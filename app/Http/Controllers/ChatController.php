<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index');
    }

    public function fetchUsers()
    {
        $users = User::where('id', '!=', auth()->id())
            ->select('id', 'name', 'profile_image', 'last_seen_at')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile_image' => $user->profile_image,
                    'is_online' => $user->isOnline(),
                    'last_seen' => $user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'Never',
                    'unread_count' => Message::where('sender_id', $user->id)
                        ->where('receiver_id', auth()->id())
                        ->where('is_read', false)
                        ->count()
                ];
            });

        return response()->json($users);
    }

    public function fetchMessages(Request $request)
    {
        $receiver_id = $request->receiver_id;

        if ($receiver_id) {
            // Private Chat
            $messages = Message::where(function ($query) use ($receiver_id) {
                $query->where('sender_id', auth()->id())
                      ->where('receiver_id', $receiver_id);
            })->orWhere(function ($query) use ($receiver_id) {
                $query->where('sender_id', $receiver_id)
                      ->where('receiver_id', auth()->id());
            })->with('sender')->orderBy('created_at', 'asc')->get();

            // Mark as read
            Message::where('sender_id', $receiver_id)
                ->where('receiver_id', auth()->id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } else {
            // Global Chat
            $messages = Message::whereNull('receiver_id')
                ->with('sender')
                ->orderBy('created_at', 'asc')
                ->take(100) // Limit global messages
                ->get();
        }

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id, // Nullable for global
            'message' => $request->message,
        ]);

        return response()->json(['status' => 'Message Sent!', 'message' => $message]);
    }
}
