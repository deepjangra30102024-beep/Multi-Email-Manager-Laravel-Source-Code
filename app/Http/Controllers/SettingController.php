<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Show the settings page.
     */
    public function index()
    {
        $googleClientId = Setting::where('key', 'GOOGLE_CLIENT_ID')->value('value');
        $googleClientSecret = Setting::where('key', 'GOOGLE_CLIENT_SECRET')->value('value');
        $googleRedirectUri = Setting::where('key', 'GOOGLE_REDIRECT_URI')->value('value') ?? url('/oauth/google/callback');

        return view('settings.index', compact('googleClientId', 'googleClientSecret', 'googleRedirectUri'));
    }

    /**
     * Update the settings in the database.
     */
    public function update(Request $request)
    {
        $request->validate([
            'google_client_id' => 'required|string',
            'google_client_secret' => 'required|string',
            'google_redirect_uri' => 'required|string',
        ]);

        $settings = [
            'GOOGLE_CLIENT_ID' => $request->google_client_id,
            'GOOGLE_CLIENT_SECRET' => $request->google_client_secret,
            'GOOGLE_REDIRECT_URI' => $request->google_redirect_uri,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}
