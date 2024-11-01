<?php

// ==================================================================
// Function to create the data and output it on shortcode
// ==================================================================

function yy_output_related_posts_shortcode($attr = "", $content, $page_id = "") {

    include(__DIR__ . '/../include/settings.php');

    // ====================================================
    // getting the id for the related page
    // ====================================================
    $output_data_to_page = "";

    $related_post_page_id = get_the_ID();

    if( !empty($attr['page_id']) || !empty($related_post_page_id) ) {

        // incase the user set page id on the shortcode
        if( !empty($attr['page_id']) ) {
            
            $page_id = $attr['page_id'];

            // making sure this is propare id number and clean up data
            // the global boxes come with 'g' before the number
            if( strstr($attr['page_id'], 'g') ) {
                $the_id_num = str_replace('g', '', $attr['page_id']);
                $page_id = 'g' . intval($the_id_num);
            } // if( strstr($attr['page_id'], 'g') ) {

        } else { // if( !empty($attr['page_id']) ) {
            $page_id = $related_post_page_id;
        } // } else { // if( !empty($attr['page_id']) ) {

    } else { // if( !empty($attr['page_id']) || !empty($related_post_page_id) ) {

        // this will output an error if the database data collection won't work
        $output_data_to_page .= "<div class='related-post-warp-error'>There was an error loading the related posts to the page</div>";

    } // } else { // if( !empty($attr['page_id']) || !empty($related_post_page_id) ) {

    // ====================================================
    // Getting the data from the database
    // ====================================================
    if( !empty($page_id) ) {

        global $wpdb; // getting access to wordpress database
        $publish_posts_count = 0;

        // getting related post from the database that set with the same post parent id
        $related_post_data = $wpdb->get_results("SELECT * FROM " . $yy_related_secondary_table_name . " WHERE post_parent_id = '{$page_id}' ORDER BY position ASC, id DESC");
        $related_post_data_count = $wpdb->get_results("SELECT id FROM " . $yy_related_secondary_table_name . " WHERE post_parent_id = '{$page_id}' AND hide_box = 0 ORDER BY position ASC, id DESC");

        // making sure there are results on the database for this post
        if( !empty($related_post_data) ) {

            $output_data_to_page .= "\n";
            $output_data_to_page .= "<div class='related-post-warp related-post-warp-" . $page_id . "'>";
            $output_data_to_page .= "\n";

                $position_number = 1; // define the position for the elements
                $related_posts_amount = count($related_post_data);

                foreach( $related_post_data as $this_related_post_data ) {

                    // ================================================
                    // Getting the post data from the database
                    // ================================================

                    $related_post_title = wp_kses_post( stripslashes_deep($this_related_post_data->title) ); // getting the post title
                    $related_post_description = wp_kses_post( stripslashes_deep($this_related_post_data->description) ); // getting the post excerpt (short description)
                    $related_post_thumbnail = esc_url($this_related_post_data->image_url); // getting the thumbnail image url
                    $related_post_thumbnail_alt = stripslashes_deep( sanitize_text_field($this_related_post_data->image_alt) ); // setting the image alt as the post title
                    $related_post_position = intval($this_related_post_data->position); // getting the postition of the post on the page
                    $db_related_post_id = intval($this_related_post_data->id); // the post id on the database
                    $hide_related_post = intval($this_related_post_data->hide_box); // the post id on the database

                    $related_post_id = intval($this_related_post_data->original_post_id);
                    $related_post_url = get_permalink($related_post_id); // getting the url to the related post

                    // ================================================
                    // incase the $hide_related_post = 2 which mean we will
                    // only output the post only if it's published
                    // ================================================

                    if( $hide_related_post == 2 ) {
                        if( get_post_status($related_post_id) != 'publish' ) {
                            $hide_related_post = 1;
                        } // if( get_post_status($related_post_id) != 'publish' ) {
                    } // if( $hide_related_post == 2 ) {

                    // ================================================
                    // if we output the post to the page
                    // ================================================

                    if($hide_related_post != 1) {

                        // ================================================
                        // Creating the ouptut data from the database
                        // ================================================

                        $add_class_info = $db_related_post_id;
                        if( ($related_posts_amount <= 2) || ($related_posts_amount == 4) ) {
                            $add_class_info = $db_related_post_id . " related-post-block-bigger";
                        } // if( ($related_posts_amount <= 2) || ($related_posts_amount == 4) ) {

                        $output_data_to_page .= "<div class='related-post-block related-post-block-" . $add_class_info . "'>";

                            if( !empty($related_post_thumbnail) && !strstr($related_post_thumbnail, 'add-image.png') ) {
                                $output_data_to_page .= "<div class='related-posts-img'>";

                                    // ------------------------------------------------------
                                    // count the post if we showing it even when the post was not published
                                    // ------------------------------------------------------

                                    if( get_post_status($related_post_id) == 0 ) {
                                        $publish_posts_count++;
                                    } // if( get_post_status($related_post_id) == 0 ) {

                                    // ------------------------------------------------------
                                    // checking to see if the post is published or pravite
                                    // ------------------------------------------------------

                                    if( get_post_status($related_post_id) === "publish" ) {
                                        
                                        // making sure we want to output the image to the page (global settings)
                                        if( $yy_related_data_array['display_thumbnail_on_page'] != 0 ) {

                                            $output_data_to_page .= "<div class='related-post-image'>";
                                                $output_data_to_page .= "<a href='" . $related_post_url . "'>";
                                                    $output_data_to_page .= "<img src='" . $related_post_thumbnail . "' alt='" . yydev_related_html_value($related_post_thumbnail_alt) . "' />";
                                                $output_data_to_page .= "</a>";
                                            $output_data_to_page .= "</div><!--related-post-image-->";       

                                        } // if( $yy_related_data_array['display_thumbnail_on_page'] != 0 ) {

                                        $publish_posts_count++;

                                    } else { // if( get_post_status($related_post_id) != "publish" ) {

                                        // making sure we want to output the image to the page (global settings)
                                        if( $yy_related_data_array['display_thumbnail_on_page'] != 0 ) {

                                            $output_data_to_page .= "<div class='related-post-image'>";
                                                $output_data_to_page .= "<img src='" . $related_post_thumbnail . "' alt='" . yydev_related_html_value($related_post_thumbnail_alt) . "' />";
                                            $output_data_to_page .= "</div><!--related-post-image-->"; 

                                        } // if( $yy_related_data_array['display_thumbnail_on_page'] != 0 ) {

                                    } // } else { // if( get_post_status($related_post_id) != "publish" ) {

                                $output_data_to_page .= "</div><!--related-posts-img-->";
                            } // if( !empty($related_post_thumbnail) && strstr($related_post_thumbnail, 'add-image.png') ) {


                            // ------------------------------------------------------
                            // checking to see if the post is published or pravite
                            // ------------------------------------------------------

                            if( get_post_status($related_post_id) === "publish" ) {

                                $output_data_to_page .= "<div class='related-post-title'>";
                                    $output_data_to_page .= "<a href='" . $related_post_url . "'>" . $related_post_title . "</a>";
                                $output_data_to_page .= "</div><!--related-post-title-->";

                            } else { // if( get_post_status($related_post_id) != "publish" ) {

                                $output_data_to_page .= "<div class='related-post-title no-active-link'>";
                                    $output_data_to_page .= $related_post_title;
                                $output_data_to_page .= "</div><!--related-post-title no-active-link-->";

                            } // } else { // if( get_post_status($related_post_id) != "publish" ) {
                            
                            // making sure we want to output the description to the page (global settings)
                            if( $yy_related_data_array['display_description_on_page'] != 0 ) {

                                if(!empty($related_post_description)) {
                                    $output_data_to_page .= "<p class='related-post-description'>" . nl2br($related_post_description);
                                    // $output_data_to_page .= " <a href='" . $related_post_url . "'>Read More...</a>";
                                    $output_data_to_page .= "</p>";
                                } // if(!empty($related_post_description)) {

                            } // if( $yy_related_data_array['display_description_on_page'] != 0 ) {

                            // ------------------------------------------------------
                            // adding fast edit button on yydevelopment theme
                            // ------------------------------------------------------
                            
    						if( is_user_logged_in() ) {

                                if( function_exists('yydev_theme_languages') ) {

                                    global $yydev_theme_data;
                                    if( isset($yydev_theme_data) && !empty($yydev_theme_data) && $yydev_theme_data['yy_fast_editing_button'] == 1 ) {

                        				$output_data_to_page .= "<div class='yy-edit-buttons'>";

                        					$output_data_to_page .= "<a class='yy-fast-edit-button' title='Edit' href='" . get_edit_post_link($related_post_id) . "'><span></span></a>";

                        			        if( function_exists('elementor_fail_php_version') ) {
                        						$elementor_edit_btn = esc_url( str_replace("action=edit", "action=elementor", get_edit_post_link($related_post_id) ) );
                        						$output_data_to_page .= "<a class='yy-fast-edit-button yy-edit-button-elementor' title='Edit' href='" . $elementor_edit_btn . "'><span></span></a>";
                        			        } // if( function_exists('elementor_fail_php_version') ) {

                        					$output_data_to_page .= "<span class='yy-fast-button-id'>" . $related_post_id . "</span>";

                        				$output_data_to_page .= "</div><!--yy-edit-buttons-->";

                                    } // if( isset($yydev_theme_data) && !empty($yydev_theme_data) && $yydev_theme_data['yy_fast_editing_button'] == 1 ) {

                                } // if( function_exists('yydev_theme_languages') ) {

    						} // if( is_user_logged_in() ) 

                        $output_data_to_page .= "</div><!--related-post-block-->";
                        $output_data_to_page .= "\n";

                    } // if($hide_related_post == 0) {

                $position_number++;
                } // foreach( $related_post_data as $this_related_post_data ) {

            $output_data_to_page .= "<div class='related-posts-clear'></div>";
            $output_data_to_page .= "\n";
            $output_data_to_page .= "</div><!--related-post-warp-->";
            $output_data_to_page .= "\n";

            // making sure there is a published post on the page that active and if not we will output warning
            if( $publish_posts_count == 0 ) {

                $output_data_to_page .= "\n";
                $output_data_to_page .= "<div class='related-post-warp-error'>There are no active related posts to output to this page</div>";
                $output_data_to_page .= "\n";
                
            } // if( $publish_posts_count == 0 ) {

        } else { // if( !empty($related_post_data_count) ) {

            $output_data_to_page .= "\n";
            $output_data_to_page .= "<div class='related-post-warp-error'>There are no active related posts to output to this page</div>";
            $output_data_to_page .= "\n";

        } // } else { // if( !empty($related_post_data_count) ) {

    } // if( !empty($page_id) ) {

    // ================================================
    // Output the data to the page
    // ================================================
        
    return $output_data_to_page;

} // function yy_output_related_posts_shortcode($attr = "", $content, $page_id = "", $title = "", $description = "") {

add_shortcode('yy-wordpress-related-posts', 'yy_output_related_posts_shortcode');


// ================================================
// Making sure the box wont have
// <p><span class="output-code"> box </span></p>
// around it, if the box is activate it will remove the
// <p><span class="output-code"> and </span></p>
// ================================================

function yy_remove_tags_from_related_posts_shortcode( $content ) {
    
    if(strpos($content, '[yy-wordpress-related-posts')) {
        
        $content_length = strlen($content); // getting the length of the string
        $boxes_count = strpos($content, '[yy-wordpress-related-posts'); // checking where the box start
            
        $first_part_content = substr($content, 0, $boxes_count); // the place where the content start before the box
        $second_part_content = substr($content, $boxes_count, $content_length); // the place where the content start after the box 
        
        // Making sure <p><span class="output-code"> exists and exists only once
        if( substr_count($first_part_content, '<p><span class="output-code">') == 1 ) {
            $word_starting_count = strrpos($first_part_content, '<p><span class="output-code">');
            $first_part_content = substr($first_part_content, 0, $word_starting_count);
            
            $second_part_content = preg_replace("/\<\/span\>\<\/p\>/", "", $second_part_content, 1, $results);
        } // if( substr_count($testing_name, "<p><span class='output-code'>") == 1 ) {
        
        $content = $first_part_content . $second_part_content;
        return $content;
    
    } else { // if(strpos($content, 'yy-wordpress-related-posts')) {
        return $content;
    } // } else { // if(strpos($content, 'yy-wordpress-related-posts')) {
    
} // function yy_remove_tags_from_related_posts_shortcode( $content ) {

add_filter("the_content", "yy_remove_tags_from_related_posts_shortcode");


?>