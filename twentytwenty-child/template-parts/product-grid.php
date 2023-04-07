<?php 
echo '<div class="el-container">';

$args = array(
    'post_type' => 'el_products',
    'post_status' => 'publish',
    'no_found_rows' => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'posts_per_page' => 6,
    'fields' => 'ids',
);
$query = new WP_Query( $args );
if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
        $post_id = $query->the_post();
        get_template_part( 'template-parts/product', 'card', ['id' => $post_id] );
    }
}
wp_reset_postdata();

echo '</div>';
