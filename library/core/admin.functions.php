<?php 
/**
 * Wordpress Administration for Nutshell Metrics
 *
 * Current functionilities added:
 	- Campaign post type
 
 * @package WordPress
 * @subpackage nutshell
 */

/**
 * Creates the Campaign Post Type 
 */
function nuts_campaign_post_type() {
	$labels = array(
		'name' => 'Nutshell Campaigns',
		'singular_name' => 'Nutshell Campaigns',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New Campaign',
		'edit_item' => 'Edit Campaign',
		'new_item' => 'New Campaign',
		'view_item' => 'View Campaign',
		'search_items' => 'Search Campaign',
		'not_found' =>  'No Campaign found',
		'not_found_in_trash' => 'No Campaign found in Trash', 
		'parent_item_colon' => '',
		'menu_name' => 'Nutshell Campaigns'
	);
	
  	$args = array(
		'labels' => $labels,
		'public' => false,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_nav_menus' => false,
		'show_in_menu' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'page',
		'has_archive' => false, 
		'hierarchical' => false,
		'menu_icon' => get_bloginfo('wpurl').'/wp-admin/images/generic.png',
		'menu_position' => 100,
		'supports' => array('title','custom-fields','revisions')
	); 
	register_post_type('nutshell-campaigns',$args);
}
add_action('init', 'nuts_campaign_post_type');
?>

