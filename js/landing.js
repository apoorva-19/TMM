$(document).ready(function(){
    $('.main').slick({
    arrows:true,
    dots: true,
    autoplay: true, 
    infinite: true,
    speed: 300,
    });
}
);

$(document).ready(function(){
    $('.responsive').slick({
    arrows:true,
    dots: true,
    autoplay: true, 
    infinite: true,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    responsive: [
        {
        breakpoint: 1024,
        settings: {
            slidesToShow: 3,
            infinite: true,
            dots: true
        }
        },
        {
        breakpoint: 600,
        settings: {
            slidesToShow: 2,
        }
        },
        {
        breakpoint: 480,
        settings: {
            slidesToShow: 1,
        }
        }
        
    ]
    });

}
);