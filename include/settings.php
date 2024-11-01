<?php

global $wpdb;
$yy_related_main_table_name = $wpdb->prefix . "yy_related_post"; // Main Database Table Name
$yy_related_secondary_table_name = $wpdb->prefix . "yy_related_posts"; // Secondary database Table Name
$wp_admin_page_name = "YYDevelopment Related Posts"; // Define the name that will show up on the wordpress admin menu panel
$wp_admin_url_path_name = "yy-related-posts"; // The url that will show up on the plugin name it will be admin.php?page=wp_admin_url_path_name
$plugin_full_name = "YYDevelopment Related Posts"; // Full name to defind the plugin (output for the plugin admin content)
$plugin_main_name = "Related Posts"; // The main single name that define the plugin (output for the plugin admin content)
$output_class_name_prefix = "wordpress_related_posts_"; // This will be the prefix for the class name that will output to the page
$wp_options_name = "yydev_related_posts_settings"; // the option settings name on the database

 // The main name that will show up when you output the code [shortcode_ouput_name]
 // We have to manually change the code from the page output.php
 // We will take the value of $shortcode_ouput_name and replace it with the new value on output.php
$shortcode_ouput_name = "yy_wordress-related-posts-shortcode-output";

// The name of the function that allow to output the code using php inside wordpress files
// We have to manually change the code from the page output.php
 // We will take the value of $php_function_name and replace it with the new value on output.php
$php_function_name = "yy_wordpress_related_posts_shortcode_output_function";


// Important:
// We also need to change the function on function.php and make sure there is not function with the same name for mysql_prep

// ================================================
// Getting the plugin settings data
// ================================================

// ----------------------------------------------
// breaking the string into to 2 variables. the array namd and vakue  
// ----------------------------------------------  

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
        	$yy_related_data_array[ $item_name[$count_number] ] = $key_name[$count_number];
        } // for($count_number = 0; $count_number < $array_count; $count_number++) {

    } // if( !empty($getting_plugin_data) ) {

?>