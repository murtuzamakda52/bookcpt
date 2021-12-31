<?php
get_header();


global $wp_query;
if (  $wp_query->max_num_pages > 1 ) : ?>

   <button id="load-more" class="btn">Load More</button>

<?php endif;

    
get_footer();
?>

 