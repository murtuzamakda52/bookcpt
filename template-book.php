<?php get_header(); 

	$min_value = esc_attr( get_option('min_range') ) ? esc_attr( get_option('min_range') ) : '0';
	$max_value = esc_attr( get_option('max_range') ) ? esc_attr( get_option('max_range') ) : '1000';


?>
<div class="main-element">
  <div class="left" style="background-color:#aaa;">
  <input type="text" name="string" id="string"><input type="button" value="search" id="search"><br>
	  
	  
	  
	   <div data-role="rangeslider">
        <label for="price-min">Price:</label>
        <input type="range" class="range-meter" name="price-min" id="min_range" value="200" min="<?php echo $min_value; ?>" max="<?php echo $max_value; ?>">
        <label for="price-max">Price:</label>
        <input type="range" class="range-meter" name="price-max" id="max_range" value="800" min="<?php echo $min_value; ?>" max="<?php echo $max_value; ?>">
      </div>
	  
	  
	  
	  <div style="display:flex"><input type="range" min="0" step="10" id="price_range"><div class="range"></div><div class="max-text">Max value : <span class="max-value"></span></div></div>
    <?php 
        $publishers = 'publisher';
        $terms = get_terms( $publishers );
        if($terms){
        $count = count( $terms );

        if ( $count > 0 ): ?>
            <div class="post-tags">
            <h3>Publishers</h3>
            <?php
            foreach ( $terms as $term ) {
                $term_link = get_term_link( $term );
                // echo '<a href="' . $term_link . '" class="tax-filter" title="' . $term->slug . '">' . $term->name . '</a> ';

                echo '<label>'. $term->name.'</label><input type="checkbox" value="'.$term->slug.'" class="publisher filter"><br>';
            } ?>
            </div>
        <?php endif; 

    }

    else{
        echo "<h3 class='post-tags'>Publishers</h3><br><h4 class='post-tags'>No Publishers found</h4><br><hr>";
    }
    ?>
	  
	  <?php $authors = 'auth';
        $terms = get_terms( $authors );
        if($terms){



        $count = count( $terms );

        if ( $count > 0 ): ?>
            <div class="post-tags">
            <h3>Authors</h3>
            <?php
            foreach ( $terms as $term ) {
                $term_link = get_term_link( $term );
                // echo '<a href="' . $term_link . '" class="tax-filter" title="' . $term->slug . '">' . $term->name . '</a> ';

                echo '<label>'. $term->name.'</label><input type="checkbox" value="'.$term->slug.'" class="author filter"><br>';
            } ?>
            </div>
        <?php endif; } 

        else{
            echo "<h3 class='post-tags'>Author</h3><br><h4 class='post-tags'>No Authors found <h4><br>";
        }
        ?>
	  
  </div>
  <div class="middle" style="background-color:#ccc;">
    <?php
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$post_per_pages = esc_attr( get_option('post_per_page') ) ? esc_attr( get_option('post_per_page') ) : '6';
    $args = array(
            'post_type' => 'book',
            'posts_per_page' => $post_per_pages,
            'order' =>'ASC',
            'paged' => $paged,
        );
        $query = new WP_Query( $args );
     if ( $query->have_posts() ): ?>
        <div class="tagged-posts">
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <div class="single-portfolio" data-page="1">
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php the_excerpt(); ?>
            <?php $value = get_post_custom(get_the_ID());
           $price = $value['price'][0];
           echo '₹ <span class="price">'.$price.'</span>';
           ?>
            </div>

            <?php endwhile; ?>
        </div>

        <center><input type="button" value="load more" id="loadmore"></center>

        <?php else: ?>
            <div class="tagged-posts">
                <h2>No posts found</h2>
            </div>
        <?php endif; ?> 
  </div>  
</div>
<?php get_footer(); ?>