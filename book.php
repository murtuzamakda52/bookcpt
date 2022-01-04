<?php 
/**
* Plugin Name: Book Cpt
* Plugin URI: https://test-projext.000webhostapp.com/
* Description: This is book cpt plugin, it comes with ajax search and filter functionlity. 
* Version: 1.0
* WC tested up to: 5.8.2
* Author: Murtuza Makda(idrish)
* Author URI: https://www.upwork.com/freelancers/~018f06972fe4607ad0
*License: GPL v3
* License URI: https://www.gnu.org/licenses/gpl-3.0.html
**/


function ajax_filter_posts_scripts() {
  wp_register_style('post-style', plugins_url('/assets/wp-post.css',__FILE__), array(), '2.0');
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
global $wp_rewrite; 
$wp_rewrite->flush_rules( true );
}

//create a function that will attach our new 'member' taxonomy to the 'post' post type
function add_publisher_taxonomy_to_post(){

    //set the name of the taxonomy
    $taxonomy1 = 'publisher';
    //set the post types for the taxonomy
    $object_type1 = 'book';
    
    //populate our array of names for our taxonomy
    $labels = array(
        'name'               => 'Publishers',
        'singular_name'      => 'Publisher',
        'search_items'       => 'Search Publishers',
        'all_items'          => 'All Publishers',
        'parent_item'        => 'Parent Publishers',
        'parent_item_colon'  => 'Parent Publishers:',
        'update_item'        => 'Update Publishers',
        'edit_item'          => 'Edit Publishers',
        'add_new_item'       => 'Add New Publishers', 
        'new_item_name'      => 'New Publishers Name',
        'menu_name'          => 'Publishers',
    'not_found'          => __( 'No Publishers found'),
        'not_found_in_trash' => __( 'No found in Trash')
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
    $taxonomy2 = 'auth';
    //set the post types for the taxonomy
    $object_type2 = 'book';
    
    //populate our array of names for our taxonomy
    $labels = array(
        'name'               => 'Authors',
        'singular_name'      => 'Author',
        'search_items'       => 'Search Authors',
        'all_items'          => 'All Authors',
        'parent_item'        => 'Parent Authors',
        'parent_item_colon'  => 'Parent Authors:',
        'update_item'        => 'Update Authors',
        'edit_item'          => 'Edit Authors',
        'add_new_item'       => 'Add New Authors', 
        'new_item_name'      => 'New Authors Name',
        'menu_name'          => 'Authors',
    'not_found'          => __( 'No Authors found'),
        'not_found_in_trash' => __( 'No found in Trash')
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
        'rewrite'           => array('slug' => 'auth')
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
  wp_nonce_field('my_custom_page', '_my_custom_page');
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

	if( isset($_POST['_my_custom_page'])){
	if (!wp_verify_nonce( $_POST['_my_custom_page'], 'my_custom_page' )) { return $post_id; }
    $price = isset($_POST['price']) ? $_POST['price']:'';
    update_post_meta( $post_id, 'price', $price );
	}
}




function searchform( ) {

   
    return include(plugin_dir_path(__FILE__) . 'template-book.php');
}

add_shortcode('search','searchform');


function ajax_filter_get_posts() {

  $authors = $_POST['author'];
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

  // Verify nonce
  if( !isset( $_POST['afp_nonce'] ) || !wp_verify_nonce( $_POST['afp_nonce'], 'afp_nonce' ) )
    die('Permission denied');

  $publishers = $_POST['publishers'];
  

  $page_no    = $_POST['page'];
  $search     = $_POST['search'];
  
  // WP Query
  $args = array(
    'publisher'    => $publishers,
    'auth' => $authors,
    'post_type'     => 'book',
    'posts_per_page' => 6,
    'order'         =>'ASC',
    'paged'         => $page_no,
    's'             => $search

  );
  
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

add_filter( 'manage_book_posts_columns', 'smashing_filter_posts_columns' );
function smashing_filter_posts_columns( $columns ){
  $columns['price'] = __( 'Price', 'smashing' );
  return $columns;
}

add_action( 'manage_book_posts_custom_column', 'smashing_realestate_column', 10, 2);
function smashing_realestate_column( $column, $post_id ) {
  // Image column
   $price = get_post_meta( $post_id, 'price', true );
    if ( ! $price ) {
      _e( 'n/a' );  
    } else {
      echo 'RS ' . number_format( $price, 0, '.', ',' );
    }
}


function some_function()
{
  $post_details = array(
  'post_title'    => 'Book Cpt Search page',
  'post_content'  => '[search]',
  'post_status'   => 'publish',
  'post_author'   => 1,
  'post_type' => 'page'
   );
   wp_insert_post( $post_details );
}

register_activation_hook(__FILE__, 'some_function');



?>