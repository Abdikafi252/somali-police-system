<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSetting;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $settings = $user->settings ?? $user->settings()->create();
        
        return view('settings.index', compact('user', 'settings'));
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        $user = auth()->user();
        $settings = $user->settings ?? $user->settings()->create();
        
        $settings->update([
            'theme' => $request->theme
        ]);

        return response()->json(['success' => true, 'message' => 'Theme updated successfully']);
    }

    public function updateNotifications(Request $request)
    {
        $request->validate([
            'notification_email' => 'boolean',
            'notification_push' => 'boolean',
            'notification_sound' => 'boolean',
        ]);

        $user = auth()->user();
        $settings = $user->settings ?? $user->settings()->create();
        
        $settings->update([
            'notification_email' => $request->has('notification_email'),
            'notification_push' => $request->has('notification_push'),
            'notification_sound' => $request->has('notification_sound'),
        ]);

        return back()->with('success', 'Rookaha ogeysiisyada waa la cusbooneysiiyay.');
    }
}
