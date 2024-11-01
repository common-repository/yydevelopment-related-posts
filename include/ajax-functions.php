<?php

// =============================================================
// removing related post from the database if the user deleted it
// =============================================================

function yy_remove_related_post_from_db() {

    // including setting.php file to get the database info
    include('settings.php');

    global $wpdb; // getting access to the database

    // the post id we want to remove
    $related_post_id = intval($_POST['post_id']);

    // removing the post from the database
    $wpdb->delete( $yy_related_secondary_table_name, array('id'=>$related_post_id) );

    die(); // we have to end ajax functions with die();
} // function yy_remove_related_post_from_db() {

add_action( 'wp_ajax_yy_remove_related_post_from_db', 'yy_remove_related_post_from_db' );

// =============================================================
// insert the data of new post using the post id
// =============================================================

function yy_insert_new_related_post_data() {

    // including setting.php file to get the database info
    include('settings.php');

    // ================================================
    // Getting the related post data with an id
    // ================================================

    $original_post_id = intval($_POST['original_post_id']); // the id of the related post (we getting this with ajax)
    $related_post_parent_id = sanitize_text_field($_POST['post_parent_id']);
    $description_load_type = sanitize_text_field($_POST['loadDescriptionType']);

    // making sure the post value is not empty and that it's a number
    if( !empty($original_post_id) && is_numeric($original_post_id) ) {

        $get_related_post_info = get_post($original_post_id); // getting the info array for the psot
        $insert_related_post_title = $get_related_post_info->post_title; // getting the post title

        $insert_related_post_description = "";

        // if the selected description is regular post data
        if( $description_load_type === 'post') {
            $insert_related_post_description = $get_related_post_info->post_excerpt; // getting the post excerpt (short description)
            if(empty($insert_related_post_description)) {
                $insert_related_post_description = wp_trim_words($get_related_post_info->post_content, 30, '...');
            } // if(empty($insert_related_post_description)) {
        } // if( $description_load_type === 'post') {

        // if the selected description yoast data
        if( $description_load_type === 'yoast') {
            $insert_related_post_description = get_post_meta( $original_post_id, '_yoast_wpseo_metadesc', true);
        } // if( $description_load_type === 'post') {

        $insert_related_post_thumbnail = get_the_post_thumbnail_url($original_post_id); // getting the thumbnail image url
        $insert_related_post_thumbnail_alt = $insert_related_post_title; // setting the image alt as the post title
        $insert_related_post_url = get_permalink($original_post_id); // getting the url to the related post
        $insert_related_parent_post_id = $related_post_parent_id; // getting the parent post id (the post that going to display the related posts)

        // if there is no image to the post we will add image that allow to add an image
        $insert_related_post_thumbnail_img = $insert_related_post_thumbnail;
        if(empty($insert_related_post_thumbnail_img)) {
            $insert_related_post_thumbnail_img = plugins_url( 'images/add-image.png', dirname(__FILE__) );
        } // if(empty($insert_related_post_thumbnail_img)) {
            
        // incase the post inserted doesn't exists
        if(empty($insert_related_post_title)) {
            echo "<span class='yy-reload-posts-error'>No data was found for the post id, please try and insert different id instead.</span>";
            die();
        } // if(empty($insert_related_post_title)) {
            
?>

        <div class='related-post-hidden-box'>

            <img src="<?php echo $insert_related_post_thumbnail_img; ?>" id='related_post-insert' class='related-post-image-display button-image-upload' alt="" />

            <div class='related-post-text-line'>
                <label for='related_post-insert'>Thumbnail URL:</label>
                <input type='text' id='related_post-insert' class='related-post-input' name='insert_related_post_thumbnail' value='<?php echo $insert_related_post_thumbnail; ?>' />
            </div><!--related-post-text-line-->

            <div class='related-post-text-line'>
                <label for='insert_related_post_thumbnail_alt'>Thumbnail Alt:</label>
                <input type='text' id='insert_related_post_thumbnail_alt' class='related-post-input' name='insert_related_post_thumbnail_alt' value='<?php echo yy_related_posts_output_string_value($insert_related_post_thumbnail_alt); ?>' />
            </div><!--related-post-text-line-->

            <div class='related-post-text-line'>
                <label for='insert_related_post_title'>Post Title:</label>
                <input type='text' id='insert_related_post_title' class='related-post-input' name='insert_related_post_title' value='<?php echo yy_related_posts_output_string_value($insert_related_post_title); ?>' />
            </div><!--related-post-text-line-->

            <div class='related-post-text-line' style='display:none'>
                <label for='insert_related_post_url'>Post URL:</label>
                <input type='text' id='insert_related_post_url' class='related-post-input' name='insert_related_post_url' value='<?php echo yy_related_posts_output_string_value($insert_related_post_url); ?>' />
            </div><!--related-post-text-line-->

            <div class='related-post-text-line'>
                <label for='insert_related_post_description'>Post Description:</label>
                <textarea id='insert_related_post_description' name='insert_related_post_description' class='related-post-input' rows='5' cols='55'><?php echo yy_related_posts_output_string_value($insert_related_post_description); ?></textarea>
            </div><!--related-post-text-line-->

            <div class='related-post-text-line' style='display:none'>
                <label for='insert_related_post_url'>Post URL:</label>
                <input type='text' id='insert_related_post_url' class='related-post-input' name='insert_related_post_url' value='<?php echo $insert_related_post_url; ?>' />
            </div><!--related-post-text-line-->
<?php

            $related_post_hide = intval($yy_related_data_array['hide_post_type']);
?>

            <div class='related-post-text-line related_posts_checkbox related-posts-hide direction-ltr-block' style='display:none'>
                <label>Hide related post from the page:</label>
                <select name='hide_related_post'>
                    <option value='0' <?php if($related_post_hide == 0) {echo "selected";} ?> >No</option>
                    <option value='1' <?php if($related_post_hide == 1) {echo "selected";} ?> >Yes</option>
                    <option value='2' <?php if($related_post_hide == 2) {echo "selected";} ?> >Show When Published</option>
                </select>
            </div><!--related-post-text-line-->

            <input type='hidden' name='insert_original_post_id' value='<?php echo $original_post_id; ?>' />
            <input type='hidden' name='insert_related_parent_post_id' value='<?php echo $insert_related_parent_post_id; ?>' />

            <div class='clear'></div>
            <a href='#' class='insert-new-related-post-btn'>Insert New Related Post</a>

            <a href='#' class='remove-this-related-insert-post'>X</a>

        </div><!--related-post-hidden-box-->

        <div class="yy-related-update-new-insert-box"></div>
        <div class='clear'></div>

<?php

    } else { // if( !empty($original_post_id) && is_numeric($original_post_id) ) {
        echo "<span class='yy-reload-posts-error'>Please insert a correct post id number</span>";
    } // } else  // if( !empty($original_post_id) && is_numeric($original_post_id) ) {

    die();
} // function yy_insert_new_related_post_data() {

add_action( 'wp_ajax_yy_insert_new_related_post_data', 'yy_insert_new_related_post_data' );


// =============================================================
// insert new related post into the database
// =============================================================

function yy_yy_insert_related_post_to_database() {

    // including setting.php file to get the database info
    include('settings.php');

    global $wpdb; // getting access to the database

    if( isset($_POST['related_parent_post_id']) && !empty($_POST['related_parent_post_id'])  ) {

        $related_parent_post_id = sanitize_text_field( $_POST['related_parent_post_id']); // getting the id of the parent post
        $original_post_id = intval($_POST['original_post_id']); // the id of the related post
        $related_post_url = esc_url_raw($_POST['related_post_url']); // getting the url to the related post
        $related_post_title = wp_kses_post($_POST['related_post_title']); // getting the post title
        $related_post_thumbnail = esc_url_raw($_POST['related_post_image_url']); // getting the thumbnail image url
        $related_post_thumbnail_alt = sanitize_text_field($_POST['related_post_image_alt']); // setting the image alt as the post title
        $related_post_description = wp_kses_post($_POST['related_post_description']); // getting the post excerpt (short description)
        $hide_related_post = intval($_POST['related_post_hide']); // getting the post excerpt (short description)
        $new_position = 0;

        $wpdb->insert( $yy_related_secondary_table_name,
            array('post_parent_id'=>$related_parent_post_id,
            'original_post_id'=>$original_post_id,
            'page_url'=>$related_post_url,
            'title'=>$related_post_title,
            'image_url'=>$related_post_thumbnail,
            'image_alt'=>$related_post_thumbnail_alt,
            'description'=>$related_post_description,
            'position'=>$new_position,
            'hide_box'=>$hide_related_post,
            ), array('%s', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%d') );
        
    } // f( isset($_POST['related_parent_post_id']) && !empty($_POST['related_parent_post_id'])  ) {

    die(); 
} // function yy_remove_related_post_from_db() {

add_action( 'wp_ajax_yy_yy_insert_related_post_to_database', 'yy_yy_insert_related_post_to_database' );


// =============================================================
// reloading all the related post into the page
// =============================================================

function yy_reloading_all_related_post() {

    // including setting.php file to get the database info
    include('settings.php');

    global $wpdb; // getting access to the database

    // including the file that reload all the related posts
    include('related-post-loader.php');

    die();
} // function yy_reloading_all_related_post() {

add_action( 'wp_ajax_yy_reloading_all_related_post', 'yy_reloading_all_related_post' );


// =============================================================
// reloading all the related post into the page
// =============================================================

function yy_updating_related_post_to_database() {

    // including setting.php file to get the database info
    include('settings.php');

    global $wpdb; // getting access to the database

    // this file will update the database with with changes
    include('insert-to-db.php');

    die();
} // function yy_updating_related_post_to_database() {

add_action( 'wp_ajax_yy_updating_related_post_to_database', 'yy_updating_related_post_to_database' );

// =============================================================
// reloading all the related post into the page
// =============================================================

function yy_copy_related_posts_from_other_page() {

    // including setting.php file to get the database info
    include('settings.php');

    global $wpdb; // getting access to the database

    // this file will update the database with with changes
    include('copy-related-posts.php');

    die();
} // function yy_copy_related_posts_from_other_page() {

add_action( 'wp_ajax_yy_copy_related_posts_from_other_page', 'yy_copy_related_posts_from_other_page' );