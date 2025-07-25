<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="ScreenOrientation" content="autoRotate:disabled">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'მთავარი') - {{ config('app.name', 'Inservice') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ mix('css/vendors/lte-core.css') }}">
    @yield('head')
    @stack('styles')

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
</head>

<body class="font-sans antialiased hold-transition sidebar-mini layout-fixed">

    @include('partials.navigation')

    @role('ინჟინერი')
    @else
        @include('partials.main-sidebar')
    @endrole

    <main class="@role('ინჟინერი') @else content-wrapper @endrole" id="app">
        @if (Session::has('flashMessage'))
            <div class="alert alert-dismissible {{ Session::has('flashType') ? 'alert-' . session('flashType') : '' }}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> შეტყობინება!</h5>
                {{ session('flashMessage') }}
            </div>
        @endif

        @if (Session::get('success', false))
            <?php $data = Session::get('success'); ?>
            @if (is_array($data))
                @foreach ($data as $msg)
                    <div class="alert alert-success" role="alert">
                        <i class="fa fa-check"></i>
                        {{ $msg }}
                    </div>
                @endforeach
            @else
                <div class="alert alert-success" role="alert">
                    <i class="fa fa-check"></i>
                    {{ $data }}
                </div>
            @endif
        @endif

        @if (Session::get('error', false))
            <?php $data = Session::get('error'); ?>
            @if (is_array($data))
                @foreach ($data as $msg)
                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-times"></i>
                        {{ $msg }}
                    </div>
                @endforeach
            @else
                <div class="alert alert-danger" role="alert">
                    <i class="fa fa-times"></i>
                    {{ $data }}
                </div>
            @endif
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li style="list-style: none;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $header }}

        {{ $slot }}
    </main>

         <div id="notification" 
             style="display: none; position: fixed; right: 20px; bottom: 20px;" 
             class="alert alert-info">
        </div>
 
        <script>
            // Add Pusher configuration before loading chat-notifications.js
            window.pusherConfig = {
                key: '{{ config('broadcasting.connections.pusher.key') }}',
                cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}'
            };
        </script>
    <!-- Scripts -->
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/guest.js') }}" defer></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ mix('js/vendors/lte-core.js') }}"></script>
    <script src="{{ asset('js/chat-notifications.js') }}"></script>
    
    <script>
        @can('ჩატი')
            document.addEventListener('DOMContentLoaded', () => {
                window.chatNotifications = new ChatNotificationSystem();
                window.chatNotifications.init();
            });
        @endcan
    </script>
    <script type="text/javascript">
        window.addEventListener("orientationchange", function() {

            if (screen.orientation) {
                screen.orientation.lock('portrait').catch(function() {});
            }
        });

        // Initial check to lock orientation
        document.addEventListener('DOMContentLoaded', function() {
            if (screen.orientation) {
                screen.orientation.lock('portrait').catch(function() {});
            }
        });

        window.Laravel = {
            csrfToken: "{{ csrf_token() }}",
            jsPermissions: {!! auth()->check() ? auth()->user()->jsPermissions() : null !!}
        }
    </script>
    @stack('scripts')

 
</body>

</html>
