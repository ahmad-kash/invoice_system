<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav mr-auto-navbav">
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                aria-expanded="false">
                {{ Auth::user()->name }}
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            @php
                $unreadNotifications = auth()->user()->unreadNotifications;
            @endphp
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="fa fa-bell"></i>
                <span class="badge badge-warning navbar-badge">{{ $unreadNotifications->count() }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right"
                style="max-height:20rem;max-width:20rem;overflow-y:auto;overflow-x:hidden; ">

                <div class="dropdown-item dropdown-header d-flex justify-content-between align-items-center">
                    <span class="mr-1">عدد الاشعارات
                        {{ $unreadNotifications->count() }}</span>
                    <form method="POST" action="{{ route('notifications.showAll') }}">
                        @csrf
                        <button class="btn btn-primary btn-sm">تحديد الكل كمقروء</button>
                    </form>

                </div>
                @foreach ($unreadNotifications as $notification)
                    <div class="dropdown-divider"></div>
                    <x-notification.notify :notification="$notification" />
                @endforeach
            </div>
        </li>
    </ul>
</nav>
