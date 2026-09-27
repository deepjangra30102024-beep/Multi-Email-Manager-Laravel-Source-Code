<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailAccount;
use Illuminate\Support\Facades\Auth;
use App\Services\GoogleGmailService;

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

        if (!$email) {
            return redirect()->route('inbox.index', $emailAccount)->with('error', 'Email not found.');
        }

        return view('inbox.show', compact('emailAccount', 'email'));
    }
}
