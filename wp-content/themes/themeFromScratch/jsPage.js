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

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    $('.add-to-cart').click(function () {
        var product_id = $(this).attr('data-value');
        var price = $('#prod_' + product_id).text();
        var quantity =  $('#txtNumber' + product_id).val();
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : ajax_params.ajax_url,
            data : {action: "add_to_cart", product_id : product_id, price: price,quantity: quantity },
            success : function( response ) {
                console.log(response);
                $(".header_cart").html(response.qty_cart);
                $('.add_cart_'+response.product.p_id).html('<span style="color:#FE980F">' + "Added to the cart!" + '</span>');
            }
        })
    });

    $('.addto-wishlist').click(function () {
        var product_id = $(this).attr('data-value');
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : ajax_params.ajax_url,
            data : {action: "add_to_wishlist", product_id : product_id}
        })
    });


    $('.remove_wishlist').click(function () {
        var product_id = $(this).attr('data-value');

        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : ajax_params.ajax_url,
            data : {action: "remove_from_wishlist", product_id : product_id}

        })

    });


    $('.cart_qty_up').click(function () {
        var product_id = $(this).attr('data-value');
        var price = $(this).attr('data-value1');
        var quantity = 1;

        jQuery.ajax({
            type : "post",
            datatype :"json",
            url: ajax_params.ajax_url,
            data : { action : "cart_qty_increase",
                product_id : product_id,
                quantity: quantity,
                price: price
            },
            success: function (response) {
                var x = JSON.parse(response);
                var prodt_price = (x.product.p_price)*x.product.p_qty;

                $(".cart_qty_"+product_id).html(x.product.p_qty);
                $(".per_product_price"+product_id).html(prodt_price);
                $("#final_amount").html(x.total);
            }
        })
    });

    $('.remove_from_cart').click(function () {
        var product_id = $(this).attr('data-value');
        jQuery.ajax({
            type : "post",
            dataType : "json",
            url : ajax_params.ajax_url,
            data : {action: "delete_from_cart", product_id : product_id },
            success : function( response ) {
                //console.log(response.qty);



                $(".cart_list_"+product_id).fadeOut();
                $("#final_amount").html(response.total);

                $(".header_cart").html(response.cart);

                if(response.total == 0){
                $("#checkout").fadeOut();
                }
            }
        })
    });

    $('.cart_qty_down').click(function () {

        var product_id = $(this).attr('data-value');
        jQuery.ajax({
            type: "post",
            datatype : "json",
            url: ajax_params.ajax_url,
            data : { action: "cart_qty_decrease", product_id : product_id},
            success:function (response) {
                var x = JSON.parse(response);
                console.log(x.total);
                var p_price = x.sub_total.p_price*x.sub_total.p_qty;
                $("#per_product_price"+product_id).html(p_price);
                $(".cart_qty_"+product_id).html(x.sub_total.p_qty);
                $("#final_amount").html(x.total);
            }
        })
    });

    $('#checkout').click(function () {
        window.location='./checkout/'
    });

    $('#login').click(function () {
        window.location='./login/'
    });

    $('#confirmation').click(function () {
        window.location='./confirmation/'
    });



    $('#loginFormoId').submit(function() {
        var username = $( ".custom_username" ).val();
        var password = $( ".custom_password" ).val();
        //alert(ajax_params.ajax_url);
        jQuery.ajax({
            type: "POST",
            datatype : "json",
            url: ajax_params.ajax_url,
            data : { action: "userlogin", username : username, password : password},
            success: function(response) {
               if(response = "success"){
                   window.location='./checkout/'
               }else{
                   window.location='./login/'
               }
            }
        })
        return false;
    });


});



