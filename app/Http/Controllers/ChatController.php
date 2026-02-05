<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Call;
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

            // Mark as read and set timestamp
            Message::where('sender_id', $receiver_id)
                ->where('receiver_id', auth()->id())
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);
        } else {
            // Global Chat
            $messages = Message::whereNull('receiver_id')
                ->with('sender')
                ->orderBy('created_at', 'asc')
                ->take(100)
                ->get();
        }

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:51200', // 50MB max
            'receiver_id' => 'nullable|exists:users,id'
        ]);

        $type = 'text';
        $filePath = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $mime = $file->getMimeType();
            
            if (str_contains($mime, 'image')) $type = 'image';
            elseif (str_contains($mime, 'video')) $type = 'video';
            elseif (str_contains($mime, 'audio')) $type = 'audio';
            else $type = 'file';

            $filePath = $file->store('chat_files', 'public');
        }

        $message = \App\Models\Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message ?? '',
            'type' => $type,
            'file_path' => $filePath,
            'delivered_at' => now(), // Assume delivered immediately for now as we don't have real-time socket ack yet
        ]);

        return response()->json(['status' => 'Message Sent!', 'message' => $message]);
    }

    // --- CALL SYSTEM ---

    public function initiateCall(Request $request)
    {
        $receiver_id = $request->receiver_id;
        
        // Clean old calls
        \App\Models\Call::where('caller_id', auth()->id())->orWhere('receiver_id', auth()->id())->delete();

        $call = \App\Models\Call::create([
            'caller_id' => auth()->id(),
            'receiver_id' => $receiver_id,
            'status' => 'ringing',
            'call_type' => $request->call_type ?? 'audio',
            'caller_signal' => $request->signal // Peer ID/SDP
        ]);

        return response()->json($call);
    }

    public function checkIncomingCall()
    {
        $call = \App\Models\Call::with('caller')
            ->where('receiver_id', auth()->id())
            ->whereIn('status', ['ringing', 'accepted', 'ended'])
            ->latest()
            ->first();

        return response()->json($call);
    }

    public function respondToCall(Request $request)
    {
        $call = \App\Models\Call::findOrFail($request->call_id);
        $call->update([
            'status' => $request->status, // accepted or declined
            'receiver_signal' => $request->signal // Peer ID/SDP
        ]);

        return response()->json($call);
    }

    public function endCall(Request $request)
    {
        $call = \App\Models\Call::find($request->call_id);
        if ($call) {
            $call->update(['status' => 'ended']);
        }
        return response()->json(['status' => 'Call Ended']);
    }

    public function sendSignal(Request $request)
    {
        $call = \App\Models\Call::findOrFail($request->call_id);
        if (auth()->id() == $call->caller_id) {
            $call->update(['caller_signal' => $request->signal]);
        } else {
            $call->update(['receiver_signal' => $request->signal]);
        }
        return response()->json(['status' => 'Signal Sent']);
    }

    public function getSignal(Request $request)
    {
        $call = \App\Models\Call::findOrFail($request->call_id);
        return response()->json($call);
    }
}

