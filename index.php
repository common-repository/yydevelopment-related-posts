<?php
/*
Plugin Name: YYDevelopment - Advanced Related Posts
Plugin URI:  https://www.yydevelopment.com/yydevelopment-wordpress-plugins/
Description: Simple plugin that allow you to add related posts to your articles & pages.
Version:     2.2.0
Author:      YYDevelopment
Author URI:  https://www.yydevelopment.com/
*/


include_once('include/settings.php');
require_once('include/functions.php');
require_once('include/ajax-functions.php');

$yy_related_posts_plugin_version = '2.0.0'; // plugin version
$yy_related_posts_slug_name = 'yydev_related_wordpress_boxes'; // the name we save on the wp_options database

// ================================================
// Creating Database when the plugin is activated
// ================================================

function yy_create_releated_posts_database() {
    
    require_once('include/install.php');
        
} // function yy_create_releated_posts_database() {

register_activation_hook(__FILE__, 'yy_create_releated_posts_database');

// ================================================
// update the database on plugin update
// ================================================

// loading the plugin version from the database
$db_plugin_version = get_option($yy_related_posts_slug_name);

// checking if the plugin version exists on the dabase
// and checking if the database version equal to the plugin version $yy_related_posts_plugin_version
if( empty($db_plugin_version) || ($yy_related_posts_plugin_version != $db_plugin_version) ) {

    // update the plugin database if it's required
    $yydev_redirect_database_update = 1;
    require_once('include/install.php');

    // update the plugin version in the database
    update_option($yy_related_posts_slug_name, $yy_related_posts_plugin_version);

} // if( empty($db_plugin_version) || ($yy_related_posts_plugin_version != $db_plugin_version) ) {

// add_action('plugins_loaded', 'my_awesome_plugin_check_version');


// ================================================
// display the plugin we have create on the wordpress
// post blog and pages
// ================================================

// function that will output the code to the page
function yydev_output_wordpress_related_posts_plugin() {

    include('include/style.php');
    include('include/script.php');
    include('include/admin-output.php');

} // function yy_output_wordpress_related_posts_plugin() {


function yydev_register_related_posts_meta_boxes() {
    add_meta_box( 'yy_related_posts-admin', 'YYDevelopment Related Posts', 'yydev_output_wordpress_related_posts_plugin');
} // function yy_wpdocs_register_related_posts_meta_boxes() {

add_action( 'add_meta_boxes', 'yydev_register_related_posts_meta_boxes' );


// ================================================
// function that will insert the code to the datbase
// once the post or page is updated
// ================================================

function yy_insert_related_posts_to_database() {

        include('include/insert-to-db.php');

} // function yy_insert_related_posts_to_database() {

add_action('pre_post_update', 'yy_insert_related_posts_to_database');

// ================================================
// Creating wordpress admin panel page
// ================================================

function yy_related_posts_page() {

    include('include/settings.php');

    include('include/style.php');
    include('include/script.php');
    
    // Including the main page and the secondary page
    if( isset($_GET['view']) && isset($_GET['id']) && ($_GET['view'] = 'secondary') ) {
        include('include/secondary-page.php');
    } else {
        include('include/main-page.php');
    }
    
} // function yy_related_posts_page() {

// ================================================
// Creating wordpress admin plugin settings page
// ================================================

function yy_related_posts_page_settings() {

    include('include/settings.php');

    include('include/style.php');
    include('include/script.php');
    
    // Including the main page and the secondary page
    include('include/admin-settings-page.php');
    
} // function yy_related_posts_page_settings() {

// ================================================
// Adding menu into wordpress
// ================================================

function yy_related_posts_plugin_menu() {

    $wordpress_icon_path = plugins_url( 'images/favicon.png', __FILE__ );

    // adding the main menu page
    add_menu_page( 'Related Posts', 'Related Posts', 'manage_options', 'yydev-related-posts', 'yy_related_posts_page',  $wordpress_icon_path, 500);

    // creating settings page
    add_submenu_page( 'yydev-related-posts', 'Settings', 'Settings', 'manage_options', 'yydev-related-posts-settings', 'yy_related_posts_page_settings');

} // function yy_related_posts_plugin_menu() {

add_action('admin_menu', 'yy_related_posts_plugin_menu');

// ================================================
// Add settings page to the plugin menu info
// ================================================

function yy_related_posts_add_settings_link( $actions, $plugin_file ) {

	static $plugin;

    if (!isset($plugin)) { $plugin = plugin_basename(__FILE__); }

	if ($plugin == $plugin_file) {

            $admin_page_url = esc_url( menu_page_url( 'yydev-related-posts', false ) );
			$settings = array('settings' => '<a href="' . $admin_page_url . '">Settings</a>');
            $donate = array('donate' => '<a target="_blank" href="https://www.yydevelopment.com/coffee-break/?plugin=yydevelopment-related-posts">Donate</a>');
            $actions = array_merge($settings, $donate, $actions);

    } // if ($plugin == $plugin_file) {

    return $actions;

} //function yy_related_posts_add_settings_link( $actions, $plugin_file ) {

add_filter( 'plugin_action_links', 'yy_related_posts_add_settings_link', 10, 5 );

// ================================================
// Output the shortcode and the template tags
// ================================================

include('front-end/output.php');

// ================================================
// Register Style on the front end
// ================================================

function yy_register_related_posts_css( $content ) {
    wp_register_style( 'yy-related-posts', plugins_url( '/front-end/related-posts.css', __FILE__ ) );
    wp_enqueue_style('yy-related-posts');
}

add_action('wp_enqueue_scripts', 'yy_register_related_posts_css');

// ================================================
// including admin notices flie
// ================================================

if( is_admin() ) {
	include_once('notices.php');
} // if( is_admin() ) {