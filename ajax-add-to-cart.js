
jQuery('#buyp').click(function(e) {
    //alert();
    e.preventDefault();
    //jQuery('');
    //get_variation_id_from_attributes();
    //alert(jQuery('input[name="product_id"]').val());
    var test_arr=jQuery('input[type="number"][name="qty"]');
    var array_size = [];

    if(jQuery('#colour').val() == '') {
        alert('Please select the color');
        return false;
    }

    jQuery(".entry-summary").prepend('<div id="preloader">Loading...</div>');
    jQuery.each(test_arr, function(i, item) {  //i=index, item=element in array
        
        // alert(jQuery(item).val());
        //alert(jQuery(item).data("attr"));
        // alert(jQuery('#colour').val());
        //alert(array_size);

        /* Sahil Work Start
        if(jQuery('#colour').val() == ''){
            alert('Please select the color');
            return false;
        }
        Sahil Work End */
        
        if(Number(jQuery(item).val()) > 0) {
            
            var data1 = {
                //colour: jQuery('#colour').val(),
                qty: jQuery(item).data("attr"),
                prod_id: jQuery('input[name="product_id"]').val(),
                quan: jQuery(item).val()
            };

            array_size.push(data1);

            console.log("ArrayCheck: " + JSON.stringify(array_size));
        }

        /* Sahil Work Start
        var data = {
            action: 'get_variation_id_from_attributes',
            colour: jQuery('#colour').val(),
            size: jQuery(item).data("attr"),
            prod_id: jQuery('input[name="product_id"]').val()
        };

        var var_id;
        //debugger
        if(Number(jQuery(item).val())>0) {
            
            jQuery.ajax({
                url : myAjax.ajaxurl,
                type : "post",
                data: data,
                async : false,
                success : function(response) {
                    var_id = response;
                    addToCart(var_id, jQuery(item).val());
                }
            });
        } 
        Sahil Work End */

    });

    /* Sahil Work Start
    setTimeout(function(){location.reload()},1500);
    return false;  
    Sahil Work End */

    //if(Number(jQuery(item).val())>0) {
            
            jQuery.ajax({
                url : myAjax.ajaxurl,
                type : "post",
                data: {
                    'action': 'get_variation_id_from_attributes1',
                    'productdata': array_size
                },
                async : false,
                success : function(response) {
                    if(response.length) {
                        //alert("Sizes are not available: " + response);
                        //alert(response.length);
                        //jQuery("#preloader").remove();
                    }
                    location.reload();
                    //window.location = myAjax.carturl;
                    return false;  
                }
            });
    //}
    

});

async function addToCart(p_id,qty) {
    jQuery.get('/?add-to-cart=' + p_id +'&quantity='+ qty, function() {
        // call back
    });
}
function qtyUpdate(toBox,op) {
    // alert(toBox);
    // alert(op);
    // alert(jQuery('#'+toBox).val());
    var val=parseInt(jQuery('#'+toBox).val());
    if(op=='+') {
        val += 1;
    }
    else {
        if(val>0) {
            val -= 1;
        }
    }
    jQuery('#'+toBox).val(val);
}
// function get_variation_id_from_attributes( $product_id, $colour, $size ) {
//     $colour = strtolower($colour);
//     $size = strtolower($size);
//
//     $variation_id = find_matching_product_variation_id ( $product_id,array(
//         'attribute_pa_colour' => $colour,
//         'attribute_pa_size' => $size
// ));
//
//     return $variation_id;
// }