<?php
// Register Custom Post Type
function events_post_type() {
  $labels = array(
    'name'                => _x( 'Events', 'Post Type General Name', 'github-Jursdotme-inzite-events-calendar' ),
    'singular_name'       => _x( 'Event', 'Post Type Singular Name', 'github-Jursdotme-inzite-events-calendar' ),
    'menu_name'           => __( 'Events', 'github-Jursdotme-inzite-events-calendar' ),
    'name_admin_bar'      => __( 'Events', 'github-Jursdotme-inzite-events-calendar' ),
    'parent_item_colon'   => __( 'Event', 'github-Jursdotme-inzite-events-calendar' ),
    'all_items'           => __( 'Alle Events', 'github-Jursdotme-inzite-events-calendar' ),
    'add_new_item'        => __( 'Tilføj Event', 'github-Jursdotme-inzite-events-calendar' ),
    'add_new'             => __( 'Tilføj ny', 'github-Jursdotme-inzite-events-calendar' ),
    'new_item'            => __( 'Ny Event', 'github-Jursdotme-inzite-events-calendar' ),
    'edit_item'           => __( 'Rediger Event', 'github-Jursdotme-inzite-events-calendar' ),
    'update_item'         => __( 'Opdater Event', 'github-Jursdotme-inzite-events-calendar' ),
    'view_item'           => __( 'Vis Event', 'github-Jursdotme-inzite-events-calendar' ),
    'search_items'        => __( 'Find Event', 'github-Jursdotme-inzite-events-calendar' ),
    'not_found'           => __( 'Ikke fundet', 'github-Jursdotme-inzite-events-calendar' ),
    'not_found_in_trash'  => __( 'Ikke fundet i papirkurv', 'github-Jursdotme-inzite-events-calendar' ),
  );
  $rewrite = array(
    'slug'                => __('events', 'github-Jursdotme-inzite-events-calendar'),
    'with_front'          => true,
    'pages'               => true,
    'feeds'               => true,
  );
  $args = array(
    'label'               => __( 'Event', 'github-Jursdotme-inzite-events-calendar' ),
    'description'         => __( 'Events', 'github-Jursdotme-inzite-events-calendar' ),
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    'taxonomies'          => array( ),
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'menu_position'       => 21,
    'menu_icon'           => 'dashicons-calendar-alt',
    'show_in_admin_bar'   => true,
    'show_in_nav_menus'   => true,
    'can_export'          => true,
    'has_archive'         => true,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'rewrite'             => $rewrite,
    'capability_type'     => array( 'event', 'events'),
    'map_meta_cap'        => true,
  );
  register_post_type( 'inzite_events', $args );
}
add_action( 'init', 'events_post_type', 0 );
