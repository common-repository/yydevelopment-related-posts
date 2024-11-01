<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php

$success_message = '';
$post_error_message = '';

// ====================================================
// Include the file that contains all the info
// ====================================================
include('settings.php');

// getting the page url for the settings page
$plugin_page_url = esc_url( menu_page_url( 'yydev-related-posts', false ) );

// ====================================================
// Inserting the content to the database if it was created
// ====================================================

if( isset($_POST['yy_related_posts_nonce_main']) ) {

    if( wp_verify_nonce($_POST['yy_related_posts_nonce_main'], 'yy_related_posts_action_main') ) {
    
        if( empty($_POST['form_submit_name']) ) {
            // If the content name is empty echo a message
            $submit_name_error = "You have to choose a name for the  related posts folder";

        } else { // if( !empty($_POST['form_submit_name']) ) {

            // If there is no error insert the info to the database
            $form_submit_name = sanitize_text_field($_POST['form_submit_name']);

            $form_slug_name = str_replace(" ", "_", strtolower(trim($form_submit_name)));
            $form_slug_name = sanitize_text_field($form_slug_name);

            // Checking if the content name already exists
            $form_submit_name_exists_check = $wpdb->query("SELECT slug FROM " . $yy_related_main_table_name . " WHERE slug = '{$form_slug_name}' ");
                    
            if($form_submit_name_exists_check > 0 ) {
                $submit_name_error = "The related post name is already exists please choose different name";
            } else { // if($form_submit_name_exists_check > 0 ) {
            
            // If the content name not exists it will insert it into the database
            $wpdb->insert( $yy_related_main_table_name,
                array('name'=>$form_submit_name,
                'slug'=>$form_slug_name,
                ), array('%s', '%s') );

                // Creating page link and redirect the user to the new url page where he can edit the content
                $new_detabase_id = $wpdb->insert_id;
                $new_page_link = $plugin_page_url . "&view=secondary&id=" . $new_detabase_id;
                $success_message = "The related posts folder was successfully created to view it <a href='" . $new_page_link . "'>click here</a>";

            } // } else { // if($form_submit_name_exists_check > 0 ) {
        
        } // if( !empty($_POST['form_submit_name']) ) {
    
    } else { // if( wp_verify_nonce($_POST['yy_related_posts_nonce_main'], 'yy_related_posts_action_main') ) {
        $submit_name_error = "Form nonce was incorrect";
    } // } else { // if( wp_verify_nonce($_POST['yy_related_posts_nonce_main'], 'yy_related_posts_action_main') ) {

} // if( isset($_POST['yy_related_posts_nonce_main']) ) {


// ====================================================
// Removing the main Data if it was deleted
// ====================================================

if( isset($_POST['yy_related_posts_nonce_remove']) ) {

    if( wp_verify_nonce($_POST['yy_related_posts_nonce_remove'], 'yy_related_posts_action_remove') ) {

        $secondary_page_id = '';
        if( isset($_POST['remove_related_id']) ) {
            $secondary_page_id = intval($_POST['remove_related_id']);
        } // if( isset($_POST['remove_related_id']) ) {

        if( isset($secondary_page_id) && !empty($secondary_page_id) ) {

            $check_content_id = $wpdb->query("SELECT * FROM " . $yy_related_main_table_name . " WHERE id = " . $secondary_page_id);

            if($check_content_id > 0) {
                // if the data id exists on the database it will be removed
                
                $wpdb->delete( $yy_related_main_table_name, array('id'=>$secondary_page_id) ); // removing main database info

                $db_posts_global_id = "g" . $secondary_page_id;
                $wpdb->delete( $yy_related_secondary_table_name, array('post_parent_id'=>$db_posts_global_id) ); // removing all sub database info

                $success_message = "The related post id #" . $secondary_page_id . " was removed successfully";
                
            } else { // if($check_content_id > 0) {
                $post_error_message = "The related post id wasn't not found";
            } // } else { // if($check_content_id > 0) {
            
        } // if( isset($secondary_page_id) && !empty($secondary_page_id) ) {

    } else { // if( wp_verify_nonce($_POST['yy_related_posts_nonce_remove'], 'yy_related_posts_action_remove') ) {
        $post_error_message = "Form nonce was incorrect";
    } // } else { // if( wp_verify_nonce($_POST['yy_related_posts_nonce_remove'], 'yy_related_posts_action_remove') ) {

} // if( isset($_POST['yy_related_posts_nonce_remove']) ) {

?>

<div class="wrap yydevelopment-related-main yydevelopment-related <?php if(is_rtl()) {echo "yydevelopment-related-rtl yydevelopment-related-rtl-main";} ?>">
    <h2>Related Pages</h2>

    <?php yy_echo_related_posts_data_message_if_exists(); ?>
    <?php yy_related_posts_link_echo_success_message_if_exists($success_message); ?>
    <?php yy_echo_related_posts_data_error_message_if_exists($post_error_message); ?>    

    <br />    
    <div class="insert-new">
        
        <h5>Related Page</h5>
        <form class="insert-form" method="POST" action="">
            <label for="form_submit_name">Create New Related Page</label>
            <input type="text" id="form_submit_name" class="form_submit_name input-long direction-ltr" name="form_submit_name" value="" />

            <?php
                // creating nonce to make sure the form was submitted correctly from the right page
                wp_nonce_field( 'yy_related_posts_action_main', 'yy_related_posts_nonce_main' ); 
            ?>

            <input type="submit" name="submit_new_form" value="Submit Related Page" />
            <?php 
                if(isset($submit_name_error)) {
                    yy_show_related_posts_data_submit_form_error_message($submit_name_error, '1'); 
                } // if(isset($submit_name_error)) {
            ?>
        </form>
    
    </div><!--insert-new-->
            
    <div class="main-page-table">
    <table class="wp-list-table widefat fixed striped posts">
    <thead>
        <tr>
            <th style="width:80px;">ID</th>
            <th style="width:150px;">Related Page Name</th>
            <th style="width:250px;">Shortcode</th>
            <th style="width:120px;text-align:center;">No. of Posts</th>
            <th style="width:190px;">Action</th>
        </tr>
    </thead>
    
    <tbody id="the-list">
    
<?php
    
// ================================================
// Echoing all the data from the database 
// ================================================
    
    global $wpdb;
    $database_content_output = $wpdb->get_results("SELECT * FROM " . $yy_related_main_table_name . " ORDER BY id DESC ");
    
    // Echo if nothing was found
    if(empty($database_content_output)) {
?>
    <tr class="no-items"><td class="colspanchange" colspan="6">No related posts where found</td></tr>
<?php     
    } // if(empty($database_content_output)) {
    
    
    foreach($database_content_output as $database_output) {
            
?>
        <tr>
            <td><a href="<?php echo $plugin_page_url . "&view=secondary&id=" . $database_output->id; ?>"><?php echo $database_output->id; ?></a></td>
            <td><a href="<?php echo $plugin_page_url . "&view=secondary&id=" . $database_output->id; ?>"><?php echo $database_output->name; ?></a></td>

            <td ><input type="text" class="output-code" value='[yy-wordpress-related-posts page_id="g<?php echo $database_output->id; ?>"]' /></td>

            <?php
                $global_page_id = "g" . $database_output->id;
            ?>

            <td style="text-align:center;">
            <?php 
                echo $wpdb->query("SELECT * FROM $yy_related_secondary_table_name WHERE post_parent_id = '{$global_page_id}' "); 
            ?>
            </td>

            <td><a href="<?php echo $plugin_page_url . "&view=secondary&id=" . $database_output->id; ?>">Edit</a> &nbsp;&nbsp;&nbsp; / &nbsp;&nbsp;&nbsp;
            
            <form class="insert-form remove-data-form" method="POST" action="">
                <?php wp_nonce_field( 'yy_related_posts_action_remove', 'yy_related_posts_nonce_remove' ); ?>
                <input type="hidden" name="remove_related_id" value="<?php echo $database_output->id; ?>" />
                <input type="submit" class="remove-submit-button" name="submit_new_form" value="Delete" />
            </form>

        </tr>
        
<?php
    } // foreach($database_content_output as $database_output) {
    
?>

    </tbody>
    
    <tfoot>
        <tr>
            <th style="width:80px;">ID</th>
            <th style="width:150px;">Related Page Name</th>
            <th style="width:250px;">Shortcode</th>
            <th style="width:120px;text-align:center;">No. of Posts</th>
            <th style="width:190px;">Action</th>
        </tr>
    </tfoot>
    
    </table>
    </div><!--main-page-table-->
        
<br />
<span id="footer-thankyou-code">This plugin was create by <a target="_blank" href="https://www.yydevelopment.com">YYDevelopment</a>. If you liked the plugin please give it a <a target="_blank" href="https://wordpress.org/plugins/yydevelopment-related-posts/#reviews">5 stars review</a>. 
If you want to help support this FREE plugin <a target="_blank" href="https://www.yydevelopment.com/coffee-break/?plugin=yydevelopment-related-posts">buy us a coffee</a>.</span>
</span>
</div><!--wrap-->