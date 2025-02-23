let menu = document.querySelector('.nav-bar');
let menuButton = document.querySelector('#menu-but');
let menubar = document.querySelector('.header');
menuButton.addEventListener('click', function() {
    menu.classList.toggle('active');
    menuButton.classList.toggle('fa-times');
});

window.onscroll = () =>{
    menu.classList.remove('active');
    menuButton.classList.remove('fa-times');
}

var swiper = new Swiper(".home-slider", {
    loop:true,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });