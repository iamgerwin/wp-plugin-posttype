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
    generate_custom_post_type('Review', array(
            'menu_icon'  => 'dashicons-star-half',
            'register_name' => 'reviews',
        )
    );

    generate_custom_post_type('Testimonial', array(
            'menu_icon' => 'dashicons-format-status',
            'supports'  => array('tile', 'editor', 'thumbnail', 'author', 'excerpt', 'comments'),
            'taxonomies' => array('category', 'post_tag'),
        )
    );
}

function my_custom_taxonomies()
{
    generate_custom_taxonomy('Product / Service', array('reviews'), array(
        'label' => 'Type of Product / Service',
        'rewrite' => array('slug' => 'product-types'),
        'hierarchical' => true,
        'register_name' => 'product-types',         
    ));
    generate_custom_taxonomy('Mood', array('reviews'), array(
        'label' => 'Moods',
        'rewrite' => array('slug' => 'moods'),
        'hierarchical' => false,
        'register_name' => 'moods',
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
register_activation_hook(__FILE__, 'my_rewrite_flush');

function generate_custom_post_type($name, $args = array())
{
    $plural_name = array_key_exists('plural_name', $args) ? $args['plural_name'] : $name. 's';
    $lower_case_name = strtolower(preg_replace("/[^\w]+/", "-", $name));

    $labels = array(
        'name'               => $plural_name,
        'singular_name'      => $name,
        'menu_name'          => $plural_name,
        'name_admin_bar'     => $name,
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New '. $name,
        'new_item'           => 'New '. $name,
        'edit_item'          => 'Edit '. $name,
        'view_item'          => 'View '. $name,
        'all_items'          => 'All '. $plural_name,
        'search_items'       => 'Search '. $plural_name,
        'parent_item_colon'  => 'Parent '. $plural_name,
        'not_found'          => 'No '. $plural_name .' found.',
        'not_found_in_trash' => 'No '. $plural_name .' found in Trash.',
    );

    $arguments = array(
        'labels' => $labels,
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
        'taxonomies'        => array_key_exists('taxonomies', $args) ? $args['taxonomies'] : array(),
    );

    $register_name = array_key_exists('register_name', $args) ? $args['register_name'] : $lower_case_name;

    register_post_type($register_name, $arguments);
}

function generate_custom_taxonomy($name, $object_type = array(), $args = array())
{
    $plural_name = array_key_exists('plural_name', $args) ? $args['plural_name'] : $name . 's';
    $lower_case_name = strtolower(preg_replace("/[^\w]+/", "-", $name));
    $hierarchical = array_key_exists('hierarchical', $args) ? $args['hierarchical'] : true;


    $labels = array(
        'name' => 'Type of '. $plural_name,
        'singular_name' => 'Type of '. $name,
        'search_items' => 'Search Types of '. $plural_name,
        'all_items' => 'All Types of '. $plural_name,
        'parent_item' => $hierarchical ? 'Parent Type of '. $name : null,
        'parent_item_colon' => $hierarchical ? 'Parent Type of '. $name .':' : null,
        'edit_item' => 'Edit Type of '. $name,
        'update_item' => 'Update Type of '. $name,
        'add_new_item' => 'Add New Type of '. $name,
        'new_item_name' => 'New Edit Type of '. $name,
        'menu_name' => 'Type of '. $name,
    );

    $arguments = array(
        'labels' => $labels,
        'hierarchical' => $hierarchical,
        'show_ui' => array_key_exists('show_ui', $args) ? $args['show_ui'] : true,
        'show_admin_column' => array_key_exists('show_admin_column', $args) ? $args['show_admin_column'] : true,
        'query_var' => array_key_exists('query_var', $args) ? $args['query_var'] : true,
        'rewrite' => array_key_exists('rewrite', $args) ? $args['rewrite'] : array('slug' => $lower_case_name),
    );

    $register_name = array_key_exists('register_name', $args) ? $args['register_name'] : $lower_case_name;

    register_taxonomy($register_name, $object_type, $arguments);
}