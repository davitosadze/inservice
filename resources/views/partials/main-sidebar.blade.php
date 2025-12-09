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
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-home"></i>
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
                            class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice-dollar"></i>
                            <p>ინვოისი</p>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->can('რეპორტის ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}"
                            class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>დეფექტური აქტი</p>
                        </a>
                    </li>
                @endif
                <hr>

                @if (Auth::user()->can('კლიენტის ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('clients.index') }}"
                            class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>კლიენტები</p>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->can('მყიდველის ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('purchasers.index') }}"
                            class="nav-link {{ request()->routeIs('purchasers.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-building"></i>
                            <p>მომსახურე ობიექტები</p>
                        </a>
                    </li>
                @endif

                <hr>

                @if (Auth::user()->can('სერვისის ნახვა') || Auth::user()->can('რემონტის ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('calendar.index') }}" class="nav-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>კალენდარი</p>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->can('სერვისის ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('services.index', ['type' => 'pending']) }}"
                            class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>გეგმიური სამუშაოები</p>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->can('რეაგირების ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('responses.index', ['type' => 'pending']) }}"
                            class="nav-link {{ request()->routeIs('responses.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bolt"></i>
                            <p>სწრაფი რეაგირებები</p>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->can('რემონტის ნახვა'))
                    <li class="nav-item">
                        <a href="{{ route('repairs.index', ['type' => 'pending']) }}"
                            class="nav-link {{ request()->routeIs('repairs.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tools"></i>
                            <p>სარემონტო სამუშაოები</p>
                        </a>
                    </li>
                @endif

                <hr>

                {{-- Instructions - moved outside --}}
                @if (Auth::user()->can('მომხმარებლის პარამეტრების ნახვა'))
                <li class="nav-item">
                    <a href="{{ route('instructions.index') }}" class="nav-link {{ request()->routeIs('instructions.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>ინსტრუქციები</p>
                    </a>
                </li>
                @endif

                {{-- Main Options --}}
                @if (Auth::user()->can('ძირითადი პარამეტრების ნახვა'))
                <li class="nav-item {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('users.*') || request()->routeIs('options.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('users.*') || request()->routeIs('options.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            პარამეტრები
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}"
                                class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-tag"></i>
                                <p>როლები</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}"
                                class="nav-link {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-key"></i>
                                <p>ნებართვები</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}"
                                class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-cog"></i>
                                <p>მომხმარებლები</p>
                            </a>
                        </li>

                        @if (Auth::user()->can('isInter'))
                            <li class="nav-item">
                                <a href="{{ route('options.index') }}" class="nav-link {{ request()->routeIs('options.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-percentage"></i>
                                    <p>ფასნამატის მაჩვენებელი</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- Additional Options --}}
                @if (Auth::user()->can('სხვა პარამეტრების ნახვა'))
                <li class="nav-item {{ request()->routeIs('categories.*') || request()->routeIs('settings.*') || request()->routeIs('repair-devices.*') || request()->routeIs('regions.*') || request()->routeIs('systems.*') || request()->routeIs('device-types.*') || request()->routeIs('device-brands.*') || request()->routeIs('locations.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('categories.*') || request()->routeIs('settings.*') || request()->routeIs('repair-devices.*') || request()->routeIs('regions.*') || request()->routeIs('systems.*') || request()->routeIs('device-types.*') || request()->routeIs('device-brands.*') || request()->routeIs('locations.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-sliders-h"></i>
                        <p>
                            სხვა
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (Auth::user()->can('მასალის ნახვა'))
                            <li class="nav-item">
                                <a href="{{ route('categories.index') }}"
                                    class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-boxes"></i>
                                    <p>გასაყიდი პროდუქცია</p>
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->can('ლოკაციის ნახვა'))
                            <li class="nav-item">
                                <a href="{{ route('settings.index') }}"
                                    class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-envelope"></i>
                                    <p>იმეილები</p>
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->can('რემონტის მოწყობილობის ნახვა'))
                            <li class="nav-item">
                                <a href="{{ route('repair-devices.index') }}"
                                    class="nav-link {{ request()->routeIs('repair-devices.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-microchip"></i>
                                    <p>რემონტის მოწყობილობები</p>
                                </a>
                            </li>
                        @endif

                        @if (Auth::user()->can('რეაგირების რედაქტირება'))
                            <li class="nav-item {{ request()->routeIs('regions.*') || request()->routeIs('systems.*') || request()->routeIs('device-types.*') || request()->routeIs('device-brands.*') || request()->routeIs('locations.*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->routeIs('regions.*') || request()->routeIs('systems.*') || request()->routeIs('device-types.*') || request()->routeIs('device-brands.*') || request()->routeIs('locations.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-th-list"></i>
                                    <p>
                                        ბარათების პარამეტრები
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>

                                <ul class="nav nav-treeview">
                                    @if (Auth::user()->can('რეგიონის ნახვა'))
                                        <li class="nav-item">
                                            <a href="{{ route('regions.index') }}"
                                                class="nav-link {{ request()->routeIs('regions.*') ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-map-marker-alt"></i>
                                                <p>რეგიონები</p>
                                            </a>
                                        </li>
                                    @endif

                                    @if (Auth::user()->can('სისტემის ნახვა'))
                                        <li class="nav-item">
                                            <a href="{{ route('systems.index') }}"
                                                class="nav-link {{ request()->routeIs('systems.*') ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-network-wired"></i>
                                                <p>სისტემები</p>
                                            </a>
                                        </li>
                                    @endif

                                    @if (Auth::user()->can('მოწყობილობის ტიპის ნახვა'))
                                        <li class="nav-item">
                                            <a href="{{ route('device-types.index') }}"
                                                class="nav-link {{ request()->routeIs('device-types.*') ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-layer-group"></i>
                                                <p>ტიპები</p>
                                            </a>
                                        </li>
                                    @endif

                                    @if (Auth::user()->can('მოწყობილობის ბრენდის ნახვა'))
                                        <li class="nav-item">
                                            <a href="{{ route('device-brands.index') }}"
                                                class="nav-link {{ request()->routeIs('device-brands.*') ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-trademark"></i>
                                                <p>ბრენდები</p>
                                            </a>
                                        </li>
                                    @endif

                                    @if (Auth::user()->can('ლოკაციის ნახვა'))
                                        <li class="nav-item">
                                            <a href="{{ route('locations.index') }}"
                                                class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-map-pin"></i>
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
