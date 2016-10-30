$(document).ready(function () {

    $(".lightbox").attr('rel', 'gallery').fancybox();
    $(".lightbox > img").addClass("img-responsive");

    SyntaxHighlighter.config.tagName = "textarea";
    SyntaxHighlighter.all();

    jQuery("#menuzord").menuzord({
        indicatorFirstLevel: "<i class='fa fa-angle-down'></i>",
        indicatorSecondLevel: "<i class='fa fa-angle-right'></i>",
        align: "right"
    });

    $(function () {
        var header = $("#nav-wrap"),
                yOffset = 0,
                triggerPoint = 150;
        $(window).scroll(function () {
            yOffset = $(window).scrollTop();

            if (yOffset >= triggerPoint) {
                header.addClass("navbar-fixed-top animated fadeInDown");
            } else {
                header.removeClass("navbar-fixed-top animated fadeInDown");
            }

        });
    });

    $('#relatedbox').owlCarousel({
        center: false,
        items: 12,
        loop: true,
        margin: 30,
        nav: true,
        dots: false,
        slideBy: 4,
        navText: ['&#xf104;', '&#xf105'],
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items: 3,
            },
            1000: {
                items: 4,
            },
            1600: {
                items: 4
            }
        }
    });

    $('.icon').click(function () {
        $('.search-input').toggleClass('expanded');
    });

    // init Masonry
    var $grid = $('.grid').masonry({
        // options...
    });
    // layout Masonry after each image loads
    $grid.imagesLoaded().progress(function () {
        $grid.masonry('layout');
    });
});

