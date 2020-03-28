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
        //add_action( 'admin_post_GetImages', array( $this, 'GetImages', 10,3 ));
        

    }

    

    function custom_action()
{
    global $wpdb;
    //Get image info
    $query = 'SELECT * FROM wp_postmeta WHERE meta_key = "external_image_url"';
    $query2 = 'SELECT post_id FROM wp_postmeta WHERE meta_key = "external_image_url";';
    $she_ids_result = $wpdb->get_results($query2);
    $she_ids_array = $she_ids_result;
    foreach( $wpdb->get_results($query) as $key => $urls) {
        $she_ids = $urls->post_id;
        $she_urls = $urls->meta_value;
        $url_array = explode("|", $she_urls);
        $desc = "Placeholder";
        ?><br></br> <?php
        //echo "Post ID: " . $she_ids . " ";
        //$this->GetImages($url_array, $she_ids, $desc);
       // foreach($url_array as $url_clean){ 
            //echo "URLs: " . $url_clean . " ";
        //}
        ?><br></br> <?php
        
    }
    $this->AssignImages($she_ids_result);
    //echo $response->meta_value;
    //$urls = $response->meta_value;
    //$cleanup = explode("|", $urls);
    //echo $urls;
    //echo $cleanup[0];
    //echo $cleanup[1];
    
    wp_die();

    echo "Output: " . $_POST['send_message'];
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
        <div id="print_out"></div>


<?php
    }


    //Importing Jewelry images
    public function GetImages($urls, $post_id, $desc)
    {

        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        $counter = 0;    

        foreach ($urls as $url) {
            $tmp = download_url($url);
            $counter++;
            if (is_wp_error($tmp)) {
                // download failed, handle error
            }

            $post_id = $post_id;
            $desc = $post_id . "-" . $counter;
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

            //$src = wp_get_attachment_url($id);

        }
    }

    public function AssignImages($ids){
        global $wpdb;
        foreach($ids as $id){
            $she_post_id = $id->post_id;
            $query = 'SELECT ID FROM shewolfb_wp.wp_posts WHERE post_type = "attachment" AND post_parent = ' . $she_post_id . ";";
            $test = $wpdb->get_results($query);
            ?><br></br><?php
            echo "Post ID: " . $she_post_id;
            ?><br></br><?php
            var_dump($test);
            /* foreach($test as $image_id){
                $test = $image_id->ID;
                $test_array = array_fill($test);
                ?><br></br><?php
            }
            var_dump($test_array); */

    }

}

}


new SheWolf_Bling_Images_Plugin();

?>