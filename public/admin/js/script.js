//................................ dashboard side bar toggle start .......................
document.addEventListener('DOMContentLoaded', () => {
    const hamBurgers = document.querySelectorAll('.toggle-btn');
    const sidebar = document.querySelector('#sidebar');
    const sidebarNav = document.querySelector('.sidebar-nav');
    const links = document.querySelectorAll('.sidebar-link-name, .sidebar-link');

    const toggleSidebar = (state) => {
        sidebar.classList.toggle('expand', state);
        sessionStorage.setItem('sidebar-state', state ? 'expanded' : 'collapsed');
    };
    toggleSidebar(sessionStorage.getItem('sidebar-state') === 'expanded');

    hamBurgers.forEach((hamBurger) => {
        hamBurger.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleSidebar(!sidebar.classList.contains('expand'));
        });
    });

    document.body.addEventListener('click', function (event) {
        if (window.innerWidth >= 992 && !sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
            toggleSidebar(false);
        }
    });

    sidebar.addEventListener('click', (e) => e.stopPropagation());

    const currentURL = new URL(window.location.href);

    links.forEach((link) => {
        link.addEventListener('click', (e) => {
            e.stopPropagation();
                const parentItem = link.closest('.sidebar-item');
                const hasDropdown = parentItem?.querySelector('.sidebar-dropdown');
                if (link.classList.contains('sidebar-link-name') && hasDropdown) {
                    toggleSidebar(true);
                } else {
                    toggleSidebar(false);
                }
                links.forEach((l) => l.classList.remove('active'));
                (parentItem?.querySelector('.sidebar-link-name') || link).classList.add('active');
        });

        const href = link.getAttribute('href');
        if (href) {
            const linkURL = new URL(href, window.location.origin);
            if (
                currentURL.pathname === linkURL.pathname &&
                currentURL.hash === linkURL.hash
            ) {
                const parentLink = link.closest('.sidebar-item')?.querySelector('.sidebar-link-name');
                (parentLink || link).classList.add('active');
            }
        }
    });

    sidebarNav.scrollTop = +sessionStorage.getItem('sidebar-scroll-position') || 0;
    sidebarNav.addEventListener('scroll', () =>
        sessionStorage.setItem('sidebar-scroll-position', sidebarNav.scrollTop)
    );
});
//................................ dashboard side bar toggle end .......................

//................................ sidebar toggle phone size start .......................
const sidebarToggle = document.querySelector("#sidebar-toggle");
const sidebar = document.querySelector("#sidebar");
sidebarToggle.addEventListener("click", function () {
    sidebar.classList.toggle("active");
});
//............................... sidebar toggle phone size  end .......................

//................................. profile tab click start ...........................
document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".tab-btn");
    const contents = document.querySelectorAll(".tab-content");
    tabs.forEach(tab => {
        tab.addEventListener("click", function () {
            const target = this.getAttribute("data-target");
            tabs.forEach(t => t.classList.remove("active"));
            contents.forEach(c => c.classList.remove("active"));
            this.classList.add("active");
            document.getElementById(target).classList.add("active");
        });
    });
});
//........................................ profile tab click end ................................


//....................................... schedule table start ..................................
document.addEventListener("DOMContentLoaded", function() {
  const ranges = [
    { range: document.getElementById('range1'), rangeV: document.getElementById('rangeV1') },
    { range: document.getElementById('range2'), rangeV: document.getElementById('rangeV2') }
  ];

  const setValue = (range, rangeV) => {
    const newValue = Number((range.value - range.min) * 100 / (range.max - range.min));
    const newPosition = 10 - (newValue * 0.2);
    rangeV.innerHTML = `<span>${range.value}</span>`;
    rangeV.style.left = `calc(${newValue}% + (${newPosition}px))`;
  };

  ranges.forEach(({ range, rangeV }) => {
    if (range && rangeV) {
      setValue(range, rangeV);
      range.addEventListener('input', () => setValue(range, rangeV));
    }
  });
});
//....................................... schedule table end ..................................


//.......................................  seat in theater start..................................
document.addEventListener('DOMContentLoaded', function () {
    const seatPlan = document.getElementById('seat-plan');
    const seatPlanContainer = document.getElementById('seat-plan-container');

    if (!seatPlan || !seatPlanContainer) return;

    document.querySelectorAll('.seat-plan-row').forEach(row => {
        const totalSeats = row.querySelectorAll('.seat').length;
        if (totalSeats > 20) {
            row.style.display = 'grid';
            row.style.gridTemplateColumns = `auto repeat(${totalSeats}, auto)`;
        }
    });

    document.querySelectorAll('.seat').forEach(seat => {
        seat.addEventListener('click', function () {
            if (this.classList.contains('available')) {
                this.classList.toggle('selected');
            }
        });
    });

    // zoom
    let zoomLevel = 1;
    const zoomStep = 0.1;
    const minZoom = 0.7;

    function updateZoom() {
        seatPlan.style.transform = `scale(${zoomLevel})`;
    }

    document.getElementById('zoom-out')?.addEventListener('click', function () {
        zoomLevel = Math.max(zoomLevel - zoomStep, minZoom);
        updateZoom();
    });

    document.getElementById('zoom-reset')?.addEventListener('click', function () {
        zoomLevel = 1;
        updateZoom();

        seatPlanContainer.scrollLeft = (seatPlanContainer.scrollWidth - seatPlanContainer.clientWidth) / 2;
        seatPlanContainer.scrollTop = (seatPlanContainer.scrollHeight - seatPlanContainer.clientHeight) / 2;
    });
});
//.......................................  seat in theater end ..................................


// ..................................................... showtime select date start ....................................
document.addEventListener('DOMContentLoaded',function(){
    const selectDates = document.querySelectorAll('.js-select-date');
    const currentUrl = window.location.href;
    selectDates.forEach(function(selectDate){
        const parentLink = selectDate.closest('a');
        if(parentLink && parentLink.href === currentUrl){
            selectDate.classList.add('js-select-active');
        }

        selectDate.addEventListener('click',function(){
            selectDates.forEach(btn => btn.classList.remove('js-select-active'));
            selectDate.classList.add('js-select-active');
        });
    });
  });
// ..................................................... showtime select date end ....................................


//.................................. payment method start .............................
$(document).ready(function(){
    $('.payment-radio').on('change', function () {
        $('.payment-info').addClass('d-none');
        $($(this).data('target')).removeClass('d-none');
        $('#payment_method').val($(this).val());
    });
    $('#payment_method').val($('.payment-radio:checked').val());
});
//.................................. payment method end .............................

