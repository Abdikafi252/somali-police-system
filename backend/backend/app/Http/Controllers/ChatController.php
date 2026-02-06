<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index');
    }

    // USERS LIST
    public function users()
    {
        return User::where('id', '!=', Auth::id())
            ->select('id', 'name', 'profile_image', 'last_seen_at')
            ->orderBy('last_seen_at', 'desc') // Show active users first
            ->get();
    }

    // LOAD MESSAGES
    public function messages(Request $request)
    {
        $rid = $request->receiver_id;

        if ($rid) {
            // Private Chat
            return Message::where(function ($q) use ($rid) {
                $q->where('sender_id', Auth::id())
                    ->where('receiver_id', $rid);
            })
                ->orWhere(function ($q) use ($rid) {
                    $q->where('sender_id', $rid)
                        ->where('receiver_id', Auth::id());
                })
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            // Global Chat
            return Message::whereNull('receiver_id')
                ->with('sender')
                ->orderBy('created_at', 'asc')
                ->take(150)
                ->get();
        }
    }

    // SEND MESSAGE
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'receiver_id' => 'nullable'
        ]);

        $msg = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

        broadcast(new \App\Events\MessageSent($msg))->toOthers();

        return response()->json(['success' => true]);
    }
}
