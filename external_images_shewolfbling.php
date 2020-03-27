<?php
/*
    Plugin Name: SheWolf Bling External Images
    description: This is a test
    Author: Justin Tharpe
    Version: 1.1.0
*/




class SheWolf_Bling_Images_Plugin{
    public function __construct()
    {
        // Hook into the admin menu
        add_action('admin_menu', array($this, 'create_plugin_settings_page'));
        //test
        add_action( 'wp_ajax_custom_action', array( $this, 'custom_action' ));

    }

    






    function custom_action()
{
    global $wpdb;
    //Get image info
    $query = 'SELECT * FROM wp_postmeta WHERE post_id = 10551 AND meta_key = "external_image_url"';
    $response = $wpdb->get_row($query);
    //echo $response->meta_value;
    $urls = $response->meta_value;
    $cleanup = explode('|', $urls);
    //echo $urls;
    echo $urls[0];
    wp_die();


    //return $results->meta_value;
    //$this->GetImages()
}

    public function create_plugin_settings_page()
    {
        // Add the menu item and page
        $page_title = 'SheWolf External Images Settings';
        $menu_title = 'SheWolf Images';
        $capability = 'manage_options';
        $slug = 'shewolf_images';
        $callback = array($this, 'plugin_settings_page_content');
        $icon = 'dashicons-admin-plugins';
        $position = 100;

        add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
        }

        public function plugin_settings_page_content(){
        global $content;
        global $wpdb;
        echo "<script src='" . plugins_url('/assets/js/main.js', __FILE__) . "'></script>";
        ?>
	    <button id="send_button" type="button">Process Images</button><div id="send_message"></div>


<?php
    }


    //Importing Jewelry images
    public function GetImages($urls, $post_id, $desc)
    {

        if (!function_exists('media_handle_upload')) {
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        }

        foreach ($urls as $url) {
            $tmp = download_url($url);
            if (is_wp_error($tmp)) {
                // download failed, handle error
            }

            //$post_id = 10508; // set 0 for no parent post id or simple attachment otherwise pass post id for include in post
            //$desc = "The WordPress Logo";
            $file_array = array();

// Set variables for storage
// fix file filename for query strings
            preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches);
            $file_array['name'] = basename($matches[0]);
            $file_array['tmp_name'] = $tmp;

// If error storing temporarily, unlink
            if (is_wp_error($tmp)) {
                @unlink($file_array['tmp_name']);
                $file_array['tmp_name'] = '';
            }


// do the validation and storage stuff
            $id = media_handle_sideload($file_array, $post_id, $desc);

// If error storing permanently, unlink
            if (is_wp_error($id)) {
                @unlink($file_array['tmp_name']);
                return $id;
            }

            // Attach the image to the product
            //set_post_thumbnail($post_id, $id);

            $src = wp_get_attachment_url($id);


            echo "Image ID's:" . $id;
        }
    }


}

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



new SheWolf_Bling_Images_Plugin();

?>