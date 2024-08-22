(function ($) {
  "use strict";

  $(".wp-block-post-content").removeClass("is-layout-constrained");

  $(document).ready(function () {
    $(".related-posts-container.owl-carousel").owlCarousel({
      loop: true,
      //   autoplay: true,
      margin: 5,
      responsiveClass: true,
      nav: false,
      dots: false,
      responsive: {
        0: {
          items: 1,
        },
        600: {
          items: 3,
        },
        1000: {
          items: 5,
        },
      },
    });
  });
})(jQuery);
