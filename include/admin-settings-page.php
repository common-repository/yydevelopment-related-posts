<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

$success_message = '';
$error_message = '';

// ====================================================
// Include the file that contains all the info
// ====================================================
include('settings.php');

// ===============================================
// This will allow us to use the media uploader
// ===============================================
wp_enqueue_media();

// ========================================================================================================
// Update the data if it's changed
// ========================================================================================================
    
if( isset($_POST['yydev_related_settings_nonce']) ) {

    if( wp_verify_nonce($_POST['yydev_related_settings_nonce'], 'yydev_related_settings_action') ) {

        // ----------------------------------------------
        // getting all the values and clear data
        // ----------------------------------------------        

        $display_thumbnail_on_page = yydev_related_checkbox_isset('display_thumbnail_on_page');
        $display_description_on_page = yydev_related_checkbox_isset('display_description_on_page');
        $load_description_type = sanitize_text_field( $_POST['load_description_type'] );
        $hide_post_type = intval( $_POST['hide_post_type'] );
        

        // ----------------------------------------------
        // insert the data into an array
        // ----------------------------------------------  

        $plugin_data_array = array(
            'display_thumbnail_on_page' => $display_thumbnail_on_page,
            'display_description_on_page' => $display_description_on_page,
            'load_description_type' => $load_description_type,
            'hide_post_type' => $hide_post_type,
        ); // $creating_data_array = array(

        // ----------------------------------------------
        // creating a value with all the array data
        // ----------------------------------------------  

        $array_key_name = '';
        $array_item_value = '';
        
	    foreach($plugin_data_array as $key=>$item) {
	        $array_key_name .= "####" . $key;
			$array_item_value .= "####" . $item;
	    } // foreach($medical_form_array as $key=>$item) {

        // ----------------------------------------------
        // inserting all the data to datbase
        // ----------------------------------------------  

        $plugin_data = $array_key_name . "***" . $array_item_value;
        $plugin_data = wp_kses_post($plugin_data);

        // update optuon on the database into wp_options
        update_option($wp_options_name, $plugin_data);

        $success_message = "The data was updated successfully";

    } else { // if( wp_verify_nonce($_POST['yydev_related_settings_nonce'], 'yydev_related_settings_action') ) {
        $error_message = "Form nonce was incorrect";
    } // } else { // if( wp_verify_nonce($_POST['yydev_related_settings_nonce'], 'yydev_related_settings_action') ) {

} // if( isset($_POST['yydev_related_settings_nonce']) ) {

// ========================================================================================================
// Get all the data and ouput it into the page
// ========================================================================================================

$getting_plugin_data = get_option($wp_options_name);

if( !empty($getting_plugin_data) ) {

    // ----------------------------------------------
    // breaking the string into to 2 variables. the array namd and vakue  
    // ----------------------------------------------  

    $break_array = explode("***", $getting_plugin_data);

    $item_name = explode("####", $break_array[0]);
    $key_name = explode("####", $break_array[1]);

    $array_count = count($key_name);

    // ----------------------------------------------
    // creating an organized array with all values
    // ----------------------------------------------      

    for($count_number = 0; $count_number < $array_count; $count_number++) {
    	$plugin_data_array[ $item_name[$count_number] ] = $key_name[$count_number];
    } // for($count_number = 0; $count_number < $array_count; $count_number++) {

} // if( !empty($getting_plugin_data) ) {

?>

<div class="wrap yydevelopment-related-settings">

    <h2 class="display-inline">Related Posts Global Settings</h2>
    <p>Below you edit the related posts global settings:</p>

    <?php yy_echo_related_posts_data_message_if_exists(); ?>
    <?php yy_related_posts_link_echo_success_message_if_exists($success_message); ?>
    <?php yy_echo_related_posts_data_error_message_if_exists($post_error_message); ?>   

    <div class="insert-new">

<form class="edit-form-data" method="POST" action="">

        <br />
        <h2> Basic Settings: </h2>

        <div class="yydev_related_line">
            <input type="checkbox" id="display_thumbnail_on_page" class="checkbox" name="display_thumbnail_on_page" value="1" <?php if($plugin_data_array['display_thumbnail_on_page'] == 1) {echo "checked";} ?> />
            <label for="display_thumbnail_on_page">Allow to select and display thumbnail image on the page</label>
        </div><!--yydev_related_line-->

        <div class="yydev_related_line">
            <input type="checkbox" id="display_description_on_page" class="checkbox" name="display_description_on_page" value="1" <?php if($plugin_data_array['display_description_on_page'] == 1) {echo "checked";} ?> />
            <label for="display_description_on_page">Allow to add a description for each related post</label>
        </div><!--yydev_related_line-->

        <div class="yydev_related_line">
            <label for="load_description_type">Load posts description from: </label>

            <select name="load_description_type">
                <option value="post" <?php if($plugin_data_array['load_description_type'] == "post") {echo "selected";} ?> >Load From Post</option>
                <option value="yoast" <?php if ($plugin_data_array['load_description_type'] == "yoast") {echo "selected";} ?> >Load From Yoast</option>
                <option value="empty" <?php if ($plugin_data_array['load_description_type'] == "empty") {echo "selected";} ?> >Leave Blank</option>
            </select>
        </div><!--yydev_related_line-->

        <div class="yydev_related_line">
            <label for="hide_post_type">Default Hide related post type: </label>

            <select name="hide_post_type">
                <option value="0" <?php if($plugin_data_array['hide_post_type'] == "0") {echo "selected";} ?> >No</option>
                <option value="1" <?php if ($plugin_data_array['hide_post_type'] == "1") {echo "selected";} ?> >Yes</option>
                <option value="2" <?php if ($plugin_data_array['hide_post_type'] == "2") {echo "selected";} ?> >Show On Publish</option>
            </select>
        </div><!--yydev_related_line-->

        <div class="clear"></div>


        <br />

        <?php
            // creating nonce to make sure the form was submitted correctly from the right page
            wp_nonce_field( 'yydev_related_settings_action', 'yydev_related_settings_nonce' ); 
        ?>

        <input type="submit" class="edit-form-data yydev-tags-submit" name="insert_top_btn" value="Submit Changes" />

</form>

<br /><br /><br />
<span id="footer-thankyou-code">This plugin was create by <a target="_blank" href="https://www.yydevelopment.com">YYDevelopment</a>. If you liked the plugin please give it a <a target="_blank" href="https://wordpress.org/plugins/yydevelopment-related-posts/#reviews">5 stars review</a>. 
If you want to help support this FREE plugin <a target="_blank" href="https://www.yydevelopment.com/coffee-break/?plugin=yydevelopment-related-posts">buy us a coffee</a>.</span>
</span>
</div><!--wrap-->