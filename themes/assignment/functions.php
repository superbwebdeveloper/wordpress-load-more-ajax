/**
 *  Custom Post Type additions.
 */
require get_template_directory() . '/inc/custom_post_type.php';
add_image_size('photos-list', 500, 375, true);

add_action('wp_enqueue_scripts', 'my_header_assets');
function my_header_assets()
{

	wp_enqueue_style('bootstrap-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
	wp_enqueue_style('fontawesome-style', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_enqueue_script('jquery-version', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', array('jquery'));
	wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array('jquery'));
}
function photos_load_more_scripts()
{
	$args_p = array('post_type' => 'photos', 'orderby' => 'menu_order title', 'order' => 'ASC', 'posts_per_page' => '5');
	$the_query_p = new WP_Query($args_p);
	// In most cases it is already included on the page and this line can be removed
	//wp_enqueue_script('jquery');

	// register our main script but do not enqueue it yet
	wp_register_script('photos_loadmore', get_stylesheet_directory_uri() . '/js/photo_load_more.js', array('jquery-version'));

	// now the most interesting part
	// we have to pass parameters to myloadmore.js script but we can get the parameters values only in PHP
	// you can define variables directly in your HTML but I decided that the most proper way is wp_localize_script()
	wp_localize_script('photos_loadmore', 'photos_loadmore_params', array(
		'ajaxurl' => admin_url('admin-ajax.php'), // WordPress AJAX
		'posts' => json_encode($the_query_p->query_vars), // everything about your loop is here
		'current_page' => get_query_var('paged') ? absint(get_query_var('paged')) : 1,
		'max_page' => $the_query_p->max_num_pages
	));

	wp_enqueue_script('photos_loadmore');
}

add_action('wp_enqueue_scripts', 'photos_load_more_scripts');

function photos_loadmore_ajax_handler()
{
	// prepare our arguments for the query
	$args_p = json_decode(stripslashes($_POST['posts']), true);
	$args_p['post_status'] = 'publish';
	$args_p = array('post_type' => 'photos', 'orderby' => 'menu_order title', 'order' => 'ASC', 'posts_per_page' => 5, 'paged' => $_POST['page'] + 1);
	$the_query_p = new WP_Query($args_p);
	//echo $the_query_p->request;
	?>
	<?php if ($the_query_p->have_posts()) {
			$count_home_banner = $the_query_p->post_count;
			while ($the_query_p->have_posts()) : $the_query_p->the_post();
				if (has_post_thumbnail($the_query_p->post->ID)) {
					$feat_image = wp_get_attachment_image_src(get_post_thumbnail_id($the_query_p->post->ID), 'photos-list');
					$p_image = $feat_image[0];

					?>
				<div class="col-4 <?php echo $the_query_p->post->ID; ?> <?php echo $the_query_p->post->post_title; ?>">
					<img src="<?php echo $p_image; ?>" alt="" />
				</div>
	<?php
				}
			endwhile;
		}

		die; // here we exit the script and even no wp_reset_query() required!
	}

	add_action('wp_ajax_loadmore', 'photos_loadmore_ajax_handler'); // wp_ajax_{action}
	add_action('wp_ajax_nopriv_loadmore', 'photos_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}
