<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">


    <!-- Brand Logo -->
    <a href="{{url('/admin/home')}}" class="brand-link">
        <img src="{{asset('/uploads/img/logo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">@lang('messages.control_panel')</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{url('/admin/home')}}" class="d-block">
                    <?php if (Auth::guard('admin')->check()) {
                        echo Auth::guard('admin')->user()->name;
                    } ?>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <?php $admin = Auth::guard('admin')->user(); ?>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
