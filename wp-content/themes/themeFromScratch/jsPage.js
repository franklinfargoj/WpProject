jQuery(document).ready(function() {

    $("#owl-demo").owlCarousel({
        navigation : true, // Show next and prev buttons
        autoPlay: 3000,
        slideSpeed : 50,
        paginationSpeed : 50,
        items : 1,
        responsive : {
            480 : { items : 1  }, // from zero to 480 screen width 4 items
            768 : { items : 2  }, // from 480 screen widthto 768 6 items
            1024 : { items : 3 }  // from 768 screen width to 1024 8 items

        },
        itemsDesktop : false,
        itemsDesktopSmall : false,
        itemsTablet: false,
        itemsMobile : false
    });

    //redirects on click of Proceed to checkout
    $('#checkout').click(function () {
        window.location='./checkout/'
    });

    $('#login').click(function () {
        window.location='./login/'
    });

    //redirects on click of Continue ON Checkout page
    $('#confirmation').click(function () {
        window.location='./confirmation/'
    });

});



