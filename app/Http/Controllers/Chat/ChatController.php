<?php

namespace App\Http\Controllers\Chat;

use App\Events\NewChatMessage;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ChatController extends Controller
{
    public function index()
    {
        $this->authorize("view", Chat::class);
        $chats = Chat::with(['user', 'response'])
            ->latest()
            ->paginate(20);
            
        return view('chats.index', compact('chats'));
    }

    public function startChat(Request $request, $type, $model_id)
    {
        // $this->authorize("create", Chat::class);

        $chat = Chat::firstOrCreate([
            'user_id' => auth()->id(),
            'type' => $type,
            'item_id' => $model_id
        ]);

        return redirect()->route('chats.show', $chat->id);
    }

    public function show($id)
    {
        $chat = Chat::with(['messages.user', 'response'])
            ->findOrFail($id);
            
        return view('chats.show', compact('chat'));
    }

    public function pdf($id)
    {
        $chat = Chat::with(['messages.user', 'response', 'repair'])
            ->findOrFail($id);

        // Get the related item (response or repair) info
        $relatedItem = null;
        $relatedItemType = '';
        
        if ($chat->type === 'response' && $chat->response) {
            $relatedItem = $chat->response;
            $relatedItemType = 'რეაგირება';
        } elseif ($chat->type === 'repair' && $chat->repair) {
            $relatedItem = $chat->repair;
            $relatedItemType = 'რემონტი';
        }

        $name = 'chat_history_' . $chat->id . '.pdf';

        $pdf = PDF::setOptions([
            'isRemoteEnabled' => true, 
            'dpi' => 150, 
            'defaultFont' => 'sans-serif'
        ])->loadView('chats.pdf', compact('chat', 'relatedItem', 'relatedItemType'));

        return $pdf->stream($name);
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required_without:images',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $message = Message::create([
            'chat_id' => $id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin' => true
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $message
                    ->addMedia($image)
                    ->toMediaCollection('chat_images');
            }
        }

        // Broadcast the message
        // broadcast(new NewChatMessage([
        //     'chat_id' => $id,
        //     'id' => $message->id,
        //     'message' => $message->message,
        //     'user_name' => auth()->user()->name,
        //     'is_admin' => true,
        //     'created_at' => $message->created_at,
        //     'images' => $message->getMedia('chat_images')->map->getFullUrl()
        // ]))->toOthers();

        return back();
    }
}