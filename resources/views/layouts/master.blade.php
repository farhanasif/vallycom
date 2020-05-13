
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{asset('img/logo.ico')}}" type="image/vnd.microsoft.icon" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" @keyup="searchit" v-model="search" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" @click="searchit">
            <i class="fa fa-search"></i>
          </button>
        </div>
    </div>
    <!-- Right navbar links -->
    <div class="col-md-2">
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-user ml-3 mr-1"></i>
          @can('isUser')
            <span class="badge badge-danger navbar-badge">3</span>{{ Auth::user()->name }}
          @endcan
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          @auth
          <router-link to="/profile" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                {{ Auth::user()->name }}
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
              </div>
            </div>
            <!-- Message End -->
          </router-link>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item dropdown-footer" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form1').submit();">
                <i class="nav-icon fas fa-power-off mr-2 red"></i> Log out
          </a>
          <form id="logout-form1" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
          </form>
          @else
          <a href="/login" class="dropdown-item dropdown-footer"><i class="fas fa-arrow-right mr-2 red"></i>Login</a>
          <div class="dropdown-divider"></div>
          <a href="/register" class="dropdown-item dropdown-footer"><i class="fas fa-user mr-2 red"></i>Register Here</a>
          @endauth
        </div>
      </li>
      @can('isAdmin')
      @else
      <li class="nav-item drodown">
        <a class="nav-link" href="#" @click="openModal">
          <i class="fas fa-cart-arrow-down ml-3 mr-1 red"></i>
        </a>
      </li>
      @endcan
    </ul>
    </div>

  </nav>
  <!-- /.navbar -->


  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="./img/logo.png" alt="vallycom" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-bold">VALLYCOM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      @auth
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="./img/profile.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <router-link to="/profile" class="d-block">{{ Auth::user()->name }}</router-link>
        </div>
      </div>

      @can('isAdmin')
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <router-link to="/dashboard" class="nav-link">
              <i class="nav-icon fas fa-chart-bar blue"></i>
              <p>
                Dashboard
              </p>
            </router-link>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog green"></i>
              <p>
                Management
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/users" class="nav-link">
                  <i class="fas fa-users nav-icon green"></i>
                  <p>Users</p>
                </router-link>
              </li>

            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="javascript:void(0)" class="nav-link">
              <i class="nav-icon fas fa-briefcase teal"></i>
              <p>
                Master Data
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <router-link to="/department" class="nav-link">
                  <i class="fas fa-users-cog nav-icon teal"></i>
                  <p>Department</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/categories" class="nav-link">
                  <i class="fas fa-code-branch nav-icon teal"></i>
                  <p>Category</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/subcategories" class="nav-link">
                  <i class="fas fa-check nav-icon teal"></i>
                  <p>Sub-category</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/users" class="nav-link">
                  <i class="fas fa-clone nav-icon teal"></i>
                  <p>Home Sliders</p>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link to="/users" class="nav-link">
                  <i class="fas fa-cog nav-icon teal"></i>
                  <p>General Settings</p>
                </router-link>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <router-link to="/developer" class="nav-link">
              <i class="nav-icon fas fa-cogs green"></i>
              <p>
                Developer
              </p>
            </router-link>
          </li>

          <!-- <li class="nav-item">
            <router-link to="/profile" class="nav-link">
              <i class="nav-icon fas fa-user orange"></i>
              <p>
                Profile
              </p>
            </router-link>
          </li> -->
          <li class="nav-item">
            <router-link to="/invoice" class="nav-link">
              <i class="nav-icon fas fa-cog red"></i>
              <p>
                Invoice
              </p>
            </router-link>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="nav-icon fas fa-power-off red"></i>
                <p>
                    Log out
                </p>
            </a>
          </li> -->
        </ul>
      </nav>
      @endcan
      @else
      <!-- check if user role logged in -->
        @can('isUser')
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="./img/profile.png" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <router-link to="/profile" class="d-block">{{ Auth::user()->name }}</router-link>
          </div>
        </div>
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
            <li class="nav-item">
              <router-link to="/" class="nav-link">
                <i class="nav-icon fas fa-home orange"></i>
                <p>
                  Home
                </p>
              </router-link>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-copy blue"></i>
                <p>
                  Departments
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/" class="nav-link">
                    <i class="fas fa-angle-right nav-icon white"></i>
                    <p>Electronics</p>
                  </router-link>
                </li>

              </ul>
            </li>
            <li class="nav-item">
              <router-link to="/learn" class="nav-link">
                <i class="nav-icon fas fa-book-open green"></i>
                <p>
                  Learn More
                </p>
              </router-link>
            </li>
            <!-- <li class="nav-item">
              <router-link to="/profile" class="nav-link">
                <i class="nav-icon fas fa-user orange"></i>
                <p>
                  Profile
                </p>
              </router-link>
            </li> -->
            <li class="nav-item">
              <router-link to="/invoice" class="nav-link">
                <i class="nav-icon fas fa-campground yellow"></i>
                <p>
                  Campaign
                </p>
              </router-link>
            </li>
            @auth
            @else
            <li class="nav-item">
              <a href="/login" class="nav-link">
                <i class="nav-icon fas fa-arrow-right red"></i>
                <p>
                  Login
                </p>
              </a>
            </li>
            @endauth
          </ul>
        </nav>
        @endcan
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
            <li class="nav-item">
              <router-link to="/" class="nav-link">
                <i class="nav-icon fas fa-home orange"></i>
                <p>
                  Home
                </p>
              </router-link>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-copy blue"></i>
                <p>
                  Departments
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link to="/" class="nav-link">
                    <i class="fas fa-angle-right nav-icon white"></i>
                    <p>Electronics</p>
                  </router-link>
                </li>

              </ul>
            </li>
            <li class="nav-item">
              <router-link to="/learn" class="nav-link">
                <i class="nav-icon fas fa-book-open green"></i>
                <p>
                  Learn More
                </p>
              </router-link>
            </li>
            <!-- <li class="nav-item">
              <router-link to="/profile" class="nav-link">
                <i class="nav-icon fas fa-user orange"></i>
                <p>
                  Profile
                </p>
              </router-link>
            </li> -->
            <li class="nav-item">
              <router-link to="/invoice" class="nav-link">
                <i class="nav-icon fas fa-campground yellow"></i>
                <p>
                  Campaign
                </p>
              </router-link>
            </li>
            @auth
            @else
            <li class="nav-item">
              <a href="/login" class="nav-link">
                <i class="nav-icon fas fa-arrow-right red"></i>
                <p>
                  Login
                </p>
              </a>
            </li>
            @endauth
          </ul>
        </nav>
      @endauth
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- for example router view -->
          <router-view></router-view>
          <!-- set progressbar -->
          <vue-progress-bar></vue-progress-bar>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <div class="modal" tabindex="-1" role="dialog" id="cartModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">My Cart</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <div v-for="(cat, n) in cats">
          <p>
            <span class="cat">@{{ cat.name }}</span>
            <button @click="removeCat(n)">Remove</button>
          </p>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Checkout</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Footer -->
  <footer class="main-footer no-print">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      <a style="background-color:black;color:white;text-decoration:none;padding:4px 6px;font-family:-apple-system, BlinkMacSystemFont, &quot;San Francisco&quot;, &quot;Helvetica Neue&quot;, Helvetica, Ubuntu, Roboto, Noto, &quot;Segoe UI&quot;, Arial, sans-serif;font-size:12px;font-weight:bold;line-height:1.2;display:inline-block;border-radius:3px" href="https://unsplash.com/@mnzoutfits?utm_medium=referral&amp;utm_campaign=photographer-credit&amp;utm_content=creditBadge" target="_blank" rel="noopener noreferrer" title="Download free do whatever you want high-resolution photos from Mnz"><span style="display:inline-block;padding:2px 3px"><svg xmlns="http://www.w3.org/2000/svg" style="height:12px;width:auto;position:relative;vertical-align:middle;top:-2px;fill:white" viewBox="0 0 32 32"><title>unsplash-logo</title><path d="M10 9V0h12v9H10zm12 5h10v18H0V14h10v9h12v-9z"></path></svg></span><span style="display:inline-block;padding:2px 3px">Mnz</span></a>
        <a style="background-color:black;color:white;text-decoration:none;padding:4px 6px;font-family:-apple-system, BlinkMacSystemFont, &quot;San Francisco&quot;, &quot;Helvetica Neue&quot;, Helvetica, Ubuntu, Roboto, Noto, &quot;Segoe UI&quot;, Arial, sans-serif;font-size:12px;font-weight:bold;line-height:1.2;display:inline-block;border-radius:3px" href="https://unsplash.com/@brucemars?utm_medium=referral&amp;utm_campaign=photographer-credit&amp;utm_content=creditBadge" target="_blank" rel="noopener noreferrer" title="Download free do whatever you want high-resolution photos from bruce mars"><span style="display:inline-block;padding:2px 3px"><svg xmlns="http://www.w3.org/2000/svg" style="height:12px;width:auto;position:relative;vertical-align:middle;top:-2px;fill:white" viewBox="0 0 32 32"><title>unsplash-logo</title><path d="M10 9V0h12v9H10zm12 5h10v18H0V14h10v9h12v-9z"></path></svg></span><span style="display:inline-block;padding:2px 3px">bruce mars</span></a>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2019-2020 <a href="http://entertechbd.com">Entertech</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
@auth
<script>
    window.user = @json(auth()->user())
</script>
@endauth
<script src="/js/app.js"></script>
<script src="https://adminlte.io/themes/dev/AdminLTE/plugins/chart.js/Chart.min.js"></script>
</body>
</html>
