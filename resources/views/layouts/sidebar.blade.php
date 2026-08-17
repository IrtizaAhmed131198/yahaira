<div class="side-bar">
    <a href="{{ route('dashboard') }}"><img src="{{ asset('images/logo.png') }}" class="img-fluid" alt=""></a>
    <ul>
        <li>
            <a href="{{ route('dashboard') }}">My Dashboard</a>
        </li>
        @if(Auth::user()->hasRole('admin|setter|closer'))
        <li>
            <a href="{{ route('lead-management') }}">Lead Management</a>
        </li>
        @endif
        @if(Auth::user()->hasRole('admin|closer'))
        <li>
            <a href="{{ route('sales-closing') }}">Sales / Closing</a>
        </li>
        <li>
            <a href="{{ route('client-intake-application') }}">Client Intake / Application</a>
        </li>
        @endif
        @if(Auth::user()->hasRole('admin|closer|billing'))
        <li>
            <a href="{{ route('payments') }}">Payments</a>
        </li>
        @endif
        @if(Auth::user()->hasRole('admin'))
        <li>
            <a href="{{ route('packages') }}">Packages</a>
        </li>
        @endif
        @if(Auth::user()->hasRole('admin|matchmaker'))
        <li>
            <a href="{{ route('matchmaking') }}">Matchmaking & Scheduling</a>
        </li>
        @endif
        @if(Auth::user()->hasRole('admin'))
        <li>
            <a href="{{ route('team-and-user-management') }}">Team & User Management</a>
        </li>
        <li>
            <a href="{{ route('reporting') }}">Dashboard & Reporting</a>
        </li>
        @endif
        @if(Auth::user()->hasRole('admin|billing'))
        <li>
            <a href="{{ route('financial') }}">Founder-Only Financial Dashboard</a>
        </li>
        @endif
        <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        </li>
    </ul>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>


<div class="side-bar mobile-menu">
       <a href="{{ route('dashboard') }}"><img src="{{ asset('images/logo.png') }}" class="img-fluid" alt=""></a>
    <img src="{{ asset('images/menu-icon.png') }}" class="img-fluid menu-mob" alt="">
    <ul>
        <li>
            <a href="{{ route('dashboard') }}">My Dashboard</a>
        </li>
        @if(Auth::user()->hasRole('admin|setter'))
        <li>
            <a href="{{ route('lead-management') }}">Lead Management</a>
        </li>
        @endif
        @if(Auth::user()->hasRole('admin|closer'))
        <li>
            <a href="{{ route('sales-closing') }}">Sales / Closing</a>
        </li>
        <li>
            <a href="{{ route('client-intake-application') }}">Client Intake / Application</a>
        </li>
        @endif
        @if(Auth::user()->hasRole('admin|closer|billing'))
        <li>
            <a href="{{ route('payments') }}">Payments</a>
        </li>
        @endif
        @if(Auth::user()->hasRole('admin'))
        <li>
            <a href="{{ route('packages') }}">Packages</a>
        </li>
        @endif
        @if(Auth::user()->hasRole('admin|matchmaker'))
        <li>
            <a href="{{ route('matchmaking') }}">Matchmaking & Scheduling</a>
        </li>
        @endif
        @if(Auth::user()->hasRole('admin'))
        <li>
            <a href="{{ route('team-and-user-management') }}">Team & User Management</a>
        </li>
        <li>
            <a href="{{ route('reporting') }}">Dashboard & Reporting</a>
        </li>
        @endif
        @if(Auth::user()->hasRole('admin|billing'))
        <li>
            <a href="{{ route('financial') }}">Founder-Only Financial Dashboard</a>
        </li>
        @endif
        <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        </li>
    </ul>
</div>
