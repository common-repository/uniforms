<?php

// No direct access allowed.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * UniForms Post Type Class
 *
 * @since 1.0.0
 */
class UniForms_Post_Type {
    
    /**
     * Class Constructor
     */
    function __construct() {
        
        add_action( 'init', array( $this, 'register_post_type' ), 0 );
        
    }
    
    /**
     * Register Post Type
     *
     * @since 1.0.0
     */
    function register_post_type() {

        $labels = array(
            'name'                  => _x( 'UniForms', 'Post Type General Name', 'uniforms' ),
            'singular_name'         => _x( 'UniForms', 'Post Type Singular Name', 'uniforms' ),
            'menu_name'             => esc_html__( 'UniForms', 'uniforms' ),
            'name_admin_bar'        => esc_html__( 'UniForms', 'uniforms' ),
            'archives'              => esc_html__( 'UniForms Archives', 'uniforms' ),
            'attributes'            => esc_html__( 'UniForms Attributes', 'uniforms' ),
            'parent_item_colon'     => esc_html__( 'Parent Item:', 'uniforms' ),
            'all_items'             => esc_html__( 'All Items', 'uniforms' ),
            'add_new_item'          => esc_html__( 'Add New', 'uniforms' ),
            'add_new'               => esc_html__( 'Add New', 'uniforms' ),
            'new_item'              => esc_html__( 'New Item', 'uniforms' ),
            'edit_item'             => esc_html__( 'Edit Item', 'uniforms' ),
            'update_item'           => esc_html__( 'Update Item', 'uniforms' ),
            'view_item'             => esc_html__( 'View Item', 'uniforms' ),
            'view_items'            => esc_html__( 'View Items', 'uniforms' ),
            'search_items'          => esc_html__( 'Search Item', 'uniforms' ),
            'not_found'             => esc_html__( 'Not found', 'uniforms' ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'uniforms' ),
            'featured_image'        => esc_html__( 'Featured Image', 'uniforms' ),
            'set_featured_image'    => esc_html__( 'Set featured image', 'uniforms' ),
            'remove_featured_image' => esc_html__( 'Remove featured image', 'uniforms' ),
            'use_featured_image'    => esc_html__( 'Use as featured image', 'uniforms' ),
            'insert_into_item'      => esc_html__( 'Insert into item', 'uniforms' ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this item', 'uniforms' ),
            'items_list'            => esc_html__( 'Items list', 'uniforms' ),
            'items_list_navigation' => esc_html__( 'Items list navigation', 'uniforms' ),
            'filter_items_list'     => esc_html__( 'Filter items list', 'uniforms' ),
        );
        $args = array(
            'label'                 => esc_html__( 'UniForms', 'uniforms' ),
            'description'           => esc_html__( 'UniForms Builder', 'uniforms' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-welcome-widgets-menus',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
        );
        register_post_type( 'uniforms', $args );
    }
    
}
new UniForms_Post_Type();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
