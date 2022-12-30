<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
 <!-- Brand Logo -->
 <a class="brand-link">
   <span class="brand-text font-weight-light">INSERVICE  LLC</span>
 </a>
 <!-- Sidebar -->
 <div class="sidebar">
  <!-- Sidebar Menu -->
  <nav class="mt-2">
   <ul 
    class="nav nav-pills nav-sidebar flex-column" 
    data-widget="treeview" 
    role="menu" 
    data-accordion="false">
     <li class="nav-item">
       <a href="{{ route('dashboard') }}" class="nav-link">
         <i class="fa fa-home nav-icon"></i>
         <p>მიმოხილვა</p>
        </a>       
     </li>
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
          <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? ' active' : '' }}">
           <i class="fab nav-icon fa-elementor"></i>
           <p>მასალები</p>
          </a>
         </li>
        @endif
        @if (Auth::user()->can('ინვოისის ნახვა'))
         <li class="nav-item">
          <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? ' active' : '' }}">
           <i class="fab nav-icon fa-elementor"></i>
           <p>ინვოისები</p>
          </a>
         </li>
       @endif
       @if (Auth::user()->can('განფასების ნახვა'))
         <li class="nav-item">
          <a href="{{ route('evaluations.index') }}" class="nav-link {{ request()->routeIs('evaluations.*') ? ' active' : '' }}">
           <i class="fab nav-icon fa-elementor"></i>
           <p>განფასებები</p>
          </a>
         </li>
       @endif
       @if (Auth::user()->can('მყიდველის ნახვა'))
         <li class="nav-item">
          <a href="{{ route('purchasers.index') }}" class="nav-link {{ request()->routeIs('purchasers.*') ? ' active' : '' }}">
           <i class="fab nav-icon fa-elementor"></i>
           <p>მყიდველები</p>
          </a>
         </li>
       @endif
       @if (Auth::user()->can('რეპორტის ნახვა'))
         <li class="nav-item">
          <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? ' active' : '' }}">
           <i class="fab nav-icon fa-elementor"></i>
           <p>დეფექტურები</p>
          </a>
         </li>
       @endif
      </ul>
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
        <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? ' active' : '' }}">
         <i class="fab nav-icon fa-elementor"></i>
         <p>როლები</p>
        </a>
       </li>
       <li class="nav-item">
        <a href="{{ route('permissions.index') }}" class="nav-link {{ request()->routeIs('permissions.*') ? ' active' : '' }}">
         <i class="fab nav-icon fa-elementor"></i>
         <p>ნებართვები</p>
        </a>
       </li>
       <li class="nav-item">
         <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? ' active' : '' }}">
          <i class="fab nav-icon fa-elementor"></i>
          <p>მომხმარებლები</p>
         </a>       
       </li>
      </ul>
     </li>

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









