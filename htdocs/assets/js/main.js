/*!
 * Main JS
 */
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

    if ($('#sommaire').length > 0) {
        createSommaire();
    }

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
    
    $('.articlebox-text table').addClass('table table-striped');

    // init Masonry
    var $grid = $('.grid').masonry({
        // options...
    });
    // layout Masonry after each image loads
    $grid.imagesLoaded().progress(function () {
        $grid.masonry('layout');
    });
});

function createSommaire() {

    var arraySommaire = new Array();
    var j = 0;
    var listeLi = "";

    $(".content h1, .content h2, .content h3").each(function (i) {
        var current = $(this);
        var part = 'part' + i;

        if ($.inArray($(this).html(), arraySommaire) >= 0) {

        } else {
            $(this).nextUntil(".content h1, .content h2, .content h3")
                    .andSelf()
                    .wrapAll('<section id="' + part + '">');

            arraySommaire[j] = $(this).html();

            listeLi += "<li><a id='" + current.html()
                    + "' href='#" + part + "' title='" + current.html() + "'>"
                    + current.html() + "</a></li>"

            j++;
        }
    });
    $("#sommaire ul.nav").prepend(listeLi);
   
    var offset = 160;
    
    $('body').scrollspy({target: '#sommaire', offset: (offset+20)});
    
    $('#sommaire ul.nav a').click(function(event) {
        event.preventDefault();
        $($(this).attr('href'))[0].scrollIntoView();
        scrollBy(0, -offset);
    });
   
}

