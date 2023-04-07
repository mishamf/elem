<?php 
echo '<div class="el-container">';

$wp_query_args = [
    'post_type' => 'el_products',
    'post_status' => 'publish',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'posts_per_page' => 6,
    'fields' => 'ids',
];

if(!empty($args['category'])){
    $term_id = filter_var($args['category'], FILTER_SANITIZE_NUMBER_INT);

    $wp_query_args['tax_query'] = [
        [
            'taxonomy' => 'el_products_categories',
            'field'    => 'id',
            'terms'    => $term_id,
        ]
    ];
}
$query = new WP_Query( $wp_query_args );
if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
        $post_id = $query->the_post();
        get_template_part( 'template-parts/product', 'card', ['id' => $post_id] );
    }
}
wp_reset_postdata();

echo '</div>';
