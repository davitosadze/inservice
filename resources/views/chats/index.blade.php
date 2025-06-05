@section('title', 'ჩატი')
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
                        <h3 class="card-title">ჩატები</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>მომხმარებელი</th>
                                    <th>მოდელი</th>
                                    <th>თარიღი</th>
                                    <th>მოქმედება</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chats as $chat)
                                @if($chat->item_id)
                                    <tr>
                                        <td>{{ $chat->id }}</td>
                                        <td>{{ $chat->user->name }}</td>
                                        <td>
                                            @if($chat->type == 'response')

                                             <a href="{{ route('responses.show', $chat->response?->id) }}">{{ 'რეაგირება ' . $chat->response?->id . " - " . $chat->response?->name }}</a></td>
                                            @else 
                                             <a href="{{ route('repairs.show', $chat->repair?->id) }}">{{ 'რემონტი ' . $chat->repair?->id . " - " . $chat->repair?->name }}</a></td>

                                            @endif
                                        <td>{{ $chat->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('chats.show', $chat->id) }}" 
                                               class="btn btn-primary btn-sm">
                                                ნახვა
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        {{ $chats->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>