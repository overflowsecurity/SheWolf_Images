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