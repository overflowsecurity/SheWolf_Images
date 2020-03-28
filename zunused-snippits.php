//echo 'Hello World!';
        //$query = "SELECT * FROM wp_postmeta WHERE meta_key = 'external_image_url';";
        //echo $query;
        //$results = $wpdb->get_row($query);
        //echo $results->post_id;

        //$test = $this->ImageFeeder();
        //echo $test;
            //echo "<script src='" . plugins_url('/assets/js/main.js', __FILE__) . "'></script>";


            function bling_add_scripts()
{
    wp_enqueue_script('ajax-script', plugins_url('/assets/js/main.js', __FILE__), array('jquery'));
}


wp_enqueue_script('ajax-script', plugins_url('/assets/js/main.js', __FILE__), array('jquery'));




class page_writer {

public static function replace_text($text) {
$text = str_replace('Avenue', 'boobs', $text);
return $text;
}

public static function replace_images($image, $attachment_id, $size  = 'woocommerce_thumbnail', $icon) {
    global $wpdb;
    global $post;
    if (is_admin()) {
        //don't override the gallery on the admin pages, otherwise we end up saving the images to the database
        return $image;
    } elseif( is_object( $post ) && 10508 == $post->ID ){
        $query = "SELECT * FROM wp_postmeta WHERE post_id = $post->ID AND meta_key = \"external_image_url\";";
        $results = $wpdb->get_row($query);

        $image[0] = $results->meta_value;

        return $image;
    } else {
        return $image;
    }
}

}

//add_filter('woocommerce_cart_item_name', array( 'page_writer', 'replace_text' ) );
//add_filter('wp_get_attachment_image_src', array( 'page_writer', 'replace_images'), 10,4 );
