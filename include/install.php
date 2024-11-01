<?php

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); // Require to use dbDelta
    include('settings.php'); // Load the files to get the databse info

    if( $wpdb->get_var("SHOW TABLES LIKE '{$yy_related_main_table_name}' ") != $yy_related_main_table_name ) {
        // The table we want to create doesn't exists
       
        $sql = "CREATE TABLE " . $yy_related_main_table_name . "( 
        id INTEGER(11) UNSIGNED AUTO_INCREMENT,
        name VARCHAR (500),
        slug VARCHAR (500),
        PRIMARY KEY (id) 
        ) $charset_collate;";
        
        dbDelta($sql);
        
    }  // if( $wpdb->get_var("SHOW TABLES LIKE '{$yy_related_main_table_name}' ") != $yy_related_main_table_name ) {


    if( $wpdb->get_var("SHOW TABLES LIKE '{$yy_related_secondary_table_name}' ") != $yy_related_secondary_table_name ) {
        // The table we want to create doesn't exists
       
        $sql = "CREATE TABLE " . $yy_related_secondary_table_name . "( 
        id INTEGER(11) UNSIGNED AUTO_INCREMENT,
        post_parent_id VARCHAR(11),
        original_post_id INTEGER(11),
        page_url TEXT,
        title TEXT,
        image_url TEXT,
        image_alt TEXT,
        description TEXT,
        position FLOAT,
        hide_box TINYINT(1),
        PRIMARY KEY (id) 
        ) $charset_collate;";
        
        dbDelta($sql);
        
       
    }  // if( $wpdb->get_var("SHOW TABLES LIKE '{$yy_related_secondary_table_name}' ") != $yy_related_secondary_table_name ) {

// if the plugin change version and require to add database fields
if( isset($yydev_redirect_database_update ) ) {

    // ============================================================
    // Dealing with the plugin database updates for new versions
    // ============================================================

    // creating an array with all the columns from the database
    $existing_columns = $wpdb->get_col("DESC {$yy_related_secondary_table_name}", 0);

    
    if($existing_columns) {

            // -------------------------------------------------------------
            // update the database for plugin version 1.1
            // -------------------------------------------------------------

            // adding new column
            $new_db_column = 'hide_box';
            if( !in_array($new_db_column, $existing_columns) ) {
                // create the date column on the database
                $wpdb->query("ALTER TABLE $yy_related_secondary_table_name ADD $new_db_column TINYINT(1) NOT NULL");
            } // if( in_array($new_db_column, $existing_columns) ) {

            // changing current column to VARCHAR
            $new_db_column = 'post_parent_id';
            if( in_array($new_db_column, $existing_columns) ) {
                // create the date column on the database
                $wpdb->query("ALTER TABLE $yy_related_secondary_table_name CHANGE $new_db_column $new_db_column VARCHAR(11) NULL DEFAULT NULL;");
            } // if( in_array($new_db_column, $existing_columns) ) {

    } // if($existing_columns) {

} // if( isset($yydev_redirect_database_update ) ) {



// ----------------------------------------------
// dealing the the settings page options
// ----------------------------------------------    

if( !get_option($wp_options_name) ) {

    // ----------------------------------------------
    // getting all the values and clear data
    // ----------------------------------------------    

    $display_thumbnail_on_page = 1;
    $display_description_on_page = 1;
    $load_description_type = 'post';

    // ----------------------------------------------
    // insert the data into an array
    // ----------------------------------------------  

    $plugin_data_array = array(
        'display_thumbnail_on_page' => $display_thumbnail_on_page,
        'display_description_on_page' => $display_description_on_page,
        'load_description_type' => $load_description_type,
    ); // $creating_data_array = array(

    // ----------------------------------------------
    // creating a value with all the array data
    // ----------------------------------------------  
    
    $array_key_name = "";
    $array_item_value = "";
    
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

} // if( !get_option($wp_options_name) ) {