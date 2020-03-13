  
<?php
//code to add style
function my_theme_enqueue_styles() {

    $parent_style = 'porto-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );


function woocommerce_ajax_add_to_cart_js() {
    if (function_exists('is_product') && is_product()) {
        //wp_enqueue_script('woocommerce-ajax-add-to-cart', plugin_dir_url(__FILE__) . 'assets/ajax-add-to-cart.js', array('jquery'), '', true);
        // wp_enqueue_script('woocommerce-ajax-add-to-cart', get_template_directory_uri() . '/../porto-child/ajax-add-to-cart.js?ver=1.0', array('jquery'), '', true);
        wp_enqueue_script('woocommerce-ajax-add-to-cart', get_stylesheet_directory_uri() . '/ajax-add-to-cart.js?ver=1.0', array('jquery'), '', true);
        wp_localize_script( 'woocommerce-ajax-add-to-cart', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    }
}
add_action('wp_enqueue_scripts', 'woocommerce_ajax_add_to_cart_js', 99);


add_action( 'wp_ajax_get_variation_id_from_attributes', 'get_variation_id_from_attributes' );
add_action( 'wp_ajax_nopriv_get_variation_id_from_attributes', 'get_variation_id_from_attributes' );
function get_variation_id_from_attributes() {
    $colour = $_POST['colour'];
    $size = $_POST['size'];
    $product_id = intval($_POST['prod_id']);
    //echo $colour.'-'.$size.'-'.$product_id.'-';

    $variation_id = find_matching_product_variation_id ( $product_id,array(
        'attribute_colour' => $colour,
        'attribute_size' => $size
    ));

    echo $variation_id;
    wp_die();
}
function find_matching_product_variation_id($product_id, $attributes)
{
    //return $product_id;
    //return print_r($attributes);
    return (new \WC_Product_Data_Store_CPT())->find_matching_product_variation(
        new \WC_Product($product_id),
        $attributes
    );
}

/* My Implementation */
add_action( 'wp_ajax_get_variation_id_from_attributes1', 'get_variation_id_from_attributes1' );
add_action( 'wp_ajax_nopriv_get_variation_id_from_attributes1', 'get_variation_id_from_attributes1' );

function get_variation_id_from_attributes1() {

    $product_data = $_POST['productdata'];
    //print_r($product_data); die;
    $notvalid_variation = array();
    
    foreach ($product_data as $value) {
        $variation_id = find_matching_product_variation_id ( $value['prod_id'],array(
            'attribute_pa_qty' => $value['qty']
            //'attribute_size' => $value['size'],
        ));
        //echo $variation_id; die;
        
        if(!empty($variation_id) && $variation_id != 0) {
            WC()->cart->add_to_cart( $value['prod_id'], $value['quan'], $variation_id );
            wc_add_to_cart_message($variation_id);
        } else {
            $notvalid_variation[] = $value['size'];
        }
        //if( !empty($notvalid_variation) ) {
            //echo 1223; 
            //wc_add_to_cart_message("Error Message");
            //die;
        //}
        
    }

    echo json_encode($notvalid_variation);
    wp_die();
}