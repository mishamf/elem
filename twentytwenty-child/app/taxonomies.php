<?php
function create_custom_taxonomy() {
    register_taxonomy(
        'el_products_categories',
        'el_products',
        array(
            'label' => __( 'Categories' ),
            'hierarchical' => true,
        )
    );


    // TODO: make not editable from Quick edit and Product edit page.
    register_taxonomy(
        'el_products_sales',
        'el_products',
        array(
            'label' => __( 'Product on sale' ),
            'hierarchical' => true,
        )
    );

    if ( ! term_exists( 'is_on_sale' , 'el_products_sales' ) ) {
        $term_name = 'Is on Sale';
        $taxonomy = 'el_products_sales'; // Replace with your desired taxonomy

        // Define the term arguments
        $term_args = array(
            'description' => 'Populated automatically depends on price/sale price',
            'slug' => 'is_on_sale',
        );

        $term = wp_insert_term( $term_name, $taxonomy, $term_args );

        // Check if the term was inserted successfully
        if ( is_wp_error( $term ) ) {
            error_log('Error: is on sale term doesn`t created');
        }
    } 
}
add_action( 'init', 'create_custom_taxonomy' );