<ul class="sidebar-nav">
        <li class="sidebar-item admin">
            <a href="#" class="sidebar-link-name collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#admin" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span class="">Admin</span>
            </a>
            <ul id="admin" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li>
                    <a href="{{route('admin#list')}}" class="sidebar-link ">Admin Lists</a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item cinema">
            <a href="#" class="sidebar-link-name collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#cinemas" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-film"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 3v18"/><path d="M3 7.5h4"/><path d="M3 12h18"/><path d="M3 16.5h4"/><path d="M17 3v18"/><path d="M17 7.5h4"/><path d="M17 16.5h4"/></svg>
                <span class="">Cinemas</span>
            </a>
            <ul id="cinemas" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li>
                    <a href="{{route('admin#cinemaList')}}" class="sidebar-link ">Cinema Lists</a>
                </li>
                <li>
                    <a href="{{route('admin#hallList')}}" class="sidebar-link ">Hall Lists</a>
                </li>
                <li>
                    <a href="{{route('admin#seatPlanList')}}" class="sidebar-link ">Seat Plan Lists</a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item genre">
            <a href="{{route('admin#genreList')}}" class="sidebar-link-name">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-drama"><path d="M10 11h.01"/><path d="M14 6h.01"/><path d="M18 6h.01"/><path d="M6.5 13.1h.01"/><path d="M22 5c0 9-4 12-6 12s-6-3-6-12c0-2 2-3 6-3s6 1 6 3"/><path d="M17.4 9.9c-.8.8-2 .8-2.8 0"/><path d="M10.1 7.1C9 7.2 7.7 7.7 6 8.6c-3.5 2-4.7 3.9-3.7 5.6 4.5 7.8 9.5 8.4 11.2 7.4.9-.5 1.9-2.1 1.9-4.7"/><path d="M9.1 16.5c.3-1.1 1.4-1.7 2.4-1.4"/></svg>
                <span class="">Genre</span>
            </a>
        </li>
        <li class="sidebar-item movie">
            <a href="#" class="sidebar-link-name collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#movies" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clapperboard"><path d="M20.2 6 3 11l-.9-2.4c-.3-1.1.3-2.2 1.3-2.5l13.5-4c1.1-.3 2.2.3 2.5 1.3Z"/><path d="m6.2 5.3 3.1 3.9"/><path d="m12.4 3.4 3.1 4"/><path d="M3 11h18v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z"/></svg>
                <span class="">Movies</span>
            </a>
            <ul id="movies" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li>
                    <a href="{{route('admin#movieList')}}" class="sidebar-link ">Movie</a>
                </li>
            </ul>
        </li>

        <li class="sidebar-item movie_plan">
            <a href="#" class="sidebar-link-name">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-check"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
                <span class="">Movies Plan Schedule</span>
            </a>
        </li>
</ul>
