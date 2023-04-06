<?php

function products_endpoint( $request ) {
    $category = $request->get_param( 'category' );
    $args = array(
        'post_type' => 'el_products',
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'el_products_categories',
                'field' => is_numeric( $category ) ? 'term_id' : 'slug',
                'terms' => $category,
            ),
        ),
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'posts_per_page' => 100
    );
    $query = new WP_Query( $args );

    $products = [];
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            if ( has_term( 'is_on_sale', 'el_products_sales', $id ) ) {
                $is_on_sale = true;
            } else {
                $is_on_sale = false;
            }
            $product = array(
                'title' => get_the_title(),
                'description' => get_the_content(),
                'image' => get_the_post_thumbnail_url(),
                'price' => get_post_meta( $id, 'custom_metabox_value_price', true ),
                'is_on_sale' => $is_on_sale,
                'sale_price' => get_post_meta( $id, 'custom_metabox_value_sale_price', true ),
            );
            array_push( $products, $product );
        }
    }
    wp_reset_postdata();
    return new WP_REST_Response($products);    
}

function product_register_endpoints() {

    register_rest_route( 'api/v1', '/el_products/(?P<category>\d+|[a-zA-Z-]+)', [
        'methods' => 'GET',
        'callback' => 'products_endpoint',
    ] );

}
add_action( 'rest_api_init', 'product_register_endpoints' );
