jQuery(document).ready(function($) {
	
	var loadmore = $('.single-portfolio').attr('data-maxpage');
                if(parseInt(loadmore) > 1)
                {
                   jQuery('#loadmore').css('display','block');
                }
                else{
                   jQuery('#loadmore').css('display','none');
                }
	
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
                var loadmore = $('.single-portfolio').attr('data-maxpage');
                if(parseInt(loadmore) > 1)
                {
                   jQuery('#loadmore').css('display','block');
                }
                else{
                   jQuery('#loadmore').css('display','none');
                }
                
            };
        });
    });


     $( document ).on( "click", "#loadmore", function() {

       var page_no = $('.single-portfolio').attr('data-page');

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
               var loadmore = $('.single-portfolio').attr('data-maxpage');
                if(parseInt(loadmore) > 1)
                {
                   jQuery('#loadmore').css('display','block');
                }
                else{
                   jQuery('#loadmore').css('display','none');
                }
            };
        });
    });


    jQuery('#search').click(function() {
       var search = $('#string').val();
       var page_no = $('.single-portfolio').attr('data-page');
        data = {
            action: 'filter_posts', // function to execute
            afp_nonce: afp_vars.afp_nonce, // wp_nonce
            search:search,
            page: parseInt(page_no)
            };
        $.post( afp_vars.afp_ajax_url, data, function(response) {
 
            if( response ) {
                // Display posts on page
                $('.tagged-posts').html( response );
                // Restore div visibility
                $('.tagged-posts').fadeIn();
               var loadmore = $('.single-portfolio').attr('data-maxpage');
                if(parseInt(loadmore) > 1)
                {
                   jQuery('#loadmore').css('display','block');
                }
                else{
                   jQuery('#loadmore').css('display','none');
                }
            };
        });
    });
	
	
	
	
	
	$('#string').bind('keyup', function(e){
   if((e.type === 'keyup' && e.which === 13) ){
     $( "#search" ).trigger( "click" );
   }
});
	
	



jQuery( document ).on( "change", ".range-meter", function(){
    var min_value = parseInt($('#min_range').val());
	var max_value = parseInt($('#max_range').val());
	$('.single-portfolio').show();
    $('.price').each(function(){
       var price_range = parseInt($(this).text());
       $(this).closest('.single-portfolio').show();
       if(price_range >= min_value   && price_range >= max_value)
{
		console.log('run');
		console.log('value');
		console.log(price_range);
		console.log($(this).closest('.single-portfolio'));
		$(this).closest('.single-portfolio').hide();
		jQuery('#loadmore').hide();
}
       });
});

});









