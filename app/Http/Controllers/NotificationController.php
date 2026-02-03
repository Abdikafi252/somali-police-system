<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return view('notifications.show', compact('notification'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back()->with('success', 'Ogeysiiska waa la akhriyay.');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Dhammaan ogeysiisyada waa la akhriyay.');
    }

    public function checkNew(Request $request)
    {
        $count = auth()->user()->unreadNotifications()
            ->where('created_at', '>', now()->subMinutes(1))
            ->count();
            
        return response()->json(['count' => $count]);
    }

    public function destroy($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();
        return back()->with('success', 'Ogeysiiska waa la tirtiray.');
    }

    public function destroyAll()
    {
        auth()->user()->readNotifications()->delete();
        return back()->with('success', 'Dhammaan ogeysiisyada la akhriyay waa la tirtiray.');
    }
}
