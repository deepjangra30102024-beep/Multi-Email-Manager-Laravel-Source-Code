<?php

namespace App\Services;

use App\Models\EmailAccount;
use Carbon\Carbon;
use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;
use Illuminate\Support\Facades\Log;

class GoogleGmailService
{
    protected $client;

    protected $emailAccount;

    public function __construct(EmailAccount $emailAccount)
    {
        $this->emailAccount = $emailAccount;

        $this->client = new Client;
        // Disable SSL verification for local development on Windows
        $this->client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setAccessType('offline');

        $token = [
            'access_token' => $emailAccount->access_token,
            'refresh_token' => $emailAccount->refresh_token,
            'expires_in' => $emailAccount->expires_in,
        ];

        $this->client->setAccessToken($token);

        // Check if token is expired and refresh it
        if ($this->client->isAccessTokenExpired()) {
            if ($this->client->getRefreshToken()) {
                $newToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                if (! isset($newToken['error'])) {
                    $this->emailAccount->update([
                        'access_token' => $newToken['access_token'],
                        'expires_in' => $newToken['expires_in'] ?? 3599,
                    ]);
                } else {
                    Log::error('Failed to refresh token: '.json_encode($newToken));
                }
            }
        }
    }

    public function getLatestEmails($limit = 15, $pageToken = null, $query = null)
    {
        $gmail = new Gmail($this->client);

        try {
            $optParams = [
                'maxResults' => $limit,
            ];

            if ($query) {
                $optParams['q'] = $query;
            } else {
                $optParams['labelIds'] = ['INBOX'];
            }

            if ($pageToken) {
                $optParams['pageToken'] = $pageToken;
            }

            $messagesResponse = $gmail->users_messages->listUsersMessages('me', $optParams);
            $messages = $messagesResponse->getMessages();
            $nextPageToken = $messagesResponse->getNextPageToken();

            $emails = [];

            if ($messages) {
                foreach ($messages as $message) {
                    $msg = $gmail->users_messages->get('me', $message->getId(), ['format' => 'metadata']);

                    $headers = $msg->getPayload()->getHeaders();
                    $subject = '';
                    $from = '';
                    $date = '';

                    foreach ($headers as $header) {
                        if ($header->getName() === 'Subject') {
                            $subject = $header->getValue();
                        }
                        if ($header->getName() === 'From') {
                            $from = $header->getValue();
                        }
                        if ($header->getName() === 'Date') {
                            try {
                                $date = Carbon::parse($header->getValue())->format('M d, Y g:i A');
                            } catch (\Exception $e) {
                                $date = $header->getValue();
                            }
                        }
                    }

                    $emails[] = [
                        'id' => $message->getId(),
                        'subject' => $subject ?: '(No Subject)',
                        'from' => $this->cleanFromAddress($from),
                        'date' => $date,
                        'snippet' => $msg->getSnippet(),
                    ];
                }
            }

            return [
                'emails' => $emails,
                'nextPageToken' => $nextPageToken,
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching emails: '.$e->getMessage());

            return [
                'emails' => [],
                'nextPageToken' => null,
            ];
        }
    }

    private function cleanFromAddress($from)
    {
        // Removes anything between < > to just show the name if available, or just returns the email
        // But for UI, sometimes just showing the raw "Name <email>" is okay. Let's just return it as is or limit string.
        return $from;
    }

    public function getEmail($messageId)
    {
        $gmail = new Gmail($this->client);

        try {
            $msg = $gmail->users_messages->get('me', $messageId, ['format' => 'full']);

            $headers = $msg->getPayload()->getHeaders();
            $subject = '';
            $from = '';
            $to = '';
            $date = '';

            foreach ($headers as $header) {
                if ($header->getName() === 'Subject') {
                    $subject = $header->getValue();
                }
                if ($header->getName() === 'From') {
                    $from = $header->getValue();
                }
                if ($header->getName() === 'To') {
                    $to = $header->getValue();
                }
                if ($header->getName() === 'Date') {
                    try {
                        $date = Carbon::parse($header->getValue())->format('M d, Y g:i A');
                    } catch (\Exception $e) {
                        $date = $header->getValue();
                    }
                }
            }

            $body = $this->getBody($msg->getPayload());

            return [
                'id' => $msg->getId(),
                'subject' => $subject ?: '(No Subject)',
                'from' => $from,
                'to' => $to,
                'date' => $date,
                'body' => $body,
                'snippet' => $msg->getSnippet(),
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching single email: '.$e->getMessage());

            return null;
        }
    }

    private function getBody($payload)
    {
        $body = '';
        $foundHtml = false;

        // If there are parts, recursively find the body
        if ($payload->getParts()) {
            foreach ($payload->getParts() as $part) {
                if ($part->getMimeType() === 'text/html') {
                    $body = $this->decodeBody($part->getBody()->getData());
                    $foundHtml = true;
                    break;
                } elseif ($part->getMimeType() === 'text/plain' && ! $foundHtml) {
                    $body = $this->decodeBody($part->getBody()->getData());
                    // Keep looking for HTML
                } elseif ($part->getParts()) {
                    // It's a multipart/alternative or mixed
                    $nestedBody = $this->getBody($part);
                    if ($nestedBody) {
                        $body = $nestedBody;
                        $foundHtml = true; // Assume nested resolved to best available
                        break;
                    }
                }
            }
        } else {
            // No parts, just a simple body
            $body = $this->decodeBody($payload->getBody()->getData());
        }

        return $body;
    }

    private function decodeBody($data)
    {
        if (! $data) {
            return '';
        }
        // Gmail API uses URL-safe Base64
        $data = str_replace(['-', '_'], ['+', '/'], $data);

        return base64_decode($data);
    }

    public function sendEmail($to, $subject, $bodyText)
    {
        $gmail = new Gmail($this->client);

        try {
            $message = new Message;

            $rawMessageString = "To: {$to}\r\n";
            $rawMessageString .= 'Subject: =?utf-8?B?'.base64_encode($subject)."?=\r\n";
            $rawMessageString .= "Content-Type: text/html; charset=utf-8\r\n";
            $rawMessageString .= "MIME-Version: 1.0\r\n\r\n";
            $rawMessageString .= $bodyText;

            $rawMessage = rtrim(strtr(base64_encode($rawMessageString), '+/', '-_'), '=');

            $message->setRaw($rawMessage);

            $gmail->users_messages->send('me', $message);

            return true;
        } catch (\Exception $e) {
            Log::error('Error sending email: '.$e->getMessage());

            return false;
        }
    }
}
