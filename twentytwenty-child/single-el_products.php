<?php
get_header();

$id = get_the_ID();
$price = get_post_meta( $id, 'custom_metabox_value_price', true );
$sale_price = get_post_meta( $id, 'custom_metabox_value_sale_price', true );
$thumbnail = get_the_post_thumbnail( $id, 'large', array( 'class' => 'post-thumbnail' ) );
$url = get_permalink($id);
$gallery = get_post_meta( $id, 'custom_metabox_field_product_gallery', true );
$term = get_the_terms($id, 'el_products_categories');
$video = get_post_meta($id, 'custom_metabox_value_youtube', true);

$html_output = '';
$html_output .= '<div class="el-product">';

$html_output .= $thumbnail;
$html_output .= '<h2>' . get_the_title() . '</h2>';
$html_output .= '<p>' . $price_html . '</p>';

$price_html = '<b>' . $price . '</b>'; 

if(!empty($sale_price)){
    $price_html = '<strike>' . $price . '</strike><br>';
    $price_html .= '<b>' . $sale_price . '</b>'; 
    $price_html .= '<h5 class="el-product_sale-badge">Product on Sale!</h5>';
}

$html_output .= $price_html;

if(!empty($gallery)){
    $gallery_ids = explode(',',$gallery);
    echo '<div class="el-product_gallery">';
    foreach($gallery_ids as $image){
       echo wp_get_attachment_image( $image, 'large');
    }
    echo '</div>';
}

if(!empty($video)){
    echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $video . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
}

$html_output .= '</div>';

echo $html_output;
echo '<div class="el-description">';
the_content();
echo '</div>';

if(!empty($term)){
    get_template_part( 'template-parts/product', 'grid', ['category' => $term[0]->term_id] );
}

get_footer();
