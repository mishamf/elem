<?php
function create_custom_post_type() {
    register_post_type( 'el_products',
        array(
            'labels' => array(
                'name' => __( 'Products' ),
                'singular_name' => __( 'Product' )
            ),
            'public' => true,
            'supports' => array( 'title', 'editor', 'thumbnail' ),
            'menu_icon' => 'dashicons-cart',
        )
    );
}
add_action( 'init', 'create_custom_post_type' );