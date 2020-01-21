<?php
function assign_event_post_type()
{
    $labels = array(
        'name'               => __('Photos'),
        'singular_name'      => __('Photos'),
        'add_new'            => __('Add New Photos'),
        'add_new_item'       => __('Add New Photos'),
        'edit_item'          => __('Edit Photos'),
        'new_item'           => __('Add New Photos'),
        'view_item'          => __('View Photos'),
        'search_items'       => __('Search Photos'),
        'not_found'          => __('No Photos found'),
        'not_found_in_trash' => __('No Photos found in trash')
    );
    $supports = array(
        'title',
        'thumbnail',
        'page-attributes',
        'revisions',
    );
    $args = array(
        'labels'               => $labels,
        'supports'             => $supports,
        'public'               => true,
        'capability_type'      => 'post',
        'rewrite'              => array('slug' => 'photos'),
        'has_archive'          => true,
        'menu_position'        => 30,
        'menu_icon'            => 'dashicons-camera-alt'
    );
    register_post_type('photos', $args);
}
add_action('init', 'assign_event_post_type');
