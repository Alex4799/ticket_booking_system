// href clicked active link start
document.addEventListener('DOMContentLoaded', () => {
  const currentHTMLPage = window.location.pathname;
  const navLinks = document.querySelectorAll('.nav-link');
  navLinks.forEach(link => link.classList.remove('active'));

  navLinks.forEach(link => {
    const linkPath = new URL(link.href).pathname;
    if (currentHTMLPage === linkPath) {
      link.classList.add('active');
    }
  });
});
// href clicked active link end


//.............................. light mode toggle start ...............................
document.addEventListener('DOMContentLoaded', function (){
  const body = document.documentElement;
  const themeToggles = document.querySelectorAll('.theme-toggle');
  const savedTheme = localStorage.getItem('theme');

  if (savedTheme === 'light') {
    body.classList.add('light');
    themeToggles.forEach(toggle => toggle.checked = false);
  } else {
    body.classList.remove('light');
    themeToggles.forEach(toggle => toggle.checked = true);
  }

  themeToggles.forEach(toggle => {
    toggle.addEventListener('change', function () {
      const isDark = this.checked;
      if (isDark) {
        body.classList.remove('light');
        localStorage.setItem('theme', 'dark');
      } else {
        body.classList.add('light');
        localStorage.setItem('theme', 'light');
      }

    });
  });
});
//.............................. light mode toggle end ...............................


// .................................................... search movie selected btn start .................................
document.addEventListener('DOMContentLoaded', () => {
  const cinemaForm = document.getElementById('cinema-form');
  if (cinemaForm) {
    cinemaForm.addEventListener('submit', function(event) {
      event.preventDefault();
      this.submit();
    });
  }

  const customSelects = document.querySelectorAll(".custom-select");
  const selectBtns = document.querySelectorAll(".select-button");
//   const selectedValues = document.querySelectorAll(".selected-value");
  const optionsLists = document.querySelectorAll(".select-dropdown li");

  selectBtns.forEach((btn, btnIndex) => {
    btn.addEventListener('click', (event) => {
      event.preventDefault();
      customSelects[btnIndex].classList.toggle("active");
      btn.setAttribute(
        "aria-expanded",
        btn.getAttribute("aria-expanded") === "true" ? "false" : "true"
      );
    });
  });

  optionsLists.forEach((option) => {
    option.addEventListener("click", function (e) {
      const customSelect = this.closest('.custom-select');
      const selectedValue = customSelect.querySelector('.selected-value');
      const btn = customSelect.querySelector('.select-button');

      selectedValue.innerHTML = this.querySelector('label').textContent;
      customSelect.classList.remove("active");
      btn.setAttribute("aria-expanded", "false");
    });

    option.addEventListener("keyup", function (e) {
      if (e.key === "Enter") {
        const customSelect = this.closest('.custom-select');
        const selectedValue = customSelect.querySelector('.selected-value');
        const btn = customSelect.querySelector('.select-button');

        selectedValue.innerHTML = this.querySelector('label').textContent;
        customSelect.classList.remove("active");
        btn.setAttribute("aria-expanded", "false");
      }
    });
  });
});
// .................................................... search movie selected btn end .................................


// ..................................................... cinema details start ....................................
var swiper = new Swiper(".mySwiper3", {
  slidesPerView: 3,
  spaceBetween: 30,
  loop: true,
  grabCursor: true,
  navigation: {
    nextEl: ".button-next2",
    prevEl: ".button-prev2",
  },
  breakpoints: {
    0: {
        slidesPerView: 1,
    },
    770: {
        slidesPerView: 2,
    },
    1200: {
        slidesPerView: 3,
    }
  }
});
// ..................................................... cinema details end ....................................


//................................................. footer dropdown start ...........................................
$(document).ready(function(){
  $('#toggle-btn').click(function(){
    $('#drop-items').toggle(500);
    $('#down').toggle();
    $('#up').toggle();
  });
  $('#drop-items').hide();
});
// ................................................... footer dropdown end ........................................


//....................................... ......category active link start .........................................
document.addEventListener('DOMContentLoaded', function () {
    const categoryContent = document.querySelector('#category-content');
    const genrePh = document.querySelector('.genre-scroll-container');
    const categoryLinks = document.querySelectorAll('.category-link');

    const currentURL = new URL(window.location.href);
    const currentPath = currentURL.pathname + currentURL.search;

    categoryLinks.forEach(link => {
        const linkURL = new URL(link.href, window.location.origin);
        const linkPath = linkURL.pathname + linkURL.search;

        if (currentPath === linkPath) {
            link.classList.add('active');
        }

        link.addEventListener('click', function () {
            sessionStorage.setItem('genre-scroll-y', categoryContent.scrollTop);
            sessionStorage.setItem('genre-scroll-x', genrePh.scrollLeft);
        });
    });

    // Restore scroll position
    const genreScrollY = sessionStorage.getItem('genre-scroll-y');
    const genreScrollX = sessionStorage.getItem('genre-scroll-x');

    if (genreScrollY !== null) {
        categoryContent.scrollTop = parseInt(genreScrollY, 10);
    }

    if (genreScrollX !== null) {
        genrePh.scrollLeft = parseInt(genreScrollX, 10);
    }
});
//................................................category active link end..... .........................................


//............................................... genre mobile scroll start ............................................
document.addEventListener('DOMContentLoaded',function(){
  const genreScrollContainer = document.querySelector('.genre-scroll-container');

  let isDown = false;
  let startX;
  let scrollLeft;

  if(genreScrollContainer){
    genreScrollContainer.addEventListener('mousedown', (e) => {
      isDown = true;
      startX = e.pageX - genreScrollContainer.offsetLeft;
      scrollLeft = genreScrollContainer.scrollLeft;
    });

    genreScrollContainer.addEventListener('mouseleave', () => {
      isDown = false;
    });

    genreScrollContainer.addEventListener('mouseup', () => {
      isDown = false;
    });

    genreScrollContainer.addEventListener('mousemove', (e) => {
      if(!isDown) return;
      e.preventDefault();
      const x = e.pageX - genreScrollContainer.offsetLeft;
      const walk = (x - startX) * 2;
      genreScrollContainer.scrollLeft = scrollLeft - walk;
    });
  }
});
//............................................... genre mobile scroll end ............................................


//................................................ movie sorting start ..... .........................................
const sortingSelect = document.querySelector(".sorting-content");
const sortingBtn = document.querySelector(".sorting-select-btn");
const sortingValue = document.querySelector(".sorting-selected-value");
const sortingOptions = document.querySelectorAll(".sorting-dropdown li");

document.addEventListener('DOMContentLoaded', () => {
  sortingOptions.forEach((option) => {
    const label = option.querySelector('label');
    if (label.textContent === sortingValue.textContent) {
      option.querySelector('input').checked = true;
    }
  });
});

if(sortingBtn){
  sortingBtn.addEventListener('click', (event) => {
    event.preventDefault();
    sortingSelect.classList.toggle("active");
    sortingBtn.setAttribute(
      "aria-expanded",
      sortingBtn.getAttribute("aria-expanded") === "true" ? "false" : "true"
    );
  });
};

sortingOptions.forEach((option) => {
  option.addEventListener("click", function () {
    sortingValue.innerHTML = this.querySelector('label').textContent;
    sortingSelect.classList.remove("active");
    sortingBtn.setAttribute("aria-expanded", "false");

    sortingOptions.forEach(opt => opt.querySelector('input').checked = false);
    this.querySelector('input').checked = true;
  });
});
//................................................ movie sorting end..... .........................................


//................................................ movie cell category start .......................................
let cellContent = document.querySelectorAll('#movie-cell-category li a');
const movieList = document.getElementById('movie-list-show');
const movieCell = document.getElementById('movie-cell-show');

function detailsClick(event){
  event.preventDefault();
  const clickedElement = event.target.tagName === 'I' ? event.target.parentElement : event.target;
  const elementClass = clickedElement.dataset.elementClass;

  if(clickedElement.classList.contains('cell-active')){
    return;
  }
  for(let d of cellContent){
    d.classList.remove('cell-active');
  }
  clickedElement.classList.add('cell-active');

  localStorage.setItem('selectedElementClass',elementClass);

  movieList.style.display = elementClass === 'movieList' ? 'block' : 'none';
  movieCell.style.display = elementClass === 'movieCell' ? 'flex' : 'none';
}

function restorState(){
  const selectedElementClass = localStorage.getItem('selectedElementClass');
  if(selectedElementClass){
    cellContent.forEach(item => {
      if(item.dataset.elementClass === selectedElementClass){
        item.classList.add('cell-active');

        movieList.style.display = selectedElementClass === 'movieList' ? 'block' : 'none';
        movieCell.style.display = selectedElementClass === 'movieCell' ? 'flex' : 'none';
      }else{
        item.classList.remove('cell-active');
      }
    });
  }
}

cellContent.forEach(item => {
  item.addEventListener('click',detailsClick);
});

document.addEventListener('DOMContentLoaded',restorState);
//................................................ movie cell category end .......................................


// ..................................................... about us image start ....................................
var swiper = new Swiper(".mySwiper2", {
  spaceBetween: 30,
  slidesPerView: 2,
  grabCursor: true,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  breakpoints: {
    0: {
        slidesPerView: 1,
    },
    500: {
        slidesPerView: 2,
    },
    992: {
      slidesPerView: 2,
    },
    1300: {
        slidesPerView: 3,
    }
  }
});
// ..................................................... about us image end ....................................


// ..................................................... other photo swiper start ....................................
var swiper = new Swiper(".mySwiper5", {
  spaceBetween: 30,
  slidesPerView: 3,
  grabCursor: true,
  autoplay: {
    delay: 5000,
    disableOnInteraction: false,
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  breakpoints: {
    0: {
        slidesPerView: 1,
    },
    500: {
        slidesPerView: 2,
    },
    992: {
      slidesPerView: 3,
    },
    1300: {
        slidesPerView: 4,
    }
  }
});
// ..................................................... other photo swiper end ....................................


// ..................................................... movie cast swiper start ....................................
var swiper = new Swiper(".mySwiper6", {
  spaceBetween: 23,
  slidesPerView: 5,
  grabCursor: true,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  breakpoints: {
    0: {
        slidesPerView: 1,
    },
    300: {
        slidesPerView: 2,
    },
    800: {
      slidesPerView: 3,
    },
    992: {
      slidesPerView: 4,
    },
    1200: {
      slidesPerView : 5,
    },
    1300: {
        slidesPerView: 6,
    }
  }
});
// ..................................................... movie cast swiper end ....................................


// ..................................................... showtime select date start ....................................
document.addEventListener('DOMContentLoaded',function(){
    const selectDates = document.querySelectorAll('.js-select-date');
    const firstDateButton = document.getElementById('first-date-button');
    const scrollContainer = document.querySelector('#select-date > ul');

    const storedDate = localStorage.getItem('activeDate');
    let foundMatch = false;
    if(storedDate){
      selectDates.forEach(button => {
        const label = button.querySelector('span').textContent.trim();
          if( label === storedDate.trim()){
              button.classList.add('js-select-active');
              foundMatch = true;

              setTimeout(() => {
                  const storedScroll = localStorage.getItem('scrollPosition');
                  if(scrollContainer && storedScroll){
                      scrollContainer.scrollLeft = parseInt(storedScroll);
                  }
              }, 0);
          }
      });
    }

    if(!foundMatch){
      if(firstDateButton){
          firstDateButton.classList.add('js-select-active');
          const label = firstDateButton.querySelector('span').textContent.trim();
          localStorage.setItem('activeDate', label);

          if(scrollContainer){
              scrollContainer.scrollLeft = 0;
              localStorage.setItem('scrollPosition', '0');
          }
      }
    }

    selectDates.forEach(button => {
      button.addEventListener('click',function(){
          selectDates.forEach(btn => { btn.classList.remove('js-select-active') });
          button.classList.add('js-select-active');

          const label = button.querySelector('span').textContent.trim();
          localStorage.setItem('activeDate', label);

          if(scrollContainer){
            localStorage.setItem('scrollPosition',scrollContainer.scrollLeft);
          }
        });
    });

    if(scrollContainer){
      scrollContainer.addEventListener('scroll',function(){
          localStorage.setItem('scrollPosition', scrollContainer.scrollleft);
      });
    }
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


//.............................................. Select the single seat plan container start .................................
document.addEventListener('DOMContentLoaded',function(){
  const seatPlan = document.getElementById('seat-plan');
  const seatPlanRow = document.querySelectorAll('.seat-plan-row');
  const seats = document.querySelectorAll('.seat');
  const swipeLeft = document.getElementById('swipe-left');

  seatPlanRow.forEach(sr => {
    const totalSeats = sr.querySelectorAll('.seat').length;

    if (totalSeats > 20) {
      sr.style.display = 'grid';
      sr.style.gridTemplateColumns = `auto repeat(${totalSeats}, auto)`;
    }

    if (seatPlan.scrollWidth > seatPlan.clientWidth) {
      swipeLeft.style.display = 'block';
    } else {
      swipeLeft.style.display = 'none';
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
//.............................................. Select the single seat plan container end .................................


//....................................................... seat design swiper start ...........................................
var swiper = new Swiper(".mySwiper7", {
  spaceBetween: 30,
  slidesPerView: 2,
  grabCursor: true,
  navigation: {
        nextEl: ".button-next7",
        prevEl: ".button-prev7",
      },
        pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  breakpoints: {
    0: {
        slidesPerView: 1,
    },
    500: {
        slidesPerView: 2,
    },
    992: {
      slidesPerView: 3,
    },
    1300: {
        slidesPerView: 4,
    }
  }
});
//....................................................... seat design swiper end ...........................................


//...................................................... team members swiper start ..........................................
var swiper = new Swiper(".mySwiper8", {
  spaceBetween: 30,
  slidesPerView: 4,
  loop: true,
  grabCursor: true,
  autoplay: {
    delay: 6000,
    disableOnInteraction: false,
  },
  navigation: {
    nextEl: ".button-next8",
    prevEl: ".button-prev8",
  },
  breakpoints: {
    0: {
        slidesPerView: 1,
    },
    700: {
        slidesPerView: 2,
    },
    980: {
      slidesPerView: 3,
    },
    1200: {
      slidesPerView: 4,
    },
    1300: {
        slidesPerView: 4,
    }
  }
});
//....................................................... team members swiper end ...........................................


//.................................................... payment promotion code start .........................................
$(document).ready(function(){
  $('#promo-code > .d-flex').click(function(){
    $('#promo-form').slideToggle(500);
  });
});
//.................................................... payment promotion code end .........................................


//................................................... payment method start ..............................................
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

//................ upload screenshot start ..........
var loadFile = function (event) {
  var image = document.getElementById("output");
  image.src = URL.createObjectURL(event.target.files[0]);
};
//..................upload screenshot end................

//................................................... payment method end ..............................................


//............................ video modal mute start ..................................
document.addEventListener('DOMContentLoaded', function() {
  const modals = document.querySelectorAll('.modal');

  modals.forEach(function(modal) {
    const video = modal.querySelector('iframe');

    modal.addEventListener('hidden.bs.modal', function () {
        video.src = video.src;
    });
  });
});
//............................ video modal mute end ..................................
