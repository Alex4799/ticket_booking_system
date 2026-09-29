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
            <a href="{{route('admin#moviePlanCinemaList')}}" class="sidebar-link-name">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-check"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
                <span class="">Movies Plan Schedule</span>
            </a>
        </li>
        <li class="sidebar-item payment">
            <a href="{{route('admin#paymentList')}}" class="sidebar-link-name">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-dollar-sign"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><path d="M12 18V6"/></svg>
                <span class="">Payment Method</span>
            </a>
        </li>
        <li class="sidebar-item promotion">
            <a href="{{route('admin#promotionList')}}" class="sidebar-link-name">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-megaphone"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                <span class="">Promotions</span>
            </a>
        </li>
        <li class="sidebar-item product">
            <a href="#" class="sidebar-link-name collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#products" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-box"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                <span class="">Products</span>
            </a>
            <ul id="products" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li>
                    <a href="{{route('admin#categoryList')}}" class="sidebar-link ">Product Category</a>
                </li>
                <li>
                    <a href="{{route('admin#productList')}}" class="sidebar-link ">Product Lists</a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link-name collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#seller" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-venetian-mask"><path d="M18 11c-1.5 0-2.5.5-3 2"/><path d="M4 6a2 2 0 0 0-2 2v4a5 5 0 0 0 5 5 8 8 0 0 1 5 2 8 8 0 0 1 5-2 5 5 0 0 0 5-5V8a2 2 0 0 0-2-2h-3a8 8 0 0 0-5 2 8 8 0 0 0-5-2z"/><path d="M6 11c1.5 0 2.5.5 3 2"/></svg>
                <span class="">Seller</span>
            </a>
            <ul id="seller" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li>
                    <a href="#" class="sidebar-link ">Product</a>
                </li>
                <li>
                    <a href="{{route('seller#movie')}}" class="sidebar-link ">Ticket</a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item order">
            <a href="#" class="sidebar-link-name collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#order-lists" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket-check"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="m9 12 2 2 4-4"/></svg>
                <span class="">Order List</span>
            </a>
            <ul id="order-lists" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li>
                    <a href="#" class="sidebar-link ">Product</a>
                </li>
                <li>
                    <a href="{{route('admin#orderList')}}" class="sidebar-link ">Ticket</a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item ">
            <a href="#" class="sidebar-link-name collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#sales" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                <span class="">Inventory</span>
            </a>
            <ul id="sales" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li>
                    <a href="{{route('admin#supplierList')}}" class="sidebar-link ">Supplier</a>
                </li>
                <li>
                    <a href="{{route('admin#storeList')}}" class="sidebar-link ">Store</a>
                </li>
                <li>
                    <a href="#" class="sidebar-link ">Purchase</a>
                </li>
                <li>
                    <a href="#" class="sidebar-link ">History</a>
                </li>
                <li>
                    <a href="#" class="sidebar-link ">Reduce</a>
                </li>
                <li>
                    <a href="#" class="sidebar-link ">Damage Product</a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="{{route('admin#socialList')}}" class="sidebar-link-name">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rss"><path d="M4 11a9 9 0 0 1 9 9"/><path d="M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1"/></svg>
                <span class="">Social App</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{route('admin#websiteView')}}" class="sidebar-link-name">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                <span class="">Website Information</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{route('admin#recycle')}}" class="sidebar-link-name">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-recycle"><path d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5"/><path d="M11 19h8.203a1.83 1.83 0 0 0 1.556-.89 1.784 1.784 0 0 0 0-1.775l-1.226-2.12"/><path d="m14 16-3 3 3 3"/><path d="M8.293 13.596 7.196 9.5 3.1 10.598"/><path d="m9.344 5.811 1.093-1.892A1.83 1.83 0 0 1 11.985 3a1.784 1.784 0 0 1 1.546.888l3.943 6.843"/><path d="m13.378 9.633 4.096 1.098 1.097-4.096"/></svg>
                <span class="">Recycle Bin</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="{{route('admin#orderLog')}}" class="sidebar-link-name">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                <span class="">Order Log</span>
            </a>
        </li>
</ul>
