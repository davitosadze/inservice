@section('title', 'ჩატი')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
@endpush

<x-app-layout>
    <x-slot name="header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">ჩატი</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </x-slot>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">ჩატი - {{ $chat->user->name }}</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="chat-container" id="chat-messages">
                            @foreach($chat->messages as $message)
                                <div class="message-bubble {{ $message->is_admin ? 'admin-message' : 'user-message' }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>{{ $message->user->name }}</strong>
                                    </div>
                                    
                                    <div class="message-content mt-2">
                                        {{ $message->message }}
                                    </div>

                                    @if($message->getMedia('chat_images')->count() > 0)
                                        <div class="message-images">
                                            @foreach($message->getMedia('chat_images') as $image)
                                                <a href="{{ $image->getFullUrl() }}" 
                                                   data-fancybox="gallery-{{ $message->id }}"
                                                   data-caption="{{ $message->user->name }} - {{ $message->created_at->format('d/m/Y H:i') }}">
                                                    <img src="{{ $image->getFullUrl() }}" 
                                                         alt="Chat image" 
                                                         style="max-width: 150px; height: auto;">
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="message-time text-muted">
                                        {{ $message->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <form action="{{ route('chats.reply', $chat->id) }}" 
                              method="POST" 
                              enctype="multipart/form-data" 
                              class="p-3 border-top">
                            @csrf
                            <div class="form-group">
                                <textarea name="message" 
                                         class="form-control" 
                                         rows="3" 
                                         placeholder="დაწერეთ პასუხი..."
                                         required></textarea>
                            </div>
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" 
                                           class="custom-file-input" 
                                           name="images[]" 
                                           multiple 
                                           accept="image/*">
                                    <label class="custom-file-label">აირჩიეთ სურათები</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mb-2 float-right">
                                <i class="fas fa-paper-plane mr-1"></i> გაგზავნა
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="{{ asset('js/chat-notifications.js') }}"></script>
    <script src="{{ asset('js/chat.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Make sure ChatManager is initialized after ChatNotificationSystem
            if (window.chatNotifications) {
                console.log('Initializing ChatManager...');
                window.chatManager = new ChatManager({{ $chat->id }});
            } else {
                console.error('ChatNotificationSystem not initialized');
            }
        });
    </script>
    @endpush
</x-app-layout>