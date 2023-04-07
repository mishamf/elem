<?php
if($args['id']){
    return '';
}

$sale_price = get_post_meta( $id, 'custom_metabox_value_sale_price', true );
$thumbnail = get_the_post_thumbnail( $id, 'large', array( 'class' => 'post-thumbnail' ) );
$url = get_permalink($id);

$html_output = '';
$html_output .= '<div class="el-product-item">';
$html_output .= '<a href="'. $url .'">';

$html_output .= $thumbnail;
$html_output .= '<h2>' . get_the_title($id) . '</h2>';
$html_output .= '<p>' . $price_html . '</p>';

if(!empty($sale_price)){
    $html_output .= '<h5 class="el-product-item_sale-badge">Product on Sale!</h5>';
}
$html_output .= '</a>';
$html_output .= '</div>';


echo $html_output;