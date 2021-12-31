jQuery(document).ready(function($) {

    var selecetd_author = [];
    var selecetd_publisher = [];
    jQuery('input:checkbox.filter').click(function() {
        if($(this).is(":checked")){
            if ($(this).hasClass("author")) {
                selecetd_author.push($(this).val());
            }
        
            if($(this).hasClass("publisher")){
                selecetd_publisher.push($(this).val());
            }
        }
        else{
            if ($(this).hasClass("author")) {
                selecetd_author.pop($(this).val());
            }

            if($(this).hasClass("publisher")){
                selecetd_publisher.pop($(this).val());
            }
        }
        // var selecetd_taxonomy = $(this).attr('title');
 
        // After user click on tag, fade out list of posts
        // $('.tagged-posts').fadeOut();
        data = {
            action: 'filter_posts', // function to execute
            afp_nonce: afp_vars.afp_nonce, // wp_nonce
            author: selecetd_author,
            publishers: selecetd_publisher,
            page:1
            };
        $.post( afp_vars.afp_ajax_url, data, function(response) {
 
            if( response ) {
                // Display posts on page
                $('.tagged-posts').html( response );
                // Restore div visibility
                $('.tagged-posts').fadeIn();
                
            };
        });
    });


     $( document ).on( "click", "#loadmore", function() {

       var page_no = $('.single-portfolio').attr('data-page');
       console.log(page_no);

        if ($(this).hasClass("author")) {
        selecetd_author.push($(this).val());
        }
        if($(this).hasClass("publisher")){
        selecetd_publisher.push($(this).val());
        }
        // After user click on tag, fade out list of posts
        data = {
            action: 'filter_posts', // function to execute
            afp_nonce: afp_vars.afp_nonce,
            author: selecetd_author,
            publishers: selecetd_publisher,
            page: parseInt(page_no)+1
            };
        $.post( afp_vars.afp_ajax_url, data, function(response) {
 
            if( response ) {
                // Display posts on page
                $('.tagged-posts').append( response );
                // Restore div visibility
                $('.tagged-posts').fadeIn();
                $('.single-portfolio').attr('data-page',parseInt(page_no)+1);
                
            };
        });
    });


    jQuery('#search').click(function() {
       var search = $('#string').val();
        data = {
            action: 'filter_posts', // function to execute
            afp_nonce: afp_vars.afp_nonce, // wp_nonce
            search:search
            };
        $.post( afp_vars.afp_ajax_url, data, function(response) {
 
            if( response ) {
                // Display posts on page
                $('.tagged-posts').html( response );
                // Restore div visibility
                $('.tagged-posts').fadeIn();
                
            };
        });
    });


 jQuery( document ).on( "change", "#price_range", function(){

    var finalvalue = parseInt($(this).val());

    $('.price').each(function(){
         console.log($(this).text());

       var price_range = parseInt($(this).text());
       $(this).closest('.single-portfolio').show();
       if(price_range < finalvalue)

        $(this).closest('.single-portfolio').hide();
       });
});

});









