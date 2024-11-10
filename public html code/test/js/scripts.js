// Initialize AOS
AOS.init({
  duration: 1200,
  once: true
});

// Toggle Dark Mode
function toggleDarkMode() {
  document.body.classList.toggle('dark-mode');
}

// Initialize Flickity Carousel
document.addEventListener('DOMContentLoaded', () => {
  var elem = document.querySelector('.carousel');
  new Flickity(elem, {
    cellAlign: 'left',
    contain: true,
    autoPlay: 3000,
    wrapAround: true
  });
});
