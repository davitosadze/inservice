<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a class="brand-link">
        <span class="brand-text font-weight-light">INSERVICE LLC</span>
    </a>
    <!-- Sidebar -->
    <div style="overflow-y: auto!important" class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if (!Auth::user()->hasRole('ინჟინერი'))
                    <li class="nav-item">

                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class="fa fa-home nav-icon"></i>
                            <p>მთავარი</p>
                        </a>

                    </li>
                @endif

                @if (Auth::user()->can('ჩატი'))
                <li class="nav-item">
                    <a href="{{ route('chats.index') }}" class="nav-link {{ request()->routeIs('chats.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>ჩატი</p>
                    </a>
                </li>
                @endif

                @if (Auth::user()->can('ინვოისის ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('invoices.index') }}"
                            class="nav-link {{ request()->routeIs('invoices.*') ? ' active' : '' }}">
                            <i class="fab nav-icon fa-elementor"></i>
                            <p>ინვოისი</p>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->can('განფასების ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('evaluations.index') }}"
                            class="nav-link {{ request()->routeIs('evaluations.*') ? ' active' : '' }}">
                            <i class="fab nav-icon fa-elementor"></i>
                            <p>განფასება</p>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->can('რეპორტის ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}"
                            class="nav-link {{ request()->routeIs('reports.*') ? ' active' : '' }}">
                            <i class="fab nav-icon fa-elementor"></i>
                            <p>დეფექტური აქტი</p>
                        </a>
                    </li>
                @endif
                <hr>

                @if (Auth::user()->can('კლიენტის ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('clients.index') }}"
                            class="nav-link {{ request()->routeIs('clients.*') ? ' active' : '' }}">
                            <i class="fab nav-icon fa-elementor"></i>
                            <p>კლიენტები</p>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->can('მყიდველის ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('purchasers.index') }}"
                            class="nav-link {{ request()->routeIs('purchasers.*') ? ' active' : '' }}">
                            <i class="fab nav-icon fa-elementor"></i>
                            <p>მომსახურე ობიექტები</p>
                        </a>
                    </li>
                @endif



                <hr>


                @if (Auth::user()->can('სერვისის ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('services.index', ['type' => 'pending']) }}"
                            class="nav-link {{ request()->routeIs('services.*') && request()->query('type') == 'pending' ? 'active' : '' }}">
                            <i class="fab nav-icon fa-elementor"></i>
                            <span>აქტიური <br> გეგმიური სამუშაოები</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->can('სერვისის ნახვა'))
                    @if (!Auth::user()->hasRole('ინჟინერი'))
                        <li class="nav-item">
                            <a href="{{ route('services.index', ['type' => 'done']) }}"
                                class="nav-link  {{ request()->routeIs('services.*') && request()->query('type') == 'done' ? 'active' : '' }}">
                                <i class="fab nav-icon fa-elementor"></i>
                                <span>დასრულებული <br> გეგმიური სამუშაოები</span>

                            </a>
                        </li>
                    @endif
                @endif

                @if (Auth::user()->can('რეაგირების ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('responses.index', ['type' => 'pending']) }}"
                            class="nav-link {{ request()->routeIs('responses.*') && request()->query('type') == 'pending' ? 'active' : '' }}">
                            <i class="fab nav-icon fa-elementor"></i>
                            <span> სწრაფი რეაგირებები</span>
                        </a>
                    </li>
                @endif

                {{-- @if (Auth::user()->can('რეაგირების ნახვა'))
                    @if (!Auth::user()->hasRole('ინჟინერი'))
                        <li class="nav-item">
                            <a href="{{ route('responses.index', ['type' => 'done']) }}"
                                class="nav-link  {{ request()->routeIs('responses.*') && request()->query('type') == 'done' ? 'active' : '' }}">
                                <i class="fab nav-icon fa-elementor"></i>
                                <span>დასრულებული <br> სწრაფი რეაგირებები</span>
                            </a>
                        </li>
                    @endif
                @endif --}}




                @if (Auth::user()->can('რემონტის ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('repairs.index', ['type' => 'pending']) }}"
                            class="nav-link {{ request()->routeIs('repairs.*') && request()->query('type') == 'pending' ? 'active' : '' }}">
                            <i class="fab nav-icon fa-elementor"></i>
                            <span>სარემონტო სამუშაოები</span>
                        </a>
                    </li>
                @endif

                {{-- @if (Auth::user()->can('რემონტის ნახვა'))
                    @if (!Auth::user()->hasRole('ინჟინერი'))
                        <li class="nav-item">
                            <a href="{{ route('repairs.index', ['type' => 'done']) }}"
                                class="nav-link  {{ request()->routeIs('repairs.*') && request()->query('type') == 'done' ? 'active' : '' }}">
                                <i class="fab nav-icon fa-elementor"></i>
                                <p>დასრულებული <br> სარემონტო სამუშაოები</p>
                            </a>
                        </li>
                    @endif
                @endif --}}


                <hr>





                {{-- Main Options --}}
                @if (Auth::user()->can('ძირითადი პარამეტრების ნახვა'))
                <li class="nav-item menu-is-opening">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>ძირითადი პარამეტრები
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


                        @if (Auth::user()->can('isInter'))
                            <li class="nav-item">
                                <a href="{{ route('options.index') }}" class="nav-link">
                                    <i class="fas fa-wrench nav-icon"></i>
                                    <p>ფასნამატის მაჩვენებელი</p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>
                @endif

                @if (Auth::user()->can('მომხმარებლის პარამეტრების ნახვა'))
      
                <li class="nav-item menu-is-opening">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>მომხმარებლის პარამეტრები
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('instructions.index') }}" class="nav-link">
                                <i class="fas fa-wrench nav-icon"></i>
                                <p>ინსტრუქციები</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif


                {{-- Additional Options --}}
                @if (Auth::user()->can('სხვა პარამეტრების ნახვა'))
                <li class="nav-item menu-is-opening">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>სხვა პარამეტრები
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (Auth::user()->can('მასალის ნახვა'))
                            <li class="nav-item">
                                <a href="{{ route('categories.index') }}"
                                    class="nav-link {{ request()->routeIs('categories.*') ? ' active' : '' }}">
                                    <i class="fab nav-icon fa-elementor"></i>
                                    <p>გასაყიდი პროდუქციის <br> და სერვისების ბაზა</p>
                                </a>
                            </li>
                        @endif


                        @if (Auth::user()->can('რემონტის მოწყობილობის ნახვა'))
                            <li class="nav-item">
                                <a href="{{ route('repair-devices.index') }}"
                                    class="nav-link {{ request()->routeIs('repair-devices.*') ? 'active' : '' }}">
                                    <i class="fab nav-icon fa-elementor"></i>
                                    <span>რემონტის მოწყობილობები</span>
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->can('რეაგირების რედაქტირება'))
                            <li class="nav-item menu-is-opening">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>მომხმარებლის პარამეტრები
                                    </p>
                                </a>

                                <ul class="nav nav-treeview">

                                </ul>
                            </li>
                        @endif


                        @if (Auth::user()->can('რეაგირების რედაქტირება'))
                            <li class="nav-item menu-is-opening">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>ბარათების პარამეტრები
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


                    </ul>
                </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
