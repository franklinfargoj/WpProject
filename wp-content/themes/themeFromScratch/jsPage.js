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
            1024 : { items : 3   // from 768 screen width to 1024 8 items
            }
        },
        itemsDesktop : false,
        itemsDesktopSmall : false,
        itemsTablet: false,
        itemsMobile : false
    });

    $('.add-to-cart').click(function () {
        var product_id = $(this).attr('data-value');
        var price = $('#prod_' + product_id).text();
        var quantity =  $('#txtNumber' + product_id).val();;
        display_cart_prod(product_id, price, quantity);
    });

    function display_cart_prod(product_id, price, quantity) {
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : cc_ajax_object.ajax_url,
            data : {action: "abc", product_id : product_id, price: price,quantity: quantity }
        })
    }

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    $('.remove_from_cart').click(function () {
        var product_id = $(this).attr('data-value');
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : cc_ajax_object.ajax_url,
            data : {action: "delete_from_cart", product_id : product_id },
            success : function( response ) {
                location.reload();
            }
        })
    });

    $('.cart_qty_up').click(function () {
        var product_id = $(this).attr('data-value');
        var price = $(this).attr('data-value1');
        var quantity = 1;
        jQuery.ajax({
            type : "post",
            datatype :"json",
            url: cc_ajax_object.ajax_url,
            data : { action : "cart_qty_increase",
                     product_id : product_id,
                     quantity: quantity,
                     price: price
                    },
            success: function (response) {
                location.reload();
            }
        })

    });

    $('.cart_qty_down').click(function () {
        var product_id = $(this).attr('data-value');
        jQuery.ajax({
            type: "post",
            datatype : "json",
            url: cc_ajax_object.ajax_url,
            data : { action: "cart_qty_decrease", product_id : product_id},
            success:function (response) {
                location.reload();
            }
        })
    });

});



