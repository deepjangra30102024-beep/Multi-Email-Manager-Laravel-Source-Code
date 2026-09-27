<?php

namespace App\Http\Controllers;

use App\Models\EmailAccount;
use Google\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        // Revoke token from Google
        $client = new Client;
        $client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));

        if ($emailAccount->access_token) {
            try {
                $client->revokeToken($emailAccount->access_token);
            } catch (\Exception $e) {
                Log::error('Failed to revoke Google token: '.$e->getMessage());
            }
        }

        // Delete from database
        $emailAccount->delete();

        return redirect()->route('dashboard')->with('success', 'Email account disconnected successfully.');
    }
}
