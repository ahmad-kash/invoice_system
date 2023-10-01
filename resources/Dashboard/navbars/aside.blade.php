        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="brand-link">
                <img src="{{ URL::asset('dist/img/logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3"
                    style="opacity: .8">
                <span class="brand-text font-weight-light">نظام الفواتير</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('home') }}" @class(['nav-link', 'active' => Request::routeIs('home')])>
                                <p>لوحة التحكم</p>
                            </a>
                        </li>
                        @can('show section')
                            <li class="nav-item">
                                <a href="{{ route('sections.index') }}" @class(['nav-link', 'active' => Request::routeIs('sections.index')])>
                                    <p>الاقسام</p>
                                </a>
                            </li>
                        @endcan
                        @can('show product')
                            <li class="nav-item">
                                <a href="{{ route('products.index') }}" @class(['nav-link', 'active' => Request::routeIs('products.index')])>
                                    <p>المنتجات</p>
                                </a>
                            </li>
                        @endcan
                        @can('show invoice')
                            <li class="nav-item">
                                <a href="{{ route('invoices.index') }}" @class(['nav-link', 'active' => Request::routeIs('invoices.index')])>
                                    <p>الفواتير</p>
                                </a>
                            </li>
                        @endcan
                        @can('show user')
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" @class(['nav-link', 'active' => Request::routeIs('users.index')])>
                                    <p>المستخدمين</p>
                                </a>
                            </li>
                        @endcan
                        @can('show role')
                            <li class="nav-item">
                                <a href="{{ route('roles.index') }}" @class(['nav-link', 'active' => Request::routeIs('roles.index')])>
                                    <p>الادوار</p>
                                </a>
                            </li>
                        @endcan
                        @canany(['restore user', 'restore invoice'])
                            <li @class([
                                'nav-item has-treeview',
                                'menu-open' =>
                                    Request::routeIs('invoices.archive.index') ||
                                    Request::routeIs('users.archive.index'),
                            ])>
                                <a href="#"@class([
                                    'nav-link',
                                    'active' =>
                                        Request::routeIs('invoices.archive.index') ||
                                        Request::routeIs('users.archive.index'),
                                ])>
                                    <p>
                                        الارشيف
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('restore invoice')
                                        <li class="nav-item">
                                            <a href="{{ route('invoices.archive.index') }}" @class([
                                                'nav-link',
                                                'active' => Request::routeIs('invoices.archive.index'),
                                            ])>
                                                <p>ارشيف الفواتير</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('restore user')
                                        <li class="nav-item">
                                            <a href="{{ route('users.archive.index') }}" @class([
                                                'nav-link',
                                                'active' => Request::routeIs('users.archive.index'),
                                            ])>
                                                <p>ارشيف المستخدمين</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
