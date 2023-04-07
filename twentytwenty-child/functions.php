<?php
require_once __DIR__ . '/app/index.php';

function el_enqueue_styles() {
    wp_enqueue_style( 'el-style', get_stylesheet_directory_uri() . '/dist/css/main.css' );
}
add_action( 'wp_enqueue_scripts', 'el_enqueue_styles' );