<?php
// Register Custom Post Type
function events_post_type() {
  $labels = array(
    'name'                => _x( 'Arrangementer', 'Post Type General Name', 'text_domain' ),
    'singular_name'       => _x( 'Arrangement', 'Post Type Singular Name', 'text_domain' ),
    'menu_name'           => __( 'Arrangementer', 'text_domain' ),
    'name_admin_bar'      => __( 'Arrangementer', 'text_domain' ),
    'parent_item_colon'   => __( 'Arrangement', 'text_domain' ),
    'all_items'           => __( 'Alle Arrangementer', 'text_domain' ),
    'add_new_item'        => __( 'Tilføj Arrangement', 'text_domain' ),
    'add_new'             => __( 'Tilføj ny', 'text_domain' ),
    'new_item'            => __( 'Nyt Arrangement', 'text_domain' ),
    'edit_item'           => __( 'Rediger Arrangement', 'text_domain' ),
    'update_item'         => __( 'Opdater Arrangement', 'text_domain' ),
    'view_item'           => __( 'Vis Arrangement', 'text_domain' ),
    'search_items'        => __( 'Find Arrangement', 'text_domain' ),
    'not_found'           => __( 'Ikke fundet', 'text_domain' ),
    'not_found_in_trash'  => __( 'Ikke fundet i papirkurv', 'text_domain' ),
  );
  $rewrite = array(
    'slug'                => 'reservationer',
    'with_front'          => true,
    'pages'               => true,
    'feeds'               => true,
  );
  $args = array(
    'label'               => __( 'Arrangement', 'text_domain' ),
    'description'         => __( 'Arrangementer', 'text_domain' ),
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
  register_post_type( 'inz_events', $args );
}
add_action( 'init', 'events_post_type', 0 );
