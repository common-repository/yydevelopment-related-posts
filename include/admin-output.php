<?php

    include('settings.php');

    $this_post_id = get_the_ID(); // getting the id of the parent post

    // checking if it's global page and it's we will load the id 
    // of the parent page from secondary-page.php
    if( isset($global_page_id) ) {
        $this_post_id = $global_page_id;
    } // if( isset($global_page_id) ) {
    
    $rtl_related_post_class = "";
    if( is_rtl() ) {
        $rtl_related_post_class = "yy-related-posts-rtl";
    } // if( is_rtl() ) {

?>

<div class='insert-new-post-form'>
    <label for='original_post_id'>Add New Post ID:</label>
    <input type='text' id="original_post_id" class='related-post-input short-post-input' name='original_post_id' />
    <input type='hidden' name='this_related_parent_post_id' value='<?php echo $this_post_id; ?>' />
    <a href='#' class='insert-post-button'>Add New Related Post</a>

    <div class='yy_load_description_type'>
        <label for="yy_load_description_type">Load posts description from:</label>
        <select id="yy_load_description_type" name='yy_load_description_type'>
            <option value="post" value="post" <?php if($yy_related_data_array['load_description_type'] === "post") {echo "selected";} ?> >Load From Post</option>
            <option value="yoast" <?php if($yy_related_data_array['load_description_type'] === "yoast") {echo "selected";} ?> >Load From Yoast</option>
            <option value="empty" <?php if($yy_related_data_array['load_description_type'] === "empty") {echo "selected";} ?> >Leave Blank</option>
        </select>
    </div><!--load_yoast_description-->
</div><!--insert-new-post-form-->

    <?php // the content here is getting loaded with ajax ?>
    <div class='insert-related-post-id-box'></div>
    <div class="yy-related-update-new-insert-box"></div>

<div class='related-post-plugin <?php echo $rtl_related_post_class; ?>'>

<?php            
        // ================================================
        // Loading all the posts from the database
        // ================================================
        include("related-post-loader.php");
?>

    <div class='clear'></div>
</div><!--related-post-plugin-->

<div class='clear'></div>

<div class="related-posts-update-db-data">
    <a class="related-posts-update-db-data-btn" href="#">UPDATE DATA IN DATABASE</a>
    <div class="related-posts-update-ajax"></div>
</div><!--related-posts-update-db-data-->

<div class='related-posts-shortcode'>
Shortcode: <span><input type='text' name='related_posts_shortcode' value='[yy-wordpress-related-posts page_id="<?php echo $this_post_id; ?>"]' /></span>
</diV><!--related-posts-shortcode-->

<div class='insert-copy-post-form insert-new-post-form'>
    <label for='copy_from_post_id'>Copy related posts from parent post id:</label>
    <input type='text' id="copy_from_post_id" class='related-post-input short-post-input' name='copy_from_post_id' />
    <input type='hidden' name='this_post_id_number' value='<?php echo $this_post_id; ?>' />
    <a href='#' class='copy_related_posts_btn'>Copy Related Post</a>
</div><!--insert-copy-post-form-->
<div class="update-copy_related-box"></div>
