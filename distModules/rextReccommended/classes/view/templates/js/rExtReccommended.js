
$(document).ready( function() {
  new geozzy.rextReccommended.reccommendedView();

  $('.owl-carousel').owlCarousel({
    margin:10,
    nav:true,
    responsive:{
      0:{
          items:1
      },
      767:{
          items:3
      },
      1200:{
          items:4
      }
    }
  });

  $('.owl-carousel .owl-nav .owl-prev').html('<i class="fa fa-angle-left"></i>');
  $('.owl-carousel .owl-nav .owl-next').html('<i class="fa fa-angle-right"></i>');

  initEffectNavs();
});

function initEffectNavs(){
  $('a.page-scroll').bind('click', function scrollSuave(event) {
    var hrefText = $(this).attr('href');
    if( hrefText.indexOf( '#' ) === 0 ) {
      var $anchor = $( hrefText );
      if( $anchor.length > 0) {
        $('html, body').stop().animate({
            scrollTop: $anchor.offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
      }
    }
  });
}