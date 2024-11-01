<?php

        include('settings.php');

        // ====================================================
        // Add seo data to the database
        // ====================================================

        if( isset($_POST['related_parent_post_id']) && !empty($_POST['related_parent_post_id'])  ) {

            $related_post_amount = 1;
            if( is_array($_POST['post_id'] )) {
                $related_post_amount = count($_POST['post_id']);
            } // if( is_array($_POST['post_id'] )) {

            // creating a loop that will update each one of the related posts
            for($num = 0; $num < $related_post_amount; $num++) {

                $related_parent_post_id = sanitize_text_field($_POST['related_parent_post_id'][$num]); // getting the id of the parent post
                $related_post_id = intval($_POST['post_id'][$num]); // the id of the related post in the datbase
                $original_post_id = intval($_POST['original_post_id'][$num]); // the original post id number
                $related_post_url = esc_url_raw($_POST['related_post_url'][$num]); // getting the url to the related post
                $related_post_title = wp_kses_post($_POST['related_post_title'][$num]); // getting the post title
                $related_post_thumbnail = esc_url_raw($_POST['related_post_image_url'][$num]); // getting the thumbnail image url
                $related_post_thumbnail_alt = sanitize_text_field($_POST['related_post_image_alt'][$num]); // setting the image alt as the post title
                $related_post_description = wp_kses_post($_POST['related_post_description'][$num]); // getting the post excerpt (short description)
                $related_post_position = intval($_POST['related_post_position'][$num]); // the position we will display the post
                $hide_related_post = intval($_POST['related_post_hide'][$num]); // the position we will display the post

                $wpdb->update( $yy_related_secondary_table_name,
                    array('post_parent_id'=>$related_parent_post_id,
                    'original_post_id'=>$original_post_id,
                    'page_url'=>$related_post_url,
                    'title'=>$related_post_title,
                    'image_url'=>$related_post_thumbnail,
                    'image_alt'=>$related_post_thumbnail_alt,
                    'description'=>$related_post_description,
                    'position'=>$related_post_position,
                    'hide_box'=>$hide_related_post,
                    ), array('id'=>$related_post_id), array('%s', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d') );
            } // for($num = 0; $num < $related_post_amount; $num++) {

            // echoing message if the content was updated with ajax
            echo "The content was updated successfully";

        } // if( isset($_POST['related_parent_post_id']) && !empty($_POST['related_parent_post_id'])  ) {

?>