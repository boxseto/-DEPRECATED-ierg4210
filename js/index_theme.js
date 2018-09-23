/*******************    carousel    ***********************/
$(function(){
    $('.carousel').carousel({
      interval: 2000
    });
});

/*******************    expanded cart    ***********************/
$('.expand_menu').css("display", "none");

$( "#cart" ).hover(
  function(){
    $(".expand_menu").css("display", "block");
  }
);
$( ".expand_menu" ).mouseleave(
  function(){
    $(".expand_menu").css("display", "none");
  }
);

$( function() {
  var spinner = $( ".qty" ).spinner();
});


/*******************    best buy    ***********************/
var owl = $('.owl-carousel');
owl.owlCarousel({
    loop:true,
    nav:false,
    dots:false,
    stagePadding: 5,
    margin:10,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },            
        960:{
            items:5
        },
        1200:{
            items:6
        }
    }
});
owl.on('mousewheel', '.owl-stage', function (e) {
    if (e.deltaY>0) {
        owl.trigger('prev.owl');
    } else {
        owl.trigger('next.owl');
    }
    e.preventDefault();
});