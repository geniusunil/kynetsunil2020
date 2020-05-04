<?php

class Mv_List_Post_Types {


	function __construct() {
		add_action( 'init', array( $this, 'list_init' ) );
		add_action( 'init', array( $this, 'list_item_init' ) );
	}



	/**
	 * Register a list post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	function list_init() {
		$labels = array(
			'name'               => _x( 'Lists', 'post type general name', 'your-plugin-textdomain' ),
			'singular_name'      => _x( 'List', 'post type singular name', 'your-plugin-textdomain' ),
			'menu_name'          => _x( 'Lists', 'admin menu', 'your-plugin-textdomain' ),
			'name_admin_bar'     => _x( 'List', 'add new on admin bar', 'your-plugin-textdomain' ),
			'add_new'            => _x( 'Add New', 'list', 'your-plugin-textdomain' ),
			'add_new_item'       => __( 'Add New List', 'your-plugin-textdomain' ),
			'new_item'           => __( 'New List', 'your-plugin-textdomain' ),
			'edit_item'          => __( 'Edit List', 'your-plugin-textdomain' ),
			'view_item'          => __( 'View List', 'your-plugin-textdomain' ),
			'all_items'          => __( 'All Lists', 'your-plugin-textdomain' ),
			'search_items'       => __( 'Search Lists', 'your-plugin-textdomain' ),
			'parent_item_colon'  => __( 'Parent Lists:', 'your-plugin-textdomain' ),
			'not_found'          => __( 'No lists found.', 'your-plugin-textdomain' ),
			'not_found_in_trash' => __( 'No lists found in Trash.', 'your-plugin-textdomain' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'your-plugin-textdomain' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'best' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			'yarpp_support' => true	,
				
		);

		register_post_type( 'lists', $args );
	}


	function list_item_init() {
		$labels = array(
			'name'               => _x( 'List Items', 'post type general name', 'your-plugin-textdomain' ),
			'singular_name'      => _x( 'List Item', 'post type singular name', 'your-plugin-textdomain' ),
			'menu_name'          => _x( 'List Items New', 'admin menu', 'your-plugin-textdomain' ),
			'name_admin_bar'     => _x( 'List Item', 'add new on admin bar', 'your-plugin-textdomain' ),
			'add_new'            => _x( 'Add New', 'list item', 'your-plugin-textdomain' ),
			'add_new_item'       => __( 'Add New List Item', 'your-plugin-textdomain' ),
			'new_item'           => __( 'New List Item', 'your-plugin-textdomain' ),
			'edit_item'          => __( 'Edit List Item', 'your-plugin-textdomain' ),
			'view_item'          => __( 'View List Item', 'your-plugin-textdomain' ),
			'all_items'          => __( 'All List Items', 'your-plugin-textdomain' ),
			'search_items'       => __( 'Search List Items', 'your-plugin-textdomain' ),
			'parent_item_colon'  => __( 'Parent List Items:', 'your-plugin-textdomain' ),
			'not_found'          => __( 'No list items found.', 'your-plugin-textdomain' ),
			'not_found_in_trash' => __( 'No list items found in Trash.', 'your-plugin-textdomain' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'your-plugin-textdomain' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'reviews' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			'yarpp_support' => true			
		);

		register_post_type( 'list_items', $args );

		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => _x( 'Categories', 'taxonomy general name', 'textdomain' ),
			'singular_name'     => _x( 'Category', 'taxonomy singular name', 'textdomain' ),
			'search_items'      => __( 'Search Categories', 'textdomain' ),
			'all_items'         => __( 'All Categories', 'textdomain' ),
			'parent_item'       => __( 'Parent Category', 'textdomain' ),
			'parent_item_colon' => __( 'Parent Category:', 'textdomain' ),
			'edit_item'         => __( 'Edit Category', 'textdomain' ),
			'update_item'       => __( 'Update Category', 'textdomain' ),
			'add_new_item'      => __( 'Add New Category', 'textdomain' ),
			'new_item_name'     => __( 'New Category Name', 'textdomain' ),
			'menu_name'         => __( 'Categories', 'textdomain' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'c' ),
		);

		register_taxonomy( 'list_categories', array( 'list_items','lists' ), $args );
        $labels = array(
            'name'              => _x( 'Comparison Categories', 'taxonomy general name', 'textdomain' ),
            'singular_name'     => _x( 'Comparison Category', 'taxonomy singular name', 'textdomain' ),
            'search_items'      => __( 'Comparison Search Categories', 'textdomain' ),
            'all_items'         => __( 'All Comparison Categories', 'textdomain' ),
            'parent_item'       => __( 'Parent Comparison Category', 'textdomain' ),
            'parent_item_colon' => __( 'Parent Comparison Category:', 'textdomain' ),
            'edit_item'         => __( 'Edit Comparison Category', 'textdomain' ),
            'update_item'       => __( 'Update Comparison Category', 'textdomain' ),
            'add_new_item'      => __( 'Add New Comparison Category', 'textdomain' ),
            'new_item_name'     => __( 'New Comparison Category Name', 'textdomain' ),
            'menu_name'         => __( 'Comparison Categories', 'textdomain' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'comp_cat' ),
        );

        register_taxonomy( 'list_comp_categories', array( 'list_items' ), $args );
		// Add new taxonomy, NOT hierarchical (like tags)
		$labels = array(
			'name'                       => _x( 'Tags', 'taxonomy general name', 'textdomain' ),
			'singular_name'              => _x( 'Tag', 'taxonomy singular name', 'textdomain' ),
			'search_items'               => __( 'Search Tags', 'textdomain' ),
			'popular_items'              => __( 'Popular Tags', 'textdomain' ),
			'all_items'                  => __( 'All Tags', 'textdomain' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Tag', 'textdomain' ),
			'update_item'                => __( 'Update Tag', 'textdomain' ),
			'add_new_item'               => __( 'Add New Tag', 'textdomain' ),
			'new_item_name'              => __( 'New Tag Name', 'textdomain' ),
			'separate_items_with_commas' => __( 'Separate tags with commas', 'textdomain' ),
			'add_or_remove_items'        => __( 'Add or remove tags', 'textdomain' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags', 'textdomain' ),
			'not_found'                  => __( 'No tags found.', 'textdomain' ),
			'menu_name'                  => __( 'Tags', 'textdomain' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'industry-software' ),
		);

		register_taxonomy( 'item_tags', 'list_items', $args );


	}
}

new Mv_List_Post_Types();
