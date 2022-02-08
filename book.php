<?php
/**
 * Book-cpt main file.
 *
 * @package bookcpt-master
 **/

/**
 * Plugin Name: Book Filter
 * Plugin URI: https://test-projext.000webhostapp.com/
 * Description: This is book cpt plugin, it comes with ajax search and filter functionlity.
 * Version: 1.0
 * WC tested up to: 5.8.2
 * Author: Murtuza Makda(idrish)
 * Author URI: https://www.upwork.com/freelancers/~018f06972fe4607ad0
 * License: GPL v3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: bmaster
 **/
function ajax_filter_posts_scripts() {
	wp_register_style( 'post-style', plugins_url( '/assets/wp-post.css', __FILE__ ), array(), '2.0' );
	wp_enqueue_style( 'post-style' );
	wp_register_script( 'afp_script', plugins_url( '/assets/ajax-filter-posts.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'afp_script' );
	wp_localize_script(
		'afp_script',
		'afp_vars',
		array(
			'afp_nonce'    => wp_create_nonce( 'afp_nonce' ), // Create nonce which we later will use to verify AJAX request.
			'afp_ajax_url' => admin_url( 'admin-ajax.php' ),
		)
	);

	wp_register_style( 'jquery-mobile-style', 'https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css', array(), '2.0' );
	wp_enqueue_style( 'jquery-mobile-style' );
	wp_register_script( 'jquery-mobile-script', 'https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'jquery-mobile-script' );

}
add_action( 'wp_enqueue_scripts', 'ajax_filter_posts_scripts', 100 );

add_action( 'init', 'bcpt_create_custom_post_type_book' );
add_action( 'init', 'bcpt_add_publisher_taxonomy_to_post' );
add_action( 'init', 'bcpt_add_author_taxonomy_to_post' );
add_filter( 'use_block_editor_for_post_type', 'bcpt_disable_gutenberg_for_book', 10, 2 );

define( 'BCPT_BASE_FILE', __FILE__ );

/**
 * Disable gutenberg editor for this cpt.
 *
 * @param boolean $current_status pass status of editor. and post type.
 * @param object  $post_type      and post type.
 * @return response of all filterd data.
 */
function bcpt_disable_gutenberg_for_book( $current_status, $post_type ) {
	if ( 'book' === $post_type ) {
		return false;
	}
	return $current_status;
}

/**
 * Function for init book cpt.
 */
function bcpt_create_custom_post_type_book() {
	$supports = array(
		'title', // post title.
		'editor', // post content.
		'author', // post author.
		'thumbnail', // featured images.
		'excerpt', // post excerpt.
		'custom-fields', // custom fields.
		'comments', // post comments.
		'revisions', // post revisions.
		'post-formats', // post formats.
	);

	$labels = array(
		'name'           => _x( 'Books', 'plural' ),
		'singular_name'  => _x( 'Book', 'singular' ),
		'menu_name'      => _x( 'Books Filter', 'admin menu' ),
		'name_admin_bar' => _x( 'Book', 'admin bar' ),
		'add_new'        => _x( 'Add Book', 'add new' ),
		'add_new_item'   => __( 'Add New Book' ),
		'new_item'       => __( 'New Book' ),
		'edit_item'      => __( 'Edit Book' ),
		'view_item'      => __( 'View Book' ),
		'all_items'      => __( 'All Book' ),
		'search_items'   => __( 'Search Book' ),
		'not_found'      => __( 'No Book found.' ),
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
		'rewrite'           => array( 'slug' => 'book' ),
		'has_archive'       => true,
		'hierarchical'      => false,
		'menu_position'     => 6,
		'menu_icon'         => 'dashicons-book',
	);

	register_post_type( 'book', $args );
	global $wp_rewrite;
	$wp_rewrite->flush_rules( true );
}

/**
 * Create a function that will attach taxonomy to the 'book' post type.
 */
function bcpt_add_publisher_taxonomy_to_post() {

	// set the name of the taxonomy.
	$taxonomy1 = 'publisher';
	// set the post types for the taxonomy.
	$object_type1 = 'book';

	// populate our array of names for our taxonomy.
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
		'not_found'          => __( 'No Publishers found' ),
		'not_found_in_trash' => __( 'No found in Trash' ),
	);

	// define arguments to be used.
	$args1 = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_ui'           => true,
		'how_in_nav_menus'  => true,
		'public'            => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'publisher' ),
	);

	// call the register_taxonomy function.
	register_taxonomy( $taxonomy1, $object_type1, $args1 );
}

/**
 * Create a function that will attach taxonomy to the 'book' post type.
 */
function bcpt_add_author_taxonomy_to_post() {
	// set the name of the taxonomy.
	$taxonomy2 = 'auth';
	// set the post types for the taxonomy.
	$object_type2 = 'book';
	// populate our array of names for our taxonomy.
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
		'not_found'          => __( 'No Authors found' ),
		'not_found_in_trash' => __( 'No found in Trash' ),
	);

	// define arguments to be used.
	$args2 = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'show_ui'           => true,
		'how_in_nav_menus'  => true,
		'public'            => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'auth' ),
	);

	// call the register_taxonomy function.
	register_taxonomy( $taxonomy2, $object_type2, $args2 );
}

add_filter( 'template_include', 'my_book_templates' );
/**
 * Add custom template (single.php / achive.php) from plugin dir.
 *
 * @param string $template pass template default path/name.
 */
function my_book_templates( $template ) {
	$post_types = array( 'book' );

	if ( is_post_type_archive( $post_types ) && file_exists( plugin_dir_path( __FILE__ ) . 'template-book.php' ) ) {
		$template = plugin_dir_path( __FILE__ ) . 'template-book.php';
	}

	if ( is_singular( $post_types ) && file_exists( plugin_dir_path( __FILE__ ) . 'single-book.php' ) ) {
		$template = plugin_dir_path( __FILE__ ) . 'single-book.php';
	}
	return $template;
}

/**
 * Function for choose template.
 *
 * @param string $template pass template default path/name.
 */
function bcpt_template_chooser( $template ) {
	global $wp_query;
	$post_type = get_query_var( 'post_type' );
	if ( 'book' === $post_type && $wp_query->is_search ) {
		return plugin_dir_path( __FILE__ ) . 'archive-search.php';  // redirect.
	}
	return $template;
}
add_filter( 'template_include', 'bcpt_template_chooser' );
add_action( 'admin_init', 'bcpt_add_metabox_price_widget' );
add_action( 'save_post', 'bcpt_save_metabox_price_widget' );

/**
 * Funtion to add a meta box to enable price widget on book posts.
 */
function bcpt_add_metabox_price_widget() {
	// replace "post" with your custom post value(eg: "motors").
	add_meta_box( 'book_price', 'Price', 'bcpt_enable_post_price_widget', 'book', 'normal', 'high' );
}

/**
 * Add price field to edit book page.
 */
function bcpt_enable_post_price_widget() {
	wp_nonce_field( 'my_custom_page', '_my_custom_page' );
	global $post;
	$image = get_post_custom( $post->ID );
	$price = $image ? $image['price'][0] : '';
	echo "<input type = 'number' name = 'price' value = '" . esc_html( $price ) . "' required>";
}

/**
 * Save the price meta box value  from edit book.
 *
 * @param int $post_id pass post id.
 */
function bcpt_save_metabox_price_widget( $post_id ) {
	$my_custom_page = filter_input( INPUT_POST, '_my_custom_page', FILTER_SANITIZE_STRING );
	$price          = filter_input( INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_INT );
	if ( isset( $my_custom_page ) ) {
		if ( ! wp_verify_nonce( $my_custom_page, 'my_custom_page' ) ) {
			return $post_id;
		}
		if ( $price ) {
			update_post_meta( $post_id, 'price', $price );
		}
	}
}
/**
 * Add search form.
 */
function searchform() {
	return include plugin_dir_path( __FILE__ ) . 'template-book.php';
}
add_shortcode( 'search', 'searchform' );

/**
 * Function for filter post.
 */
function ajax_filter_get_posts() {

	$page_no      = filter_input( INPUT_POST, 'page_no', FILTER_SANITIZE_STRING );
	$paged        = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$afp_nonce    = filter_input( INPUT_POST, 'afp_nonce', FILTER_SANITIZE_STRING );
	$posted_array = filter_input_array( INPUT_POST );

	// Verify nonce.
	if ( ! isset( $afp_nonce ) || ! wp_verify_nonce( $afp_nonce, 'afp_nonce' ) ) {
		die( 'Permission denied' );
	}
	if ( isset( $_POST['publishers'] ) ) {
		$publishers = $posted_array['publishers'];
	}
	if ( isset( $_POST['author'] ) ) {
		$authors = $posted_array['author'];
	}
	$search         = filter_input( INPUT_POST, 'search', FILTER_SANITIZE_STRING );
	$post_per_pages = wp_unslash( get_option( 'post_per_page' ) ) ? wp_unslash( get_option( 'post_per_page' ) ) : '6';
	// WP Query.
	$args = array(
		'publisher'      => $publishers,
		'auth'           => $authors,
		'post_type'      => 'book',
		'posts_per_page' => $post_per_pages,
		'order'          => 'ASC',
		'paged'          => $page_no,
		's'              => $search,

	);
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) :
		while ( $query->have_posts() ) :
			$query->the_post(); ?>
	<div class="single-portfolio" data-page="<?php echo esc_html( $page_no ); ?>" data-maxpage="<?php echo esc_html( $query->max_num_pages ); ?>">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( array( 100, 100 ) );
				?>
				<?php
			} else {
				echo '<img src="' . esc_url( plugins_url( '/assets/placeholderimage.jpg', __FILE__ ) ) . '" width="100" height="100" style="height:100px !important">';
			}
			$value = get_post_custom( get_the_ID() );
			$price = $value['price'][0];
			?>
<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><h5><?php the_excerpt(); ?></h5> <?php echo '₹ <span class="price">' . esc_html( $price ) . '</span>'; ?></h2>
</div>

<?php endwhile; ?>
<?php else : ?>
<h2 class="center-text">No Books found</h2>
	<?php
endif;
die();
}

add_action( 'wp_ajax_filter_posts', 'ajax_filter_get_posts' );
add_action( 'wp_ajax_nopriv_filter_posts', 'ajax_filter_get_posts' );

/**
 * Heading for admin table in editbook page.
 *
 * @param string $columns pass column name.
 * @return response of all filterd data.
 */
function bcpt_price_column_in_admin_table_heading( $columns ) {
	$columns['price'] = __( 'Price' );
	return $columns;
}
add_filter( 'manage_book_posts_columns', 'bcpt_price_column_in_admin_table_heading' );
add_action( 'manage_book_posts_custom_column', 'bcpt_price_column_in_admin_table_show', 10, 2 );

/**
 * Edit price shows in admin table book edit page.
 *
 * @param string $column pass column name.
 * @param string $post_id post type which post type you want to add.
 */
function bcpt_price_column_in_admin_table_show( $column, $post_id ) {
	// Image column.
	$price = get_post_meta( $post_id, 'price', true );
	if ( ! $price ) {
		esc_html_e( 'n/a' );
	} else {
		echo '₹ ' . number_format( $price, 0, '.', ',' );
	}
}


/**
 * Function for create new page and add append shortcode of search page.
 */
function bcpt_create_page_and_add_shortcode() {
	$post_details = array(
		'post_title'   => 'Book Filter Search page',
		'post_content' => '[search]',
		'post_status'  => 'publish',
		'post_author'  => 1,
		'post_type'    => 'page',
	);
	wp_insert_post( $post_details );
}

register_activation_hook( __FILE__, 'bcpt_create_page_and_add_shortcode' );


/**
 * Function for remove extra meta box from book cpt edit page.
 */
function bcpt_remove_books_extra_metaboxes() {
	remove_meta_box( 'authordiv', 'book', 'normal' ); // Author Metabox.
	remove_meta_box( 'postcustom', 'book', 'normal' ); // Custom Fields Metabox.
	remove_meta_box( 'commentstatusdiv', 'book', 'normal' ); // Comments Status Metabox.
	remove_meta_box( 'commentsdiv', 'book', 'normal' ); // Comments Metabox.
	remove_meta_box( 'postexcerpt', 'book', 'normal' ); // Excerpt Metabox.
	remove_meta_box( 'revisionsdiv', 'book', 'normal' ); // Revisions Metabox.

}
add_action( 'admin_menu', 'bcpt_remove_books_extra_metaboxes' );


/**
 * Add Book option to admin menu page.
 */
function bcpt_option_menu_page() {
	add_submenu_page( 'edit.php?post_type=book', 'Book Filter Option', 'Book Option', 'add_users', BCPT_BASE_FILE, 'bcpt_book_filter_custom_options', 4 );
}
add_action( 'admin_menu', 'bcpt_option_menu_page' );


/**
 * Book option page's view.
 */
function bcpt_book_filter_custom_options() {
	?>
<form method = "post" action = "options.php">
	<?php
	settings_fields( 'book_filter_min_max_post' );
			do_settings_sections( 'book_filter_min_max_post' );
	?>
	<div class="wrap">
		<h1>Book Filter Settings</h1>
		<input type = "text" name = "post_per_page" value = "<?php echo esc_attr( get_option( 'post_per_page' ) ); ?>">
		<label>Min Range Value</label>
		<input type = "number" class = "range-meter" name = "min_range" id = "min_range" value = "<?php echo esc_attr( get_option( 'min_range' ) ); ?>">
		<label>max Range Value</label>
		<input type = "number" class = "range-meter" name = "max_range" id = "max_range" value = "<?php echo esc_attr( get_option( 'max_range' ) ); ?>">
		<?php submit_button(); ?>
	</div>
</form>
	<?php
}
add_action( 'admin_init', 'register_book_filter_value' );

/**
 * Register setting field.
 */
function register_book_filter_value() {
	register_setting( 'book_filter_min_max_post', 'post_per_page' );
	register_setting( 'book_filter_min_max_post', 'min_range' );
	register_setting( 'book_filter_min_max_post', 'max_range' );
}

/**
 * Edit price text box append in quick edit.
 *
 * @param string $column_name pass column name.
 * @param string $post_type post type which post type you want to add.
 */
function bcpt_add_quick_edit( $column_name, $post_type ) {
	switch ( $column_name ) {
		case 'price':
			echo '<fieldset class="inline-edit-col-right" style="border: 1px solid #dddddd;">
                        <legend style="font-weight: bold; margin-left: 10px;">Book Custom Fields:</legend>
                        <div class="inline-edit-col">';
			wp_nonce_field( 'wpar_q_edit_nonce', 'wpar_nonce' );
			echo '<label class="alignleft" style="width: 100%;">
                        <span class="title">' . esc_html( 'Price' ) . '</span>
                        <span class="input-text-wrap"><input type="number" name="' . esc_html( $column_name ) . '" value="" style="width: 100%;"></span>
                        <span style="font-style: italic;color:#999999; text-align:right; display: inherit;">' . esc_html( 'Enter Book Price' ) . '</span>
                      </label>';
			echo '<br><br>';
			echo '</div></fieldset>';
			break;
		default:
			break;
	}
}
add_action( 'quick_edit_custom_box', 'bcpt_add_quick_edit', 10, 2 );
add_action( 'save_post', 'bcpt_edit_save_price', 10, 2 );

/**
 * Function for save price.
 *
 * @param int    $post_id pass post id & post.
 * @param object $post    pass post object.
 */
function bcpt_edit_save_price( $post_id, $post ) {
	$price = filter_input( INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_INT );
	if ( $price ) {
		update_post_meta( $post_id, 'price', $price );
	}
}

/**
 * Edit Price from book cpt adminside table.
 */
function wpar_quick_edit_js() {
		$current_screen = get_current_screen();
	if ( 'edit-book' !== $current_screen->id || 'book' !== $current_screen->post_type ) {
		return;
	}
		wp_enqueue_script( 'jquery' );
	?>
		<script type="text/javascript">
			jQuery(function($) {
				var $wpar_inline_editor = inlineEditPost.edit;
				inlineEditPost.edit = function( id ) {
					// call the original WP edit function.
					$wpar_inline_editor.apply( this, arguments );
					// get the post ID.
					var $post_id = 0;
					if ( typeof( id ) == 'object') {
						$post_id = parseInt( this.getId( id ) );
					}
					// if we have our post.
					if ( $post_id != 0 ) {
						// define the edit row.
						var $edit_row = $( '#edit-' + $post_id );
						var $post_row = $( '#post-' + $post_id );

						// get the data.
						var $price = $( '.column-price', $post_row ).text();
						var $fvalue = $price.replace( '₹ ', '');

						// populate the data.
						$( ':input[name="price"]', $edit_row).val( $fvalue );
					}
				}
			});
		</script>
	<?php
}
	add_action( 'admin_print_footer_scripts-edit.php', 'wpar_quick_edit_js' );
?>
