//Slider Control
jQuery(document).ready(function(){
    $(".carousel-inner .item:first").addClass("active");
    $(".carousel-indicators li:first").addClass("active");
    $('.carousel').carousel({
      interval: 7000
    });
});