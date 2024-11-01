<?php

// ==================================================================
// output the values into the the page or input in the correct way
// allowing to have double and single quotes inside input
// ==================================================================

function yydev_related_html_value($output_code) {

    $output_code = stripslashes_deep($output_code);
    $output_code = esc_html($output_code);
    return $output_code;

} // function yydev_related_html_value($output_code) {

// ==================================================================
// output the values into the the page or input in the correct way
// allowing to have double and single quotes ", ' inside input
// use $echo = 1 if you want to echo the ouput yy_related_posts_output_string_value($string, 1)
// ==================================================================

function yy_related_posts_output_string_value($output_code, $echo = 0) {

    $output_code = stripslashes_deep($output_code);
    $output_code = esc_html($output_code);

    if($echo == 1) {
        echo $output_code;
    } else { // if($echo == 1) {
        return $output_code;
    } // } else { // if($echo == 1) {
    

} // function yy_related_posts_output_string_value($output_code) {

// ==================================================================
// This function will display error message if there was something wrong
// $error_message will be the name of the string we define and if it's exists
// it will echo the message to the page
// if $display_inline is set to 1 it will have style of display: inline
// ==================================================================

function yy_show_related_posts_data_submit_form_error_message($error_message, $display_inline = "") {
    
    if($display_inline == 1) {
        $display_inline_echo = "display-inline";
    } // if($display_inline == 1) {
    
    if( isset($error_message) ) {
        ?>
        
        <div class="output-data-error-message <?php echo $display_inline_echo; ?>">
            <?php echo $error_message; ?>
        </div>
        
        <?php
    } // if( isset($error) ) {
    
} // function yy_show_related_posts_data_submit_form_error_message($error) {

// ==================================================================
// Cehcking if the checkbox is already set
// ==================================================================

function yydev_related_checkbox_isset($post_value) {
    
    $checkbox_value = '';

    if( isset( $_POST[$post_value] ) ) {
        $checkbox_value = intval($_POST[$post_value]);
    } // if( isset( $_POST[$post_value] ) ) {

    return $checkbox_value;
    
} // function yydev_related_checkbox_isset($error) {

// ================================================
// Echoing Message if it's exists 
// ================================================

function yy_echo_related_posts_data_message_if_exists() {
    
    if(isset($_GET['message'])) {
        echo "<div class='output-messsage'> " . htmlentities($_GET['message']) . " </div>";
    } // if(isset($_GET['message'])) {
    
    if(isset($_GET['error-message'])) {
        echo "<div class='error-messsage'><b>Error:</b> " .  htmlentities($_GET['error-message']) . " </div>";
    } // if(isset($_GET['error-message'])) {

} // function yy_echo_related_posts_data_message_if_exists() {


function yy_related_posts_echo_success_message_if_exists($success) {

    if(isset($success) && !empty($success) ) {
        echo "<div class='output-messsage'> " . htmlentities($success) . " </div>";
    } // if(isset($success) && !empty($success) ) {

} // function yy_related_posts_echo_success_message_if_exists($success) {


function yy_related_posts_link_echo_success_message_if_exists($success) {

    if(isset($success) && !empty($success) ) {
        echo "<div class='output-messsage'> " . $success . " </div>";
    } // if(isset($success) && !empty($success) ) {

} // function yydev_redirect_echo_success_message_if_exists($success) {


function yy_echo_related_posts_data_error_message_if_exists($error) {

    if(isset($error) && !empty($error) ) {
        echo "<div class='error-messsage'><b>Error:</b> " .  $error . " </div>";
    } // if(isset($_GET['error-message'])) {

} // function yy_echo_related_posts_data_error_message_if_exists() {


// ==================================================================
// redirect the page using the path you provided
// ==================================================================

function yy_related_posts_data_redirect_page($link) {
	header("Location: {$link}");
	exit;
} // function yy_related_posts_data_redirect_page($path) {
