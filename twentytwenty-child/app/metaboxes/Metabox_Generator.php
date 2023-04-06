<?php

namespace app;

class Metabox_Generator {

    function __construct($title, $id, $metaboxes, $post_type = 'el_products'){
        $this->title = $title;
        $this->id = $id;
        $this->post_type = $post_type;
        $this->metaboxes = $metaboxes;

        add_action( 'add_meta_boxes', [$this,'add_custom_metabox'] );
        add_action( 'save_post', [$this,'save_custom_metabox'] );
    }

    // Add the metabox to the post editor screen
    function add_custom_metabox() {
        add_meta_box(
            $this->id,
            $this->title,
            [$this, 'custom_metabox_callback'],
            $this->post_type,
            'normal',
            'default'
        );
    }

    // Output the metabox content
    function custom_metabox_callback( $post ) {
        wp_nonce_field( 'save-post-' . $post->ID,  '_wpnonce_meta_boxes' );

        $outout = '<ul>';
        foreach ($this->metaboxes as $metabox){
            $outout .= '<li>';
            $value = get_post_meta( $post->ID, 'custom_metabox_value_'. $metabox['id'], true );
            if(empty($metabox['type']) || $metabox['type'] === 'text'){
                $outout .= '<label style="min-width:140px; display:inline-block" for="custom_metabox_field_'.$metabox['id'].'">' . $metabox['title'] . '</label> ';
                $outout .= '<input type="text" id="custom_metabox_field_'.$metabox['id'].'" name="custom_metabox_field_'.$metabox['id'].'" value="' . esc_attr( $value ) . '" size="25" />';        
            }

            if( $metabox['type'] === 'gallery'){
                $outout .= '<label style="min-width:140px; display:inline-block" for="custom_metabox_field_'.$metabox['id'].'">' . $metabox['title'] . '</label> ';
                $outout .= '<input type="file" multiple id="custom_metabox_field_'.$metabox['id'].'" name="custom_metabox_field_'.$metabox['id'].'" value="' . esc_attr( $value ) . '" size="100" />';        
            }
            
           $outout .= '</li>';
        }
        $outout .= '</ul>';

        echo $outout;
    }

    // Save the metabox value
    function save_custom_metabox( $post_id ) {
        if ( ! isset( $_POST['_wpnonce_meta_boxes'] ) || ! wp_verify_nonce( $_POST['_wpnonce_meta_boxes'], 'save-post-' . $post_id) ) {
            return '';
        }
        $post_type = get_post_type( $post_id );
        if ( ( $this->post_type !== $post_type ) || !current_user_can( 'edit_page', $post_id ) ) {
            return '';
        } 


        // TODO: add validation there for texts and int fields.
        foreach ($this->metaboxes as $metabox){

            if($metabox['type'] === 'gallery'){
                if ( ! isset( $_FILES['custom_metabox_field_product_gallery'] ) ) {
                    return;
                }
                $attachment_ids = [];
                $files = $_FILES['my_files'];
                $attachment_ids = [];
                foreach ( $files['name'] as $index => $name ) {
                    if ( $files['error'][$index] !== UPLOAD_ERR_OK ) {
                        continue;
                    }
                    $file_info = wp_handle_upload( $files, array( 'test_form' => false ) );
                    if ( ! isset( $file_info['file'] ) ) {
                        continue;
                    }
                    $attachment_id = wp_insert_attachment( array(
                        'post_mime_type' => $file_info['type'],
                        'post_title' => sanitize_file_name( $file_info['file'] ),
                        'post_content' => '',
                        'post_status' => 'inherit',
                    ), $file_info['file'] );
                    if ( is_wp_error( $attachment_id ) ) {
                        continue;
                    }
                    $attachment_ids[] = $attachment_id;
                }
                $res = update_post_meta( $post_id, 'custom_metabox_value_'. $metabox['id'] , implode(',', $attachment_ids) );
            }

            $meta_value = sanitize_text_field( $_POST['custom_metabox_field_'. $metabox['id']] );
            $res = update_post_meta( $post_id, 'custom_metabox_value_'. $metabox['id'] , $meta_value );
        }   
        $term = get_term_by( 'slug', 'is_on_sale', 'el_products_sales' );
        
        if(empty($_POST['custom_metabox_field_sale_price'])){
            wp_remove_object_terms( $post_id, $term->term_id , 'el_products_sales' );
        } else {
            wp_set_post_terms( $post_id, $term->term_id, 'el_products_sales', true );
        }

    }

}