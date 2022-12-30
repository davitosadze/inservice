<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="{{ mix('css/vendors/lte-core.css') }}">

        @stack('styles')
        
    </head>
    <body class="font-sans antialiased hold-transition sidebar-mini layout-fixed">
        
        @include('partials.navigation')
        @include('partials.main-sidebar')

        <main class="content-wrapper" id="app">
            @if (Session::has('flashMessage'))
              <div class="alert alert-dismissible {{ Session::has('flashType') ? 'alert-'.session('flashType') : '' }}">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> შეტყობინება!</h5>
                {{ session('flashMessage') }}
              </div>
            @endif

            @if(Session::get('success', false))
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
        
        <!-- Scripts -->
        <script src="{{ mix('js/manifest.js') }}"></script>
        <script src="{{ mix('js/vendor.js') }}"></script>
        <script src="{{ mix('js/guest.js') }}" defer></script>
        <script src="{{ mix('js/app.js') }}"></script>
        <script src="{{ mix('js/vendors/lte-core.js') }}"></script>

        @stack('scripts')
        
    </body>
</html>
