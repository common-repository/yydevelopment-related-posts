
<script>
jQuery(document).ready(function($){


    // ==================================================
   // Confirm before data from the database
   // ==================================================
   
    $(".remove-form").click(function() {
        if (confirm("Are you sure you want to permanently remove this data?"))
            return true;
        else
            return false;
    }) ;

    // ================================================
    // Script load the media library when upload image
    // ================================================

    $(document).on('click', '.button-image-upload', function(e) {
        
        var inputIMGvalueChange = "input#" + $(this).attr('id'); // Getting the id that and it's the same as the id tag for the text input for the image path
        var changeImagePath = "img#" + $(this).attr('id'); // changing the page of the image so it will update the new image instead
        
        e.preventDefault();
        var image = wp.media({
        title: 'Upload Image',
        // mutiple: true if you want to upload multiple files at once
        multiple: false
        }).open()
        .on('select', function(e){

            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url; // getting the url info
            var image_alt = uploaded_image.toJSON().alt; // getting the image all tag

            // Let's assign the url value to the input field
            $( changeImagePath ).attr('src', image_url);
            $( inputIMGvalueChange ).val(image_url);


        }); // $(document).on('click', '.button-image-upload', function(e) {
    }); /// $('.button-image-upload').click(function(e) {

    // =========================================================
    // removing element from the datbase with ajax
    // =========================================================

    $(document).on('click', '.remove-this-box-block', function() {

        var confirm_removing_post = confirm("Are you sure you want to remove this related post?\nThe data you are about to delete will be lost forever!");

        if (confirm_removing_post == true) {

            // getting the related post id in the database
            var relatedPostDbID = $(this).attr('id'); 
            // the main div class for the box element
            var relatedPostBox = ".related-post-block-" + relatedPostDbID;

            // define the function name and creating the post data
            var data = {
                'action': 'yy_remove_related_post_from_db',
                'post_id': relatedPostDbID
            }; // var data = {

            // starting the ajax request
            jQuery.post(
                ajaxurl, // url for wordpress ajax file
                data, // the data we transfer with ajax to the php function
                function(response) {
                    
                    // outputing the code when   ajax request is done, 
                    $( relatedPostBox ).animate({opacity: "0"}, 500, function() {
                        $( relatedPostBox ).remove();
                    }); // $( relatedPostBox ).animate({opacity: "0"}, 1000, function() {

                    // response = is the html code we get back from the php code that runing
                    // alert(response);

                } // function(response) {
            ); // jQuery.post(

        } // if (confirm_removing_post == true) {
        
        return false;

    }); // $(document).on('click', '.remove-this-box-block', function() {
    // =========================================================
    // removing the image when someone click on the image close button
    // =========================================================

    $(document).on('click', 'a.remove-this-box-block-image', function() {

        // getting the related post id in the database
        var relatedPostDbID = $(this).attr('id'); 
        // the main div class for the box element
        var relatedPostBox = ".related-post-block-" + relatedPostDbID;
        <?php $related_post_thumbnail = plugins_url( 'images/add-image.png', dirname(__FILE__) ); ?>

        // changing the image url into the place holder url
        $( relatedPostBox ).find(".related-post-image-display").attr("src", "<?php echo $related_post_thumbnail; ?>");
        $( relatedPostBox ).find(".related_post_image_url").attr("value", "<?php echo $related_post_thumbnail; ?>");
        
        return false;

    }); // $(document).on('click', '.remove-this-box-block', function() {

    // =========================================================
    // hidding the new related box if removed
    // =========================================================

    $(document).on('click', '.remove-this-related-insert-post', function() {
        
        $(".insert-related-post-id-box").animate({opacity: "0"}, 200, function() {
            $(".insert-related-post-id-box").css({display: "none", opacity: "1"});
            $(".insert-related-post-id-box").html("");
        }); // $( relatedPostBox ).animate({opacity: "0"}, 1000, function() {

        return false;
    }); // $(document).on('click', '.remove-this-related-insert-post', function() {

    // =========================================================
    // inserting new related box if the post id was inserted
    // =========================================================

    $(document).on('click', '.insert-post-button', function() {

        var imageLoader = "<img src='<?php echo plugins_url( 'images/loader.gif', dirname(__FILE__) ); ?>' alt='' />";
        var originalPostID = $("[name=original_post_id]").val();
        var PostParentID = $("[name=this_related_parent_post_id]").val();
        var loadDescriptionType = $("[name=yy_load_description_type]").val();

        // inserting loader image while loading ajax
        // $(".insert-related-post-id-box").css("display", "block");
        // $(".insert-related-post-id-box").html(imageLoader);
        $(".yy-related-update-new-insert-box").css("display", "block");
        $(".yy-related-update-new-insert-box").html(imageLoader);

            // define the function name and creating the post data
            var data = {
                'action': 'yy_insert_new_related_post_data',
                'original_post_id': originalPostID,
                'post_parent_id': PostParentID,
                'loadDescriptionType': loadDescriptionType
            }; // var data = {

            // starting the ajax request
            jQuery.post( ajaxurl, data, 
                function(response) {

                    $(".yy-related-update-new-insert-box").html(response);
                    var yyRelatedErrorCheck = $(".yy-reload-posts-error").text();

                    // if there is a response that mean that there was an error and we check that
                    if( !(yyRelatedErrorCheck) ) {
                        yy_insert_post_into_database();
                    } // if( !(yyRelatedErrorCheck) ) {

                } // function(response) {
            ); // jQuery.post(

        return false;
    }); // $(document).on('click', '.insert-post-button', function() {

    // =========================================================
    // inserting new related post into the database with ajax
    // =========================================================

    function yy_insert_post_into_database() {

            var related_parent_post_id = $("[name=insert_related_parent_post_id]").val();
            var original_post_id = $("[name=insert_original_post_id]").val();
            var related_post_url = $("[name=insert_related_post_url]").val();
            var related_post_title = $("[name=insert_related_post_title]").val();
            var related_post_image_url = $("[name=insert_related_post_thumbnail]").val();
            var related_post_image_alt = $("[name=insert_related_post_thumbnail_alt]").val();
            var related_post_description = $("[name=insert_related_post_description]").val();
            var related_post_hide = $("[name=hide_related_post]").val();

                // define the function name and creating the post data
                var data = {
                    'action': 'yy_yy_insert_related_post_to_database',
                    'related_parent_post_id': related_parent_post_id,
                    'original_post_id': original_post_id,
                    'related_post_url': related_post_url,
                    'related_post_title': related_post_title,
                    'related_post_image_url': related_post_image_url,
                    'related_post_image_alt': related_post_image_alt,
                    'related_post_description': related_post_description,
                    'related_post_hide': related_post_hide,
                }; // var data = {

                // starting the ajax request
                jQuery.post( ajaxurl, data, 
                    function(response) {

                            // reloading all the related posts again
                            yy_reload_just_one_posts();

                            // hiding the new related post once i have inserted the post to the database
                            $(".insert-related-post-id-box .related-post-hidden-box").animate({opacity: "0"}, 200, function() {
                            $(".insert-related-post-id-box .related-post-hidden-box").css({display: "none", opacity: "1"});
                            $(".insert-related-post-id-box .related-post-hidden-box").html("");
                            $(".yy-related-update-new-insert-box").css("display", "none");

                        }); // $( relatedPostBox ).animate({opacity: "0"}, 1000, function() {
                    } // function(response) {
                ); // jQuery.post(

            return false;

    } // function yy_insert_post_into_database() {

    // =========================================================
    // updating the related posts to the database when 
    // clicking on the update button
    // =========================================================

    $(document).on('click', '.related-posts-update-db-data-btn', function() {


        // this function will create an array with the related post info
        function getting_value_for_related_post(name) {
            var value = [];
            $(".related-post-block [name='" + name + "[]']").each(function() {
                value.push( $(this).val() ); // createing an array with the values
            });

            return value;
        } // function getting_value_for_related_post () {


        var imageLoader = "<img src='<?php echo plugins_url( 'images/loader.gif', dirname(__FILE__) ); ?>' alt='' />";
        $(".related-posts-update-ajax").css("display", "block");
        $(".related-posts-update-ajax").html(imageLoader);
        
            // creating the data we are going to transfer into the page that update the data
            var related_parent_post_id = getting_value_for_related_post('related_parent_post_id');
            var post_id = getting_value_for_related_post('post_id');
            var original_post_id = getting_value_for_related_post('original_post_id');
            var related_post_url = getting_value_for_related_post('related_post_url');
            var related_post_title = getting_value_for_related_post('related_post_title');
            var related_post_image_url = getting_value_for_related_post('related_post_image_url');
            var related_post_image_alt = getting_value_for_related_post('related_post_image_alt');
            var related_post_description = getting_value_for_related_post('related_post_description');
            var related_post_position = getting_value_for_related_post('related_post_position');
            var related_post_hide = getting_value_for_related_post('related_post_hide');

            // define the function name and creating the post data
            var data = {
                'action': 'yy_updating_related_post_to_database',
                'related_parent_post_id': related_parent_post_id,
                'post_id': post_id,
                'original_post_id': original_post_id,
                'related_post_url': related_post_url,
                'related_post_title': related_post_title,
                'related_post_image_url': related_post_image_url,
                'related_post_image_alt': related_post_image_alt,
                'related_post_description': related_post_description,
                'related_post_position': related_post_position,
                'related_post_hide': related_post_hide
            }; // var data = {

            // starting the ajax request
            jQuery.post( ajaxurl, data, 
                function(response) {

                    $(".related-posts-update-ajax").html(response);
                    yy_reload_all_related_posts();

                } // function(response) {
            ); // jQuery.post(


        return false;
    }); // $(document).on('click', '.related-posts-update-db-data-btn', function() {

    // =========================================================
    // Reloading all related posts if new one was inserted into the database
    // =========================================================

    function yy_reload_all_related_posts() {

            var relatedParentID = $("[name=this_related_parent_post_id]").val();

            // define the function name and creating the post data
            var data = {
                'action': 'yy_reloading_all_related_post',
                'this_related_parent_post_id': relatedParentID
            }; // var data = {

            // starting the ajax request
            jQuery.post( ajaxurl, data, 
                function(response) {
                $(".related-post-plugin").html(response);

                } // function(response) {
            ); // jQuery.post(

    } // function yy_reload_all_related_posts() {

    // =========================================================
    // Reloading the last related post if new one was inserted into the database
    // =========================================================

    function yy_reload_just_one_posts() {

            var relatedParentID = $("[name=this_related_parent_post_id]").val();

            // define the function name and creating the post data
            var data = {
                'action': 'yy_reloading_all_related_post',
                'this_related_parent_post_id': relatedParentID,
                'reload_only_one_post': '1'
            }; // var data = {

            // starting the ajax request
            jQuery.post( ajaxurl, data, 
                function(response) {

                // loading the last post and the current posts showing on the html page
                $(".related-post-plugin").prepend(response);
                $(".related-post-block").eq(0).css("opacity", "0");
                $(".related-post-block").eq(0).animate({opacity: "1"}, 500);

                // readjusting the position number after inserting new post
                var positionNumber = 1;
                $("[name='related_post_position[]']").each(function() {
                    $(this).val(positionNumber);
                    positionNumber++;
                });


                } // function(response) {
            ); // jQuery.post(

    } // function yy_reload_just_one_posts() {

    // =========================================================
    // Copy data from another parent post
    // =========================================================

    $(document).on('click', '.copy_related_posts_btn', function() {

        var imageLoader = "<img src='<?php echo plugins_url( 'images/loader.gif', dirname(__FILE__) ); ?>' alt='' />";
        var copy_from_post_id = $("[name=copy_from_post_id]").val();
        var this_post_id_number = $("[name=this_post_id_number]").val();

        // inserting loader image while loading ajax
        // $(".insert-related-post-id-box").css("display", "block");
        // $(".insert-related-post-id-box").html(imageLoader);
        $(".update-copy_related-box").css("display", "block");
        $(".update-copy_related-box").html(imageLoader);

            // define the function name and creating the post data
            var data = {
                'action': 'yy_copy_related_posts_from_other_page',
                'copy_from_post_id': copy_from_post_id,
                'this_post_id_number': this_post_id_number
            }; // var data = {

            // starting the ajax request
            jQuery.post( ajaxurl, data, 
                function(response) {

                    $(".update-copy_related-box").html(response);
                    yy_reload_all_related_posts();

                } // function(response) {
            ); // jQuery.post(

        return false;
    }); // $(document).on('click', '.copy_related_posts_btn', function() {

});// jQuery(document).ready(function($) {





</script>