<?php

/**
 * Plugin Name: Posttype
 * Description: Some post type plugin
 * Version: 0.1
 * Author: Gerwin
 * License: GPL2
 */


function my_custom_posttypes()
{
    generate_custom_post_type('Reviews', 'Review', array(
            'menu_icon' => 'dashicons-star-half',
            'taxonomies' => array('category', 'post_tag'),
        )
    );

    generate_custom_post_type('Testimonials', 'Testimonial', array(
            'menu_icon' => 'dashicons-format-status',
        )
    );
}
add_action('init', 'my_custom_posttypes');

function my_rewrite_flush()
{
    my_custom_posttypes();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'my_rewrite_flush');


function generate_custom_post_type($name, $singular, $args = array())
{
    $lower_case_name = strtolower($name);
    $labels = array(
        'name'               => $name,
        'singular_name'      => $singular,
        'menu_name'          => $name,
        'name_admin_bar'     => $singular,
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New '. $singular,
        'new_item'           => 'New '. $singular,
        'edit_item'          => 'Edit '. $singular,
        'view_item'          => 'View '. $singular,
        'all_items'          => 'All '. $name,
        'search_items'       => 'Search '. $name,
        'parent_item_colon'  => 'Parent '. $name,
        'not_found'          => 'No '. $name .' found.',
        'not_found_in_trash' => 'No '. $name .' found in Trash.',
    );

    $args = array(
        'labels'            => $labels,
        'public'            => array_key_exists('public', $args) ? $args['public'] : true,
        'public_queryable'  => array_key_exists('public_queryableublic', $args) ? $args['public_queryable'] : true,
        'show_ui'           => array_key_exists('show_ui', $args) ? $args['show_ui'] : true,
        'show_in_menu'      => array_key_exists('show_in_menu', $args) ? $args['show_in_menu'] : true,
        'menu_position'     => array_key_exists('menu_position', $args) ? $args['menu_position'] : 5,
        'menu_icon'         => array_key_exists('menu_icon', $args) ? $args['menu_icon'] : 'dashicons-id-alt',
        'query_var'         => array_key_exists('query_var', $args) ? $args['query_var'] : true,
        'rewrite'           => array_key_exists('rewrite', $args) ? $args['rewrite'] : array('slug' => $lower_case_name),
        'capability_type'   => array_key_exists('capability_type', $args) ? $args['capability_type'] : 'post',
        'has_archive'       => array_key_exists('has_archive', $args) ? $args['has_archive'] : true,
        'hierarchical'      => array_key_exists('hierarchical', $args) ? $args['hierarchical'] : false,
        'supports'          => array_key_exists('supports', $args) ? $args['supports'] : array('tile', 'editor', 'thumbnail'),
        'taxonomies'        => array_key_exists('taxonomies', $args) ? $args['taxonomies'] : array('category', 'post_tag'),
    );

    register_post_type($lower_case_name, $args);
}