<div class="page-sidebar">
    <ul class="list-unstyled accordion-menu">
        <li class="sidebar-title">
            Main
        </li>
        <li class="@if(request()->routeIs('dashboard')) active-page @endif">
            <a href="{{route('dashboard')}}"><i data-feather="home"></i>Dashboard</a>
        </li>
        <li class="sidebar-title">
            Apps
        </li>
        <li class="@if(request()->routeIs('events') || request()->routeIs('event.detail')) active-page @endif">
            <a href="{{route('events')}}"><i data-feather="inbox"></i>Events</a>
        </li>
        <li class="@if(request()->routeIs('flights')) active-page @endif">
            <a href="{{route('flights')}}"><i data-feather="inbox"></i>Flights</a>
        </li>
        <li class="@if(request()->routeIs('contact-us')) active-page @endif">
            <a href="{{route('contact-us')}}"><i data-feather="inbox"></i>Contact Us</a>
        </li>
    </ul>
</div>
