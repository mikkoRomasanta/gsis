<nav class="navbar navbar-expand-md navbar-dark nav-bg color-font-nav color-bg-dark">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            {{-- <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button> --}}

            <!-- Branding Image -->
            @if (Auth::guest())
                <a class="navbar-brand" style="padding:0" href="{{ url('/') }}">
                    <img style="height: 2.5rem; width: 2.5rem" src="/icon.png">
                    {{ config('app.name', 'GSIS') }}
                </a>
            @else
                <a class="navbar-brand color-font-main" style="padding:0" href="{{ url('/dashboard') }}">
                    <img style="height: 2.5rem; width: 2.5rem" src="/icon.png">
                    {{ config('app.name', 'GSIS') }}
                </a>
            @endif
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <ul class="navbar-nav mr-auto">
                @if (Auth::guest()) <!-- If user is logged in/not guest. Home will redirect to user's dashboard instead of page index -->
                    <li class="nav-item"><a class="nav-link " href="/">Home</a></li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">Home
                            @if($stocksLowCount > 0)
                                {!!' <span class="badge color-bg-main color-font-dark">'.$stocksLowCount.'</span>'!!}
                            @endif
                        </a>
                    </li>
                @endif
                    <li class="nav-item"><a class="nav-link" href="/items">Inventory</a></li>
                    @if(Auth::user())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Logs</a>
                            <div class="dropdown-menu color-bg-main">
                            <a class="dropdown-item" href="/issuances">Issuance</a>
                            <a class="dropdown-item" href="/receivings">Receiving</a>
                            </div>
                        </li>
                    @endif
                    @if(Auth::guest())
                    @elseif(Auth::user()->role != 'USER')
                        @if(Auth::user()->role != 'ADMIN')
                            <li class="nav-item"><a class="nav-link " href="/admin/accounts">Admin</a></li>
                        @else
                            <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Admin</a>
                                    <div class="dropdown-menu color-bg-main">
                                    <a class="dropdown-item" href="/admin/accounts">Accounts</a>
                                    <a class="dropdown-item" href="/admin/adminlogs">Logs</a>
                                    </div>
                                </li>
                        @endif
                    @endif
                </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    {{-- <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li> --}}
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle username" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                            {{ Auth::user()->username }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu color-bg-main">
                            <a class="dropdown-item" href="/excel">Excel</a>
                            <a class="dropdown-item" href="/changepass">Change Password</a>
                            <div class="dropdown-divider"></div>
                            <a  class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
