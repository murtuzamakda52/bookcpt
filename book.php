<?php 
/**
* Plugin Name: book
* Plugin URI: https://test-projext.000webhostapp.com/
* Description: This is the very first plugin I ever created and this is a unique plugin because using .
* Version: 1.0
* WC tested up to: 5.8.2
* Author: Murtuza Makda(idrish)
* Author URI: https://www.upwork.com/freelancers/~018f06972fe4607ad0
*License: GPL v3
* License URI: https://www.gnu.org/licenses/gpl-3.0.html
**/


function ajax_filter_posts_scripts() {
  wp_register_style('post-style', plugins_url('/assets/wp-post.css',__FILE__));
  wp_enqueue_style('post-style');
  wp_register_script('afp_script', plugins_url('/assets/ajax-filter-posts.js',__FILE__),array('jquery'), '', true);
  wp_enqueue_script('afp_script');

  wp_localize_script( 'afp_script', 'afp_vars', array(
        'afp_nonce'     => wp_create_nonce( 'afp_nonce' ), // Create nonce which we later will use to verify AJAX request
        'afp_ajax_url'  => admin_url( 'admin-ajax.php' ),
      )
  );
}
add_action('wp_enqueue_scripts', 'ajax_filter_posts_scripts', 100);


add_action('init', 'create_custom_post_type_book');
add_action('init','add_publisher_taxonomy_to_post');
add_action('init','add_author_taxonomy_to_post');





add_filter('use_block_editor_for_post_type', 'prefix_disable_gutenberg_for_book', 10, 2);
function prefix_disable_gutenberg_for_book($current_status, $post_type)
{
    if ($post_type === 'book') return false;
    return $current_status;
}

 
function create_custom_post_type_book() {
$supports = array(
'title', // post title
'editor', // post content
'author', // post author
'thumbnail', // featured images
'excerpt', // post excerpt
'custom-fields', // custom fields
'comments', // post comments
'revisions', // post revisions
'post-formats', // post formats
);
 
$labels = array(
'name'           => _x('Books', 'plural'),
'singular_name'  => _x('Book', 'singular'),
'menu_name'      => _x('Books', 'admin menu'),
'name_admin_bar' => _x('Book', 'admin bar'),
'add_new'        => _x('Add Book', 'add new'),
'add_new_item'   => __('Add New Book'),
'new_item'       => __('New Book'),
'edit_item'      => __('Edit Book'),
'view_item'      => __('View Book'),
'all_items'      => __('All Book'),
'search_items'   => __('Search Book'),
'not_found'      => __('No Book found.'),
);
 
$args = array(
'supports'          => $supports,
'labels'            => $labels,
'description'       => 'Holds our book and specific data',
'public'            => true,
'taxonomies'        => array( 'Authors', 'Publishers' ),
'show_ui'           => true,
'show_in_menu'      => true,
'show_in_nav_menus' => true,
'show_in_admin_bar' => true,
'can_export'        => true,
'capability_type'   => 'post',
'show_in_rest'      => true,
'query_var'         => true,
'rewrite'           => array('slug' => 'book'),
'has_archive'       => true,
'hierarchical'      => false,
'menu_position'     => 6,
'menu_icon'         => 'dashicons-book',
);
 
register_post_type('book', $args);
}

//create a function that will attach our new 'member' taxonomy to the 'post' post type
function add_publisher_taxonomy_to_post(){

    //set the name of the taxonomy
    $taxonomy1 = 'Publishers';
    //set the post types for the taxonomy
    $object_type1 = 'book';
    
    //populate our array of names for our taxonomy
    $labels = array(
        'name'               => 'Publishers',
        'singular_name'      => 'Publishers',
        'search_items'       => 'Search Publishers',
        'all_items'          => 'All Publishers',
        'parent_item'        => 'Parent Publishers',
        'parent_item_colon'  => 'Parent Publishers:',
        'update_item'        => 'Update Publishers',
        'edit_item'          => 'Edit Publishers',
        'add_new_item'       => 'Add New Publishers', 
        'new_item_name'      => 'New Publishers Name',
        'menu_name'          => 'Publishers'
    );
    
    //define arguments to be used 
    $args1 = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'show_ui'           => true,
        'how_in_nav_menus'  => true,
        'public'            => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'publisher')
    );
    
    //call the register_taxonomy function
    register_taxonomy($taxonomy1, $object_type1, $args1); 
}


function add_author_taxonomy_to_post(){

    //set the name of the taxonomy
    $taxonomy2 = 'Authors';
    //set the post types for the taxonomy
    $object_type2 = 'book';
    
    //populate our array of names for our taxonomy
    $labels = array(
        'name'               => 'Authors',
        'singular_name'      => 'Authors',
        'search_items'       => 'Search Authors',
        'all_items'          => 'All Authors',
        'parent_item'        => 'Parent Authors',
        'parent_item_colon'  => 'Parent Authors:',
        'update_item'        => 'Update Authors',
        'edit_item'          => 'Edit Authors',
        'add_new_item'       => 'Add New Authors', 
        'new_item_name'      => 'New Authors Name',
        'menu_name'          => 'Authors'
    );
    
    //define arguments to be used 
    $args2 = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'show_ui'           => true,
        'how_in_nav_menus'  => true,
        'public'            => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'author')
    );
    
    //call the register_taxonomy function
    register_taxonomy($taxonomy2, $object_type2, $args2); 
}

add_filter( 'template_include', 'my_book_templates' );
function my_book_templates( $template ) {
    $post_types = array( 'book' );

    if ( is_post_type_archive( $post_types ) && file_exists( plugin_dir_path(__FILE__) . 'template-book.php' ) ){
        $template = plugin_dir_path(__FILE__) . 'template-book.php';
    }

    if ( is_singular( $post_types ) && file_exists( plugin_dir_path(__FILE__) . 'single-book.php' ) ){
        $template = plugin_dir_path(__FILE__) . 'single-book.php';
    }
    return $template;
}

function template_chooser($template)   
{    
  global $wp_query;   
  $post_type = get_query_var('post_type');   
  if( $wp_query->is_search && $post_type == 'book' )   
  {
    return plugin_dir_path(__FILE__) . 'archive-search.php';  //  redirect 
  }   
  return $template;   
}
add_filter('template_include', 'template_chooser'); 


add_action('admin_init','add_metabox_post_price_widget');
add_action('save_post','save_metabox_post_price_widget');
/*
* Funtion to add a meta box to enable Answer widget on posts.
*/
function add_metabox_post_price_widget()
{
  add_meta_box("book_price", "Price", "enable_post_price_widget", "book", "normal", "high"); /* replace "post" with your custom post value(eg: "motors") */
}

function enable_post_price_widget(){
  global $post;
  $image=get_post_custom($post->ID );
  $price = $image ? $image['price'][0] : '';
  echo "<input type='number' name='price' value='".$price."' required>";
}

/*
* Save the meta box value of Answer widget on posts.
*/
function save_metabox_post_price_widget($post_id)
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    $price = isset($_POST['price']) ? $_POST['price']:'';

    update_post_meta( $post_id, 'price', $price );
}




function searchform( ) {

   
    return include(plugin_dir_path(__FILE__) . 'template-book.php');
}

add_shortcode('search','searchform');


function ajax_filter_get_posts( $author ) {

  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

  // Verify nonce
  if( !isset( $_POST['afp_nonce'] ) || !wp_verify_nonce( $_POST['afp_nonce'], 'afp_nonce' ) )
    die('Permission denied');

  $authors    = $_POST['author'];
  $publishers = $_POST['publishers'];
  $page_no    = $_POST['page'];
  $search     = $_POST['search'];
  // WP Query
  $args = array(
    'Authors'       => $authors,
    'Publishers'    => $publishers,
    'post_type'     => 'book',
    'posts_per_page' => 6,
    'order'         =>'ASC',
    'paged'         => $page_no,
    's'             => $search
  );
  
  // If taxonomy is not set, remove key from array and get all posts
  if( !$authors ) {
    unset( $args['tag'] );
  }

  $query = new WP_Query( $args );



  if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
    <div class="single-portfolio" data-page="<?php echo $page_no; ?>" data-maxpage="<?php echo $query->max_num_pages; ?>">
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php the_excerpt(); ?>
     <?php $value=get_post_custom($post->ID );
           $price = $value['price'][0];
           echo 'Rs <span class="price">'.$price.'</span>';
           ?>
    </div>

  <?php endwhile; ?>
  
  <?php else: ?>
    <h2 class="center-text">No posts found</h2>
  <?php endif;
  die();
}

add_action('wp_ajax_filter_posts', 'ajax_filter_get_posts');
add_action('wp_ajax_nopriv_filter_posts', 'ajax_filter_get_posts');



?>