<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

$success_message = '';
$post_error_message = '';

// ====================================================
// Include the file that contains all the info
// ====================================================
include('settings.php');
require_once('script.php');
require_once('ajax-functions.php');

// getting the page url for the settings page
$plugin_page_url = esc_url( menu_page_url( 'yydev-related-posts', false ) );

if( isset($_GET['id']) ) {
    $secondary_page_id = intval($_GET['id']);
} // if( isset($_GET['id']) ) {

// ====================================================
// Redirect the user the to main page
// if the data was not found
// ====================================================

if( isset($secondary_page_id) && !empty($secondary_page_id) && is_numeric($secondary_page_id) ) {
    
    global $wpdb;
    $check_for_real_data_id = $wpdb->query("SELECT id FROM " . $yy_related_main_table_name . " WHERE id = " . $secondary_page_id);
    
    if($check_for_real_data_id == 0) {
        $post_error_message = "The related post id you were looking for was not found";
        $new_page_link = $plugin_page_url . "&error-message=" . urlencode($post_error_message);
    } // if($check_for_real_data_id < 1 ) {

} else { // if( isset($secondary_page_id) && !empty($secondary_page_id) && is_numeric($secondary_page_id) ) {

    $post_error_message = "The related post you were looking for was not found";
    $new_page_link = $plugin_page_url . "&error-message=" . urlencode($post_error_message);

} // } else { // if( isset($secondary_page_id) && !empty($secondary_page_id) && is_numeric($secondary_page_id) ) {

// ====================================================
// Update the main database if it's changed
// ====================================================
    
    
if( isset($_POST['yy_related_posts_nonce_edit_main_db']) ) {

    if( wp_verify_nonce($_POST['yy_related_posts_nonce_edit_main_db'], 'yy_related_posts_action_edit_main_db') ) {

        $main_deta_id = intval($_POST['form_id']);        

        if( !empty($main_deta_id) ) {

                // If there is no error insert the info to the database
                $related_post_name = sanitize_text_field($_POST['related_post_name']);

                $related_post_slug = str_replace(" ", "_", strtolower(trim($related_post_name)));
                $related_post_slug = sanitize_text_field($related_post_slug);

                // Checking if the main database id exists
                $check_database_exists = $wpdb->query("SELECT id FROM " . $yy_related_main_table_name . " where id = " . $main_deta_id);

                if($check_database_exists == 0 ) {
                    $post_error_message = "The related post id was not found";
                } else { // if($check_database_exists < 1 ) {
                
                // If the main database id exists it will update it
                $wpdb->update( $yy_related_main_table_name,
                    array('name'=>$related_post_name,
                    'slug'=>$related_post_slug,
                    ), array('id'=>$main_deta_id), array('%s', '%s') );

                    // Creating page link and redirect the user the current page with the new data
                    $new_detabase_id = $wpdb->insert_id;
                    $success_message = "The related post was updated successfully";

                    $new_page_link = $plugin_page_url . "&view=secondary&id=" . $main_deta_id . "&message=" . urlencode($success_message);
                    // yydev_redirect_redirections_page($new_page_link);

                } // } else { // if($check_database_exists < 1 ) {
                
        } // if( !empty($main_deta_id) ) {

    } else { // if( wp_verify_nonce($_POST['yy_related_posts_nonce_edit_main_db'], 'yy_related_posts_action_edit_main_db') ) {
        $post_error_message = "Form nonce was incorrect";
    } // } else { // if( wp_verify_nonce($_POST['yy_related_posts_nonce_edit_main_db'], 'yy_related_posts_action_edit_main_db') ) {

} // if( isset($_POST['yy_related_posts_nonce_edit_main_db']) ) {

?>


<div class="wrap yydevelopment-related <?php if(is_rtl()) {echo "yydevelopment-related-rtl";} ?>">
    <h2 class="isplay-inline">Edit Related Posts <a class="go-back-button" href="<?php echo $plugin_page_url; ?>">Go Back</a></h2>
    

    <?php yy_echo_related_posts_data_message_if_exists(); ?>
    <?php yy_related_posts_echo_success_message_if_exists($success_message); ?>
    <?php yy_echo_related_posts_data_error_message_if_exists($post_error_message); ?>
    
    <div class="insert-new">
        
<?php

    $check_secondary_deta_id = $wpdb->get_row("SELECT * FROM " . $yy_related_main_table_name . " WHERE id = '{$secondary_page_id}' ");

?>
                
        <h4>Edit Related Page Settings</h4>
                
        <form class="edit-main-database" method="POST" action="">
           
            <span>Related Page ID: <?php echo $check_secondary_deta_id->id; ?></span>
            <br />
           
            <label for="related_post_name">Related Posts Name:</label>
            <input type="text" id="related_post_name" class="related_post_name input-long" name="related_post_name" value="<?php echo yydev_related_html_value($check_secondary_deta_id->name); ?>" />

            <input type="hidden" name="form_id" class="form_id" value="<?php echo yydev_related_html_value($secondary_page_id); ?>" />
            
            <br /><br />

            <?php wp_nonce_field( 'yy_related_posts_action_edit_main_db', 'yy_related_posts_nonce_edit_main_db' ); ?>

            <input type="submit" class="edit-main-database img_url_button" name="edit-main-database" value="Edit Related Posts Name" />
        </form>
    
<br /><br />

<div id="yy_related_posts" class="postbox yy_related_global_page">

<?php
    // This will enqueue the Media Uploader script
    wp_enqueue_media();
?>

<?php

// this ID will be as the parent page id in the database
if( intval($check_secondary_deta_id->id) ) {
    $global_page_id = "g" . $check_secondary_deta_id->id;
} // if( intval() ) {

// ====================================================
// Adding the admin ouput from regular pages
// ====================================================
include('admin-output.php');

?>
</div><!--yy_related_posts-->

<br /><br />
<span id="footer-thankyou-code">This plugin was create by <a target="_blank" href="https://www.yydevelopment.com">YYDevelopment</a>. If you liked the plugin please give it a <a target="_blank" href="https://wordpress.org/plugins/yydevelopment-related-posts/#reviews">5 stars review</a>. 
If you want to help support this FREE plugin <a target="_blank" href="https://www.yydevelopment.com/coffee-break/?plugin=yydevelopment-related-posts">buy us a coffee</a>.</span>
</span>
</div><!--wrap-->
