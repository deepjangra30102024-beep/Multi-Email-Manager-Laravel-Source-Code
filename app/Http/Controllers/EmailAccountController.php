<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailAccount;
use Illuminate\Support\Facades\Auth;

class EmailAccountController extends Controller
{
    /**
     * Remove the specified email account from storage.
     */
    public function destroy(EmailAccount $emailAccount)
    {
        // Ensure the user owns this account
        if ($emailAccount->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $emailAccount->delete();

        return redirect()->route('dashboard')->with('success', 'Email account disconnected successfully.');
    }
}
