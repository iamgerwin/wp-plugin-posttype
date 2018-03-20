<?php

/**
 * Plugin Name: GERPosttype
 * Description: Some post type plugin
 * Version: 0.1
 * Author: Gerwin
 * License: GPL2
 */


function my_custom_posttypes()
{
    generate_custom_post_type( 'Review', array(
            'menu_icon'     => 'dashicons-star-half',
            'register_name' => 'reviews',
        )
    );

    generate_custom_post_type( 'Testimonial', array(
            'menu_icon'  => 'dashicons-format-status',
            'supports'   => array('tile', 'editor', 'thumbnail', 'author', 'excerpt', 'comments'),
            'taxonomies' => array('category', 'post_tag'),
        )
    );
}

function my_custom_taxonomies()
{
    generate_custom_taxonomy( 'Product / Service', array('reviews'), array(
        'rewrite'       => array('slug' => 'product-types'),
        'hierarchical'  => true,
        'register_name' => 'product-types',         
    ));

    generate_custom_taxonomy( 'Mood', array('reviews'), array(
        'rewrite'       => array('slug' => 'moods'),
        'hierarchical'  => false,
        'register_name' => 'moods',
    ));

    generate_custom_taxonomy( 'Price Range', array('reviews'), array(
        'rewrite'       => array('slug' => 'prices'),
        'hierarchical'  => false,
        'register_name' => 'prices',
    ));
}


add_action('init', 'my_custom_posttypes');
add_action('init', 'my_custom_taxonomies');

/**
 * 
 */

function my_rewrite_flush()
{
    my_custom_posttypes();
    my_custom_taxonomies();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'my_rewrite_flush' );

function generate_custom_post_type( $name, $args = array() )
{
    $plural_name = array_key_exists( 'plural_name', $args ) ? $args['plural_name'] : $name. 's';
    $lower_case_name = strtolower(preg_replace("/[^\w]+/", "-", $name));

    $labels = array(
        'name'               => _x( $plural_name, 'gerposttype-textdomain' ),
        'singular_name'      => _x( $name, 'gerposttype-textdomain' ),
        'menu_name'          => _x( $plural_name, 'admin menu', 'gerposttype-textdomain' ),
        'name_admin_bar'     => _x( $name, 'add new on admin bar', 'gerposttype-textdomain' ),
        'add_new'            => _x( 'Add New', $lower_case_name, 'gerposttype-textdomain' ),
        'add_new_item'       => __( 'Add New '. $name, 'gerposttype-textdomain' ),
        'new_item'           => __( 'New '. $name, 'gerposttype-textdomain' ),
        'edit_item'          => __( 'Edit '. $name, 'gerposttype-textdomain' ),
        'view_item'          => __( 'View '. $name, 'gerposttype-textdomain' ),
        'all_items'          => __( 'All '. $plural_name, 'gerposttype-textdomain' ),
        'search_items'       => __( 'Search '. $plural_name, 'gerposttype-textdomain' ),
        'parent_item_colon'  => __( 'Parent '. $plural_name, 'gerposttype-textdomain' ),
        'not_found'          => __( 'No '. $plural_name .' found.', 'gerposttype-textdomain' ),
        'not_found_in_trash' => __( 'No '. $plural_name .' found in Trash.', 'gerposttype-textdomain' ),
    );

    $arguments = array(
        'labels'            => $labels,
        'description'       => __( 'Description', 'gerposttype-textdomain'),
        'public'            => array_key_exists( 'public', $args ) ? $args['public'] : true,
        'public_queryable'  => array_key_exists( 'public_queryableublic', $args ) ? $args['public_queryable'] : true,
        'show_ui'           => array_key_exists( 'show_ui', $args ) ? $args['show_ui'] : true,
        'show_in_menu'      => array_key_exists( 'show_in_menu', $args ) ? $args['show_in_menu'] : true,
        'menu_position'     => array_key_exists( 'menu_position', $args ) ? $args['menu_position'] : 5,
        'menu_icon'         => array_key_exists( 'menu_icon', $args ) ? $args['menu_icon'] : 'dashicons-id-alt',
        'query_var'         => array_key_exists( 'query_var', $args ) ? $args['query_var'] : true,
        'rewrite'           => array_key_exists( 'rewrite', $args ) ? $args['rewrite'] : array('slug' => $lower_case_name),
        'capability_type'   => array_key_exists( 'capability_type', $args ) ? $args['capability_type'] : 'post',
        'has_archive'       => array_key_exists( 'has_archive', $args ) ? $args['has_archive'] : true,
        'hierarchical'      => array_key_exists( 'hierarchical', $args ) ? $args['hierarchical'] : false,
        'supports'          => array_key_exists( 'supports', $args ) ? $args['supports'] : array('tile', 'editor', 'thumbnail'),
        'taxonomies'        => array_key_exists( 'taxonomies', $args ) ? $args['taxonomies'] : array(),
    );

    $register_name = array_key_exists( 'register_name', $args ) ? $args['register_name'] : $lower_case_name;

    register_post_type($register_name, $arguments);
}

function generate_custom_taxonomy($name, $object_type = array(), $args = array())
{
    $plural_name = array_key_exists( 'plural_name', $args ) ? $args['plural_name'] : $name . 's';
    $lower_case_name = strtolower(preg_replace("/[^\w]+/", "-", $name));
    $lower_case_plural_name = strtolower( preg_replace( "/[^\w]+/", "-", $plural_name ) );
    $hierarchical = array_key_exists(  'hierarchical', $args ) ? $args['hierarchical'] : true;


    $labels = array(
        'name'                       => _x( $plural_name, 'taxonomy general name', 'textdomain' ),
        'singular_name'              => _x( $name, 'taxonomy singular name', 'textdomain' ),
        'search_items'               => __( 'Search '. $plural_name, 'textdomain' ),
        'popular_items'              => __( 'Popular '. $plural_name, 'textdomain' ),
        'all_items'                  => __( 'All '. $plural_name, 'textdomain' ),
        'parent_item'                => $hierarchical ? __( 'Parent Type of '. $name, ' textdomain') : null,
        'parent_item_colon'          => $hierarchical ? __( 'Parent Type of '. $name .':', ' textdomain') : null,
        'edit_item'                  => __( 'Edit '. $name, 'textdomain' ),
        'update_item'                => __( 'Update '. $name, 'textdomain' ),
        'add_new_item'               => __( 'Add New '. $name, 'textdomain' ),
        'new_item_name'              => __( 'New '. $name .' Name', 'textdomain' ),
        'separate_items_with_commas' => __( 'Seperate '. $lower_case_plural_name .' with commas', 'textdomain' ),
        'add_or_remove_items'        => __( 'Add or remove '. $plural_name, 'textdomain' ),
        'choose_from_most_used'      => __( 'Choose from the most used '. $lower_case_plural_name, 'textdomain' ),
        'not_found'                  => __( 'No '. $lower_case_plural_name .' found.', 'textdomain' ),
        'menu_name'                  => __( $plural_name, 'textdomain' ),
    );

    $arguments = array(
        'labels'                => $labels,
        'hierarchical'          => $hierarchical,
        'show_ui'               => array_key_exists( 'show_ui', $args ) ? $args['show_ui'] : true,
        'show_admin_column'     => array_key_exists( 'show_admin_column', $args ) ? $args['show_admin_column'] : true,
        'update_count_callback' => array_key_exists( 'update_count_callback', $args ) ? $args['update_count_callback'] : '_update_post_term_count',
        'query_var'             => array_key_exists( 'query_var', $args ) ? $args['query_var'] : true,
        'rewrite'               => array_key_exists( 'rewrite', $args ) ? $args['rewrite'] : array('slug' => $lower_case_plural_name),
    );

    $register_name = array_key_exists( 'register_name', $args ) ? $args['register_name'] : $lower_case_name;

    register_taxonomy($register_name, $object_type, $arguments);
}
