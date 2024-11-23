<?php

/**
 * Check button.
 * This file is responsible for rendering the check button in the admin.
 * 
 * @package Custom_Content_Scraper
 */

namespace Custom_Content_Scraper\Scraper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CheckButton {

    /**
     * Constructor.
     * 
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_js' ) );
        add_action( 'post_submitbox_start', array( $this, 'render_button' ) );
        add_action( 'wp_ajax_ccs_check_button', array( $this, 'ajax_callback' ) );
    }

    /**
     * Enqueue JS.
     * 
     */
    public function enqueue_js() {
        wp_enqueue_script( 'ccs-admin-js', CCS_URI . 'assets/js/scraper/check-button.js', array( 'jquery' ), CCS_VERSION, true );
    }

    /**
     * Ajax callback
     * 
     */
    public function ajax_callback() {
        check_ajax_referer( 'ccs-button-nonce', 'nonce' );

        $page_url = isset( $_POST['page_url'] ) ? esc_url_raw( $_POST['page_url'] ) : '';
        if ( empty( $page_url ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid URL', 'custom-content-scraper' ) ) );
        }

        $response = wp_remote_get( $page_url );
        if ( is_wp_error( $response ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Error fetching the page', 'custom-content-scraper' ) ) );
        }

        // The idea is to compare the last modified date of the page with the last update date of the post.
        // This is not a good solution whether we are dealing with third-party websites. But if we are dealing with our own website, it's a good solution.
        $last_modified_date = wp_remote_retrieve_header( $response, 'Last-Modified' );
        if ( ! $last_modified_date ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Last modified date not found', 'custom-content-scraper' ) ) );
        }

        $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
        if ( ! $post_id ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid post ID', 'custom-content-scraper' ) ) );
        }

        $last_update_date = get_field( 'last_update_date', $post_id );
        $is_new_content = strtotime( $last_update_date ) < strtotime( $last_modified_date );

        if ( ! $is_new_content ) {
            wp_send_json_error( array( 'message' => esc_html__( 'No new content found', 'custom-content-scraper' ) ) );
        }
        
        $body = wp_remote_retrieve_body( $response );
        $new_post_content = wp_strip_all_tags( $body );

        wp_send_json_success( 
            array( 
                'page_html_content' => $body, 
                'page_html_content_without_tags' => wp_strip_all_tags( $new_post_content ),
            ) 
        );
    }

    /**
     * Render button.
     * 
     * @return void
     */
    public function render_button() {
        global $post;

        if ( 'page' !== $post->post_type ) {
            return;
        }

        $page_url_to_load = 'https://www.terra.com.br/';

        ?>

        <div class="ccs-check-button">
            <button type="button" class="button button-primary" data-post-id="<?php echo absint( $post->ID ); ?>" data-page-url="<?php echo esc_attr( $page_url_to_load ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'ccs-button-nonce' ) ) ?>"><?php echo esc_html__( 'Check', 'custom-content-scraper' ); ?></button>
        </div>

        <?php
    }
}