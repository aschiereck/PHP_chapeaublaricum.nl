$(function(){
    var $slider = $('#slider').flickity({
        pageDots: true,
        prevNextButtons: false,
        draggable: false,
        autoPlay: 8000, // 8 seconds between each slide
        selectedAttraction: 0.2,
        friction: 0.8,
        imagesLoaded: true
    });
});