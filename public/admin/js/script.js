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
    if (href && (currentURL.pathname === href || currentURL.href.includes(href))) {
        const parentLink = link.closest('.sidebar-item')?.querySelector('.sidebar-link-name');
        (parentLink || link).classList.add('active');
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


//................................. profile edit click start ...........................
let detailsText = document.getElementsByClassName('text-content');
const editProfile = document.getElementById('edit-profile');
const changePassword = document.getElementById('change-password');
let currentDetails = 0;

function detailsClick(event,elementId){
  event.preventDefault();
  const clickText = event.target;

  if(clickText.classList.contains('text-active')){
    return;
  }
  for(let d of detailsText){
    d.classList.remove('text-active');
  }
  clickText.classList.add('text-active');
  currentDetails = Array.from(detailsText).indexOf(clickText);

  editProfile.style.display = elementId === 'editProfile' ? 'block' : 'none';
  changePassword.style.display = elementId === 'changePassword' ? 'block' : 'none';
}
//........................................ profile edit click end ................................

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
document.addEventListener('DOMContentLoaded',function(){
  const seatPlan = document.getElementById('seat-plan');
  const seatPlanRow = document.querySelectorAll('.seat-plan-row');
  const seats = document.querySelectorAll('.seat');

  seatPlanRow.forEach(sr => {
    const totalSeats = sr.querySelectorAll('.seat').length;

    if (totalSeats > 20) {
      sr.style.display = 'grid';
      sr.style.gridTemplateColumns = `auto repeat(${totalSeats}, auto)`;
    }
  });

  seats.forEach(seatClick => {
    seatClick.addEventListener('click',function(){
      if(seatClick.classList.contains('available')){
          seatClick.classList.toggle('selected');
      }
    });

  });

  if(seatPlan){
    let isDown = false;
    let startX;
    let scrollLeft;

    seatPlan.addEventListener('mousedown', (e) => {
        isDown = true;
        startX = e.pageX - seatPlan.offsetLeft;
        scrollLeft = seatPlan.scrollLeft;
        e.preventDefault();
    });

    seatPlan.addEventListener('mouseleave', () => {
        isDown = false;
    });

    seatPlan.addEventListener('mouseup', () => {
        isDown = false;
    });

    seatPlan.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - seatPlan.offsetLeft;
        const walk = (x - startX) * 2;
        seatPlan.scrollLeft = scrollLeft - walk;
    });
  }
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


// ....................................... showtime select cinema and time dropdown start ................................
$(document).ready(function(){
  $('.ct-toggle-btn').click(function(){
    $(this).closest('.cinema-section').find('.select-time').slideToggle(500);
    $(this).find('.cinema-down').toggle();
    $(this).find('.cinema-up').toggle();
  });
});
// ....................................... showtime select cinema and time dropdown end ....................................

$(document).ready(function(){
    $('.promo-icon').click(function(){
      $('#promo-form').slideToggle(500);
    });
});


//.................................. payment method start .............................
  $(document).ready(function(){
    $('.payment-content').not(':first').hide();

    $('.custom-radio').each(function() {
        if ($(this).find('input').is(':checked')) {
            $(this).addClass('active').removeClass('inactive');
        } else {
            $(this).addClass('inactive').removeClass('active');
        }
    });

   $('.custom-radio').on('click', function(){
      $(this).find('input[type="radio"]').prop('checked', true);

      $('.payment-content').hide();

      const target = $(this).find('input[type="radio"]').data('target');
      $(target).show();

      $('.custom-radio').each(function() {
          if ($(this).find('input').is(':checked')) {
              $(this).addClass('active').removeClass('inactive');
          } else {
              $(this).addClass('inactive').removeClass('active');
          }
      });
    });
  });
//.................................. payment method end .............................
