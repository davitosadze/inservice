<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">{{auth()->user()->name}}</a>
            <div class="dropdown-menu">
                <a class="nav-link" href="{{ route('profile') }}">{{ __('პროფილი') }}</a>
                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link 
                        :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();"> გასვლა 
                    </x-responsive-nav-link>
                </form>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
