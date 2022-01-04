<?php
// single-news.php
get_header();
/* Start the Loop */
while (have_posts()) : the_post();
   ?>
   
<div class="qa-title"><?php the_title(); ?></div>
	<div class="qa-question">
         <?php the_post_thumbnail('full'); ?>
         <?php the_content();?>
		<?php $author_id=$post->post_author; ?>
<?php the_author_meta( 'user_nicename' , $author_id ); ?> 
</div>

<?php
endwhile; // End of the loop.
get_footer();