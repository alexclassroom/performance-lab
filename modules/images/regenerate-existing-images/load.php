<?php
/**
 * Module Name: Regenarate Existing Images
 * Description: Introduces background process infrastructure to regenerate existing image sizes and MIME types on demand.
 * Experimental: No
 *
 * @since n.e.x.t
 * @package performance-lab
 */

/**
 * Require helper functions and specific integrations.
 */
require_once __DIR__ . '/class-wp-background-jobs-list-table.php';
require_once __DIR__ . '/admin-page.php';

/**
 * Registers the background job taxonomy.
 *
 * Intentionally on lower priority, so that other post types can be
 * registered successfully and taxonomy can be used for all the post types.
 *
 * This taxonomy will be used as a queue system where each term represents a job in the queue.
 *
 * @since n.e.x.t
 */
function perflab_register_background_job_taxonomy() {
	// Labels for the background job taxonomy.
	$labels = array(
		'name'                  => _x( 'Background Jobs', 'taxonomy general name', 'performance-lab' ),
		'singular_name'         => _x( 'Background Job', 'taxonomy singular name', 'performance-lab' ),
		'search_items'          => __( 'Search Background Jobs', 'performance-lab' ),
		'all_items'             => __( 'All Background Jobs', 'performance-lab' ),
		'parent_item'           => __( 'Parent Background Job', 'performance-lab' ),
		'parent_item_colon'     => __( 'Parent Background Job:', 'performance-lab' ),
		'edit_item'             => __( 'Edit Background Job', 'performance-lab' ),
		'update_item'           => __( 'Update Background Job', 'performance-lab' ),
		'add_new_item'          => __( 'Add new Background job', 'performance-lab' ),
		'new_item_name'         => __( 'New background job', 'performance-lab' ),
		'menu_name'             => __( 'Background Jobs', 'performance-lab' ),
		'view_item'             => __( 'View Background Job', 'performance-lab' ),
		'not_found'             => __( 'No background jobs found.', 'performance-lab' ),
		'no_terms'              => __( 'No background jobs', 'performance-lab' ),
		'items_list'            => __( 'Background jobs list', 'performance-lab' ),
		'items_list_navigation' => __( 'Background jobs list navigation', 'performance-lab' ),
		'back_to_items'         => __( 'Back to background jobs', 'performance-lab' ),
	);

	// Taxonomy arguments.
	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'show_ui'            => false,
		'query_var'          => false,
		'rewrite'            => false,
		'show_in_quick_edit' => false,
		'capabilities'       => perflab_get_background_job_capabilities(),
	);

	/**
	 * Register background_job taxonomy.
	 *
	 * We are not assigning the taxonomy to any object type because we can
	 * still assign the terms in taxonomy to thos object types using
	 * `wp_set_object_terms` which do not check if there is relationship
	 * between object and taxonomy.
	 */
	register_taxonomy( 'background_job', array( 'post' ), $args );
}
add_action( 'init', 'perflab_register_background_job_taxonomy' );

/**
 * Retrieves list of capabilities to manage background jobs.
 *
 * @since n.e.x.t
 *
 * @return array Map of core term capabilities and the actual capability names to use.
 */
function perflab_get_background_job_capabilities() {
	return array(
		'manage_terms' => 'manage_jobs', // To manage background_job terms.
		'edit_terms'   => 'edit_jobs', // To edit background_job terms.
		'delete_terms' => 'delete_jobs', // To delete background_job terms.
		'assign_terms' => 'assign_jobs', // To assign background_job terms to posts.
	);
}
