<?php

namespace App\Http\Controllers;

use App\Models\EmailAccount;
use App\Models\EmailTemplate;
use App\Services\GoogleGmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InboxController extends Controller
{
    public function index(Request $request, EmailAccount $emailAccount)
    {
        if ($emailAccount->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $query = $request->input('q');
        $pageToken = $request->input('page_token');

        $googleService = new GoogleGmailService($emailAccount);
        $result = $googleService->getLatestEmails(15, $pageToken, $query);

        $emails = $result['emails'];
        $nextPageToken = $result['nextPageToken'];

        return view('inbox.index', compact('emailAccount', 'emails', 'nextPageToken', 'query'));
    }

    public function show(EmailAccount $emailAccount, $messageId)
    {
        if ($emailAccount->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $googleService = new GoogleGmailService($emailAccount);
        $email = $googleService->getEmail($messageId);

        if (! $email) {
            return redirect()->route('inbox.index', $emailAccount)->with('error', 'Email not found.');
        }

        return view('inbox.show', compact('emailAccount', 'email'));
    }

    public function compose(EmailAccount $emailAccount)
    {
        if ($emailAccount->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $templates = EmailTemplate::where('user_id', Auth::id())->get();

        return view('inbox.compose', compact('emailAccount', 'templates'));
    }

    public function send(Request $request, EmailAccount $emailAccount)
    {
        if ($emailAccount->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $googleService = new GoogleGmailService($emailAccount);
        $success = $googleService->sendEmail($request->to, $request->subject, $request->body);

        if ($success) {
            return redirect()->route('inbox.index', $emailAccount)->with('success', 'Email sent successfully!');
        } else {
            return back()->withInput()->with('error', 'Failed to send email. Please try again.');
        }
    }
    public function destroy(EmailAccount $emailAccount, $messageId)
    {
        if ($emailAccount->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $googleService = new GoogleGmailService($emailAccount);
        $success = $googleService->deleteEmail($messageId);

        if ($success) {
            return redirect()->route('inbox.index', $emailAccount)->with('success', 'Email deleted successfully.');
        } else {
            return redirect()->route('inbox.index', $emailAccount)->with('error', 'Failed to delete email.');
        }
    }
}
