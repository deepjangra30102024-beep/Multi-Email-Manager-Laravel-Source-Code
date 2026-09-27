<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\EmailAccount;
use Google\Client;
use Illuminate\Support\Facades\Auth;

class OAuthController extends Controller
{
    /**
     * Redirect the user to the Google OAuth consent screen.
     */
    public function redirect()
    {
        $client = new Client();
        // Disable SSL verification for local development on Windows
        $client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->addScope('https://mail.google.com/');
        $client->addScope('email');
        $client->setAccessType('offline');
        $client->setPrompt('consent'); // Force to get refresh token every time

        return redirect($client->createAuthUrl());
    }

    /**
     * Handle the callback from Google.
     */
    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return redirect('/dashboard')->with('error', 'Google authentication was denied.');
        }

        $client = new Client();
        // Disable SSL verification for local development on Windows
        $client->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));

        // Exchange authorization code for an access token.
        $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));

        if (isset($token['error'])) {
            return redirect('/dashboard')->with('error', 'Error fetching access token.');
        }

        $client->setAccessToken($token);

        // Get user info from Google
        $oauth2 = new \Google\Service\Oauth2($client);
        $googleUser = $oauth2->userinfo->get();

        $user = Auth::user();

        // Save or update the email account
        $emailAccount = EmailAccount::updateOrCreate(
            [
                'user_id' => $user->id,
                'email_address' => $googleUser->email,
            ],
            [
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'] ?? null, // Refresh token is only provided on first auth or with prompt=consent
                'expires_in' => $token['expires_in'] ?? 3599,
            ]
        );

        return redirect('/dashboard')->with('success', 'Gmail account connected successfully!');
    }
}
