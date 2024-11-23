<?php

/**
 * ACF Integration.
 * This file is reponsible for all ACF integration.
 * 
 * @package Custom_Content_Scraper
 */

namespace Custom_Content_Scraper\Scraper\Integration;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AdvancedCustomFields {

    /**
     * Constructor.
     * 
     */
    public function __construct() {
        if ( ! class_exists( 'acf' ) ) {
            return;
        }

        add_action( 'acf/include_fields', array( $this, 'register_fields' ) );
    }

    /**
     * Register fields.
     * 
     * @return void
     */
    public function register_fields() {
        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }
    
        acf_add_local_field_group( array(
            'key' => 'group_673d02f253c60',
            'title' => 'Page Fields',
            'fields' => array(
                array(
                    'key' => 'field_673d02f3e6dc1',
                    'label' => 'Last user',
                    'name' => 'last_user',
                    'aria-label' => '',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'allow_in_bindings' => 0,
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                array(
                    'key' => 'field_673d0306e6dc2',
                    'label' => 'Last update date',
                    'name' => 'last_update_date',
                    'aria-label' => '',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'allow_in_bindings' => 0,
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'page',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ) );
    }
}