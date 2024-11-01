<?php

    // define the parent post id if it's loaded with ajax
    if( isset($_POST['this_related_parent_post_id']) && !empty($_POST['this_related_parent_post_id']) ) {
        $this_post_id = $_POST['this_related_parent_post_id'];
    } // if( isset($_POST['this_related_parent_post_id']) && !empty($_POST['this_related_parent_post_id']) ) {

    
    // if new post was inserted we will only get the last post
    $database_limit = '';
    if( isset($_POST['reload_only_one_post'] )) {
        $database_limit = "limit 1";
    } // if( isset($_POST['reload_only_one_post'] )) {
        

    if( !empty($this_post_id) ) {

        // define empty output code will insert the data into
        $output_code = "";

        // getting related post from the database that set with the same post parent id
        $related_post_data = $wpdb->get_results("SELECT * FROM " . $yy_related_secondary_table_name . " WHERE post_parent_id = '{$this_post_id}' ORDER BY position ASC, id DESC $database_limit");

        // making sure there are results on the database for this post
        if( !empty($related_post_data) ) {

            $position_number = 1; // define the position for the elements
            foreach( $related_post_data as $this_related_post_data ) {

                // ================================================
                // Getting the post data from the database
                // ================================================

                $related_post_title = $this_related_post_data->title; // getting the post title
                $related_post_description = $this_related_post_data->description; // getting the post excerpt (short description)
                $related_post_thumbnail = $this_related_post_data->image_url; // getting the thumbnail image url
                $related_post_thumbnail_alt = $this_related_post_data->image_alt; // setting the image alt as the post title
                $related_post_url = $this_related_post_data->page_url; // getting the url to the related post
                $related_post_position = $this_related_post_data->position; // getting the postition of the post on the page
                $db_related_post_id = $this_related_post_data->id; // the post id on the database
                $original_post_id = $this_related_post_data->original_post_id; // the original post number we took the data from
                $hide_post_from_page = $this_related_post_data->hide_box; // the original post number we took the data from

                // ================================================
                // Creating the ouptut data from the database
                // ================================================

                $output_code .= "<div class='related-post-block related-post-block-" . $db_related_post_id . "'>";

                    // creating class to check if the image is empty or not
                    $empty_image_class = "";
                    if( empty($related_post_thumbnail) || strstr($related_post_thumbnail, 'add-image.png') ) {
                        $related_post_thumbnail = plugins_url( 'images/add-image.png', dirname(__FILE__) );
                        $empty_image_class = "empty-releatd-image";
                    } // if( empty($related_post_thumbnail) || strstr($related_post_thumbnail, 'add-image.png') ) {


                    // checking if we should output post image
                    $hide_class = '';
                    if( $yy_related_data_array['display_thumbnail_on_page'] == 0 ) {
                        $hide_class= "yydev_related_display_none";
                    } // if( $yy_related_data_array['display_thumbnail_on_page'] == 0 ) {

                    $output_code .= "<div class='upload-img-warper " . $hide_class . "'>";

                        $output_code .= "<div class='related-post-admin-image " . $empty_image_class . "'>";
                            $output_code .= "<img id='related_post" . $db_related_post_id . "' class='related-post-image-display button-image-upload' src='" . yy_related_posts_output_string_value($related_post_thumbnail) . "' alt='' />";
                            $output_code .= "<a href='#' id='" . $db_related_post_id . "' class='remove-this-box-block-image'>X</a>";
                        $output_code .= "</div><!--related-post-admin-image-->";
                        $output_code .= "<div class='clear'></div>";
                        
                        $output_code .= "<div class='related-post-text-line'>";
                            $output_code .= "<label for='related_post" . $db_related_post_id . "'>Thumbnail URL:</label>";
                            $output_code .= "<input type='text' id='related_post" . $db_related_post_id . "' class='related-post-input related_post_image_url' name='related_post_image_url[]' value='" . yy_related_posts_output_string_value($related_post_thumbnail) . "' />";
                        $output_code .= "</div><!--related-post-text-line-->";
                        
                        $output_code .= "<div class='related-post-text-line'>";
                            $output_code .= "<label for='related_post_alt'>Thumbnail Alt:</label>";
                            $output_code .= "<input type='text' id='related_post_alt' class='related-post-input' name='related_post_image_alt[]' value='" . yy_related_posts_output_string_value($related_post_thumbnail_alt) . "' />";
                        $output_code .= "</div><!--related-post-text-line-->";

                        $output_code .= "<div class='related-post-text-line' style='display:none'>";
                            $output_code .= "<label for='related_post_url'>Post URL:</label>";
                            $output_code .= "<input type='text' id='related_post_url' class='related-post-input' name='related_post_url[]' value='" . yy_related_posts_output_string_value($related_post_url) . "' />";
                        $output_code .= "</div><!--related-post-text-line-->";

                     $output_code .= "</div><!--upload-img-warper-->";


                        $output_code .= "<div class='related-post-text-line'>";
                            $output_code .= "<label for='related_post_title'>Post Title:</label>";
                            $output_code .= "<input type='text' id='related_post_title' class='related-post-input' name='related_post_title[]' value='" . yy_related_posts_output_string_value($related_post_title) . "' />";
                        $output_code .= "</div><!--related-post-text-line-->";

                    // checking if we should output post description
                    $hide_class = '';
                    if( $yy_related_data_array['display_description_on_page'] == 0 ) {
                        $hide_class= "yydev_related_display_none";
                    } // if( $yy_related_data_array['display_description_on_page'] == 0 ) {

                    $output_code .= "<div class='related-post-text-line " . $hide_class . "'>";
                        $output_code .= "<label for='related_post_description'>Post Description:</label>";
                        $output_code .= " <textarea id='related_post_description' name='related_post_description[]' class='related-post-input' rows='5' cols='55'>" . yy_related_posts_output_string_value($related_post_description) . "</textarea>";
                    $output_code .= "</div><!--related-post-text-line-->";


                    $output_code .= "<div class='related-post-text-line'>";
                        $output_code .= "<label for='related_post_position'>Position:</label>";
                        $output_code .= " <input type='text' id='related_post_position' class='related-post-input short-post-input' name='related_post_position[]' value='" . yy_related_posts_output_string_value($position_number) . "' />";
                    $output_code .= "</div><!--related-post-text-line-->";

                    $current_hide_post_from_page = yy_related_posts_output_string_value($hide_post_from_page);
                    $output_code .= "<div class='related-post-text-line related-posts-hide related_posts_checkbox direction-ltr-block'>";
                    $output_code .= "<label>Hide related post from the page:</label>";
                        $output_code .= "<select name='related_post_hide[]'>";
                            $output_code .= "<option value='0' ";
                            if($current_hide_post_from_page == 0) { $output_code .="selected"; }
                            $output_code .= ">No</option>";

                            $output_code .= "<option value='1' ";
                            if($current_hide_post_from_page == 1) { $output_code .="selected"; }
                            $output_code .= ">Yes</option>";

                            $output_code .= "<option value='2' ";
                            if($current_hide_post_from_page == 2) { $output_code .="selected"; }
                            $output_code .= ">Show On Publish</option>";
                        $output_code .= "</select>";
                    $output_code .= "</div><!--related-post-text-line-->";


                    $output_code .= "<input type='hidden' name='post_id[]' value='" . $db_related_post_id . "' />";
                    $output_code .= "<input type='hidden' name='related_parent_post_id[]' value='" . $this_post_id . "' />";
                    
                $output_code .= "<div class='clear'></div>";

                $output_code .= "<input type='text' class='original-post-id-number' name='original_post_id[]' value='" . $original_post_id . "' />";
                $output_code .= "<a href='#' id='" . $db_related_post_id . "' class='remove-this-box-block'>X</a>";

                $output_code .= "</div><!--related-post-block-->";
            
                $position_number++;
        
            } // foreach( $related_post_data as $this_related_post_data ) {

        } else { // if( !empty($related_post_data) ) {
            
            // output message if there are no posts
            // $output_code .= "<p class='no-related-posts'>There are no related post set to this article</p>";

        } // } else { // if( !empty($related_post_data) ) {

    } // if( !empty($this_post_id) ) {

// echoing the data to the page
echo $output_code;

?>