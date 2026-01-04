document.addEventListener("DOMContentLoaded", function(){
  let ticking = false;
  window.addEventListener('scroll', function() {
    if (!ticking) {
      window.requestAnimationFrame(function() {
        if (window.scrollY > 450) {
          $('.sticky').addClass('fixed-top');
        } else {
          $('.sticky').removeClass('fixed-top');
          document.body.style.paddingTop = '0';
        }
        ticking = false;
      });
      ticking = true;
    }
  }, { passive: true });
});