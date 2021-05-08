$(document).on('ready', function() {
  $(".regular").slick({
    dots: false,
    infinite: true,
    slidesToShow: 2,
    slidesToScroll: 1,
    arrows: true,
    autoplay: true,
    responsive: [
        {
          breakpoint: 1199,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
       
      ]
	})

  $(".main_slider").slick({
    dots: false,
    infinite: true,
    arrows: false,
    autoplay: true,
    autoplaySpeed: 5500,
    speed: 200,
    pauseOnFocus: false,
	pauseOnHover: false,
    pauseOnDotsHover: false,
    slidesToShow: 1,
    slidesToScroll: 1,
    fade: true,
    cssEase: 'linear'
	})
  $(".portfolio_slide").slick({
    dots: false,
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: true,
    autoplay: true,
  })

});
	
$('.menu_icon').on('click', function(){
    $('body').addClass('open');
});
$('.close_icon').on('click', function(){
    $('body').removeClass('open');
});
$('.navbar>ul>li').on('click', function(){
    $(this).find(".sub-menu").toggle('medium');
});

