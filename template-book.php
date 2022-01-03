<?php get_header(); ?>
<div class="main-element">
  <div class="left" style="background-color:#aaa;">
  <input type="text" name="string" id="string"><input type="button" value="search" id="search"><br>
    <input type="range" min="0" step="10" id="price_range">
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
            echo "<h3 class='post-tags'>Author</h3><br><h4 class='post-tags'>No Author found <h4><br>";
        }
        ?>
	  
  </div>
  <div class="middle" style="background-color:#ccc;">
    <?php
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $args = array(
            'post_type' => 'book',
            'posts_per_page' => 6,
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
           echo 'Rs <span class="price">'.$price.'</span>';
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