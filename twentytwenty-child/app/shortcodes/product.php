<?php
function product_shortcode( $atts ) {
    // Set default attributes
    $atts = shortcode_atts( array(
      'id' => '',
      'bg_color' => ''
    ), $atts );
  
    if(empty($atts['id'])){
        return '';
    }

    $atts['id'] = apply_filters( 'product_box_id', $atts['id'] );

    $id = filter_var($atts['id'], FILTER_SANITIZE_NUMBER_INT);

    $thumbnail = get_the_post_thumbnail( $id, 'large', array( 'class' => 'post-thumbnail' ) );

  
    // Get box background color
    $bg_color = filter_var($atts['bg_color'], FILTER_SANITIZE_STRING);

    if(!empty($bg_color)){
        $bg_color = 'style="background-color:' . $bg_color . '"';
    }

    $price = get_post_meta( $id, 'custom_metabox_value_price', true );
    $sale_price = get_post_meta( $id, 'custom_metabox_value_sale_price', true );

    $price_html = '<b>' . $price . '</b>'; 

    if(!empty($sale_price)){
        $price_html = '<strike>' . $price . '</strike><br>';
        $price_html .= '<b>' . $sale_price . '</b>'; 
        $price_html .= '<h5 class="el-product_sale-badge">Product on Sale!</h5>';
    }

  
    // Output product box
    $output = '<div ' . $bg_color . '>';
    $output .= $thumbnail;
    $output .= '<h2>' . get_the_title($id) . '</h2>';
    $output .= '<p>' . $price_html . '</p>';
    $output .= '</div>';
  
    $output = apply_filters( 'product_box_html', $output );

    return $output;
  }
  add_shortcode( 'product_box', 'product_shortcode' );