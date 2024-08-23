(function ($) {
  "use strict";

  $(document).ready(function () {
    /*
     * remove Class which is make layout small in theme.
     */
    $(".wp-block-post-content").removeClass("is-layout-constrained");

    /*
     * Add WP Carousel slider Code
     */

    $(".related-posts-container.owl-carousel").owlCarousel({
      loop: true,
      autoplay: true,
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
