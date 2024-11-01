<?php

include('settings.php');

$copy_from_related_parent_post_id = intval($_POST['copy_from_post_id']);
$this_post_id_number = $_POST['this_post_id_number'];

    // getting related post data from the copy post page
    $copied_post_data = $wpdb->get_results("SELECT * FROM " . $yy_related_secondary_table_name . " WHERE post_parent_id = " . $copy_from_related_parent_post_id . " ORDER BY position ASC, id DESC");

    // making sure there are results on the database for this post
    if( !empty($copied_post_data) && !empty($copy_from_related_parent_post_id) ) {

        $position_number = 1; // define the position for the elements
        foreach( $copied_post_data as $this_copied_post_data ) {

            $related_parent_post_id = intval($this_copied_post_data->post_title); // getting the id of the parent post
            $original_post_id = intval($this_copied_post_data->original_post_id); // the original post id number
            $related_post_url = esc_url_raw($this_copied_post_data->page_url); // getting the url to the related post
            $related_post_title = wp_kses_post($this_copied_post_data->title); // getting the post title
            $related_post_thumbnail = esc_url_raw($this_copied_post_data->image_url); // getting the thumbnail image url
            $related_post_thumbnail_alt = sanitize_text_field($this_copied_post_data->image_alt); // setting the image alt as the post title
            $related_post_description = wp_kses_post($this_copied_post_data->description); // getting the post excerpt (short description)
            $related_post_position = intval($this_copied_post_data->position); // the position we will display the post
            $hide_post_from_page = intval($this_copied_post_data->hide_box); // the position we will display the post

            $wpdb->insert( $yy_related_secondary_table_name,
                array('post_parent_id'=>$this_post_id_number,
                'original_post_id'=>$original_post_id,
                'page_url'=>$related_post_url,
                'title'=>$related_post_title,
                'image_url'=>$related_post_thumbnail,
                'image_alt'=>$related_post_thumbnail_alt,
                'description'=>$related_post_description,
                'position'=>$related_post_position,
                'hide_box'=>$hide_post_from_page,
                ), array('%s', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d') );

        } // foreach( $copied_post_data as $this_copied_post_data ) {
    
        echo "The related posts were copied successfully from page #" . $copy_from_related_parent_post_id;

    } else { // if( !empty($copied_post_data) && !empty($copy_from_related_parent_post_id) ) {

        // incase there were no results for the copy number 
        echo "no data was found for your post number, make you inserted the correct number";

    } // } else { // if( !empty($copied_post_data) && !empty($copy_from_related_parent_post_id) ) {

?>