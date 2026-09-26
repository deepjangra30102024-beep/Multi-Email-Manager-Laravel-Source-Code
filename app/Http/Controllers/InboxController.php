<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailAccount;
use Illuminate\Support\Facades\Auth;

class InboxController extends Controller
{
    public function index(EmailAccount $emailAccount)
    {
        if ($emailAccount->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // For now, we'll just pass the account. Later we will fetch emails via Google API.
        return view('inbox.index', compact('emailAccount'));
    }
}
