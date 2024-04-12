<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a class="brand-link">
        <span class="brand-text font-weight-light">INSERVICE LLC</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if (!Auth::user()->hasRole('ინჟინერი'))
                    <li class="nav-item">

                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class="fa fa-home nav-icon"></i>
                            <p>მიმოხილვა</p>
                        </a>

                    </li>
                @endif
                <li class="nav-item menu-is-opening menu-open">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            ძირითადი
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (Auth::user()->can('მასალის ნახვა'))
                            <li class="nav-item">
                                <a href="{{ route('categories.index') }}"
                                    class="nav-link {{ request()->routeIs('categories.*') ? ' active' : '' }}">
                                    <i class="fab nav-icon fa-elementor"></i>
                                    <p>მასალები</p>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->can('ინვოისის ნახვა'))
                            <li class="nav-item">
                                <a href="{{ route('invoices.index') }}"
                                    class="nav-link {{ request()->routeIs('invoices.*') ? ' active' : '' }}">
                                    <i class="fab nav-icon fa-elementor"></i>
                                    <p>ინვოისები</p>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->can('განფასების ნახვა'))
                            <li class="nav-item">
                                <a href="{{ route('evaluations.index') }}"
                                    class="nav-link {{ request()->routeIs('evaluations.*') ? ' active' : '' }}">
                                    <i class="fab nav-icon fa-elementor"></i>
                                    <p>განფასებები</p>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->can('მყიდველის ნახვა'))
                            <li class="nav-item">
                                <a href="{{ route('purchasers.index') }}"
                                    class="nav-link {{ request()->routeIs('purchasers.*') ? ' active' : '' }}">
                                    <i class="fab nav-icon fa-elementor"></i>
                                    <p>მყიდველები</p>
                                </a>
                            </li>
                        @endif
                        @if (Auth::user()->can('რეპორტის ნახვა'))
                            <li class="nav-item">
                                <a href="{{ route('reports.index') }}"
                                    class="nav-link {{ request()->routeIs('reports.*') ? ' active' : '' }}">
                                    <i class="fab nav-icon fa-elementor"></i>
                                    <p>დეფექტურები</p>
                                </a>
                            </li>
                        @endif


                    </ul>

                    @if (Auth::user()->can('კლიენტის ნახვა'))
                <li class="nav-item">
                    <a href="{{ route('clients.index') }}"
                        class="nav-link {{ request()->routeIs('clients.*') ? ' active' : '' }}">
                        <i class="fab nav-icon fa-elementor"></i>
                        <p>კლიენტები</p>
                    </a>
                </li>
                @endif
                </li>


                @if (Auth::user()->can('isInter'))
                    <li class="nav-item menu-is-opening menu-open">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>მართვა
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('roles.index') }}"
                                    class="nav-link {{ request()->routeIs('roles.*') ? ' active' : '' }}">
                                    <i class="fab nav-icon fa-elementor"></i>
                                    <p>როლები</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('permissions.index') }}"
                                    class="nav-link {{ request()->routeIs('permissions.*') ? ' active' : '' }}">
                                    <i class="fab nav-icon fa-elementor"></i>
                                    <p>ნებართვები</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}"
                                    class="nav-link {{ request()->routeIs('users.*') ? ' active' : '' }}">
                                    <i class="fab nav-icon fa-elementor"></i>
                                    <p>მომხმარებლები</p>
                                </a>
                            </li>


                        </ul>
                    </li>
                @endif

                @if (Auth::user()->can('რეაგირების ნახვა'))
                    @if (!Auth::user()->hasRole('ინჟინერი'))
                        <li class="nav-item">
                            <a href="{{ route('responses.index', ['type' => 'done']) }}"
                                class="nav-link  {{ request()->routeIs('responses.*') && request()->query('type') == 'done' ? 'active' : '' }}">
                                <i class="fab nav-icon fa-elementor"></i>
                                <p>რეაგირებები</p>
                            </a>
                        </li>
                    @endif
                @endif

                @if (Auth::user()->can('რეაგირების ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('responses.index', ['type' => 'pending']) }}"
                            class="nav-link {{ request()->routeIs('responses.*') && request()->query('type') == 'pending' ? 'active' : '' }}">
                            <i class="fab nav-icon fa-elementor"></i>
                            <span>განსახილველი <br> რეაგირებები</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->can('რეაგირების რედაქტირება'))
                    <li class="nav-item menu-is-opening">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>რეაგირების პარამეტრები
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            @if (Auth::user()->can('რეგიონის ნახვა'))
                                <li class="nav-item">
                                    <a href="{{ route('regions.index') }}"
                                        class="nav-link {{ request()->routeIs('regions.*') ? ' active' : '' }}">
                                        <i class="fab nav-icon fa-elementor"></i>
                                        <p>რეგიონები</p>
                                    </a>
                                </li>
                            @endif

                            {{-- @if (Auth::user()->can('შემსრულებლის ნახვა'))
                            <li class="nav-item">
                                <a href="{{ route('performers.index') }}"
                                    class="nav-link {{ request()->routeIs('performers.*') ? ' active' : '' }}">
                                    <i class="fab nav-icon fa-elementor"></i>
                                    <p>შემსრულებლები</p>
                                </a>
                            </li>
                        @endif --}}

                            @if (Auth::user()->can('სისტემის ნახვა'))
                                <li class="nav-item">
                                    <a href="{{ route('systems.index') }}"
                                        class="nav-link {{ request()->routeIs('systems.*') ? ' active' : '' }}">
                                        <i class="fab nav-icon fa-elementor"></i>
                                        <p>სისტემები</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('მოწყობილობის ტიპის ნახვა'))
                                <li class="nav-item">
                                    <a href="{{ route('device-types.index') }}"
                                        class="nav-link {{ request()->routeIs('device-types.*') ? ' active' : '' }}">
                                        <i class="fab nav-icon fa-elementor"></i>
                                        <p>ტიპები</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('მოწყობილობის ბრენდის ნახვა'))
                                <li class="nav-item">
                                    <a href="{{ route('device-brands.index') }}"
                                        class="nav-link {{ request()->routeIs('device-brands.*') ? ' active' : '' }}">
                                        <i class="fab nav-icon fa-elementor"></i>
                                        <p>ბრენდები</p>
                                    </a>
                                </li>
                            @endif

                            @if (Auth::user()->can('ლოკაციის ნახვა'))
                                <li class="nav-item">
                                    <a href="{{ route('locations.index') }}"
                                        class="nav-link {{ request()->routeIs('locations.*') ? ' active' : '' }}">
                                        <i class="fab nav-icon fa-elementor"></i>
                                        <p>ლოკაციები</p>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif


                @if (Auth::user()->can('isInter'))
                    <li class="nav-item">
                        <a href="" class="nav-link">
                            <i class="fas fa-wrench nav-icon"></i>
                            <p>პარამეტრები</p>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
