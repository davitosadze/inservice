<?php

namespace App\Http\Controllers\API;

use App\Events\NewChatMessage;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function startChat(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'item_id' => 'required',
            'type' => 'required'
        ]);

        $chat = Chat::create([
            'item_id' => $request->item_id,
            'type' => $request->type,
            'user_id' => auth()->id()
        ]);

        return response()->json(['chat_id' => $chat->id]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message' => 'required_without:images',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $message = Message::create([
            'chat_id' => $request->chat_id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin' => false
        ]);

        $imageUrls = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $media = $message
                        ->addMedia($image)
                        ->usingName($image->getClientOriginalName())
                        ->usingFileName(time() . '_' . $image->getClientOriginalName())
                        ->toMediaCollection('chat_images');
                    
                    $imageUrls[] = $media->getFullUrl();
                } catch (\Exception $e) {
                    \Log::error('Image upload failed: ' . $e->getMessage());
                }
            }
        }

        // Broadcast the message with images
        broadcast(new NewChatMessage([
            
                'chat_id' => (int)$request->chat_id,
                'id' => $message->id,
                'message' => $message->message,
                'user_name' => auth()->user()->name,
                'is_admin' => false,
                'created_at' => $message->created_at->toISOString(),
                'images' => $imageUrls
            
        ]))->toOthers();

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'user_name' => auth()->user()->name,
                'is_admin' => false,
                'created_at' => $message->created_at->toISOString(),
                'images' => $imageUrls
            ]
        ]);
    }

    public function getMessages(Request $request, $chatId)
    {
        $chat = Chat::findOrFail($chatId);
        $messages = $chat->messages()
            ->with('user')
            ->get()
            ->map(function($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'user_name' => $message->user->name,
                    'is_admin' => $message->is_admin,
                    'images' => $message->getMedia('chat_images')->map(fn($media) => $media->getFullUrl()),
                    'created_at' => $message->created_at
                ];
            });

        return response()->json(['messages' => $messages]);
    }
}
