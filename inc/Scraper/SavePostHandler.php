<?php

/**
 * Save Post Handler.
 * This file is reponsible for handling the save/update from the posts.
 * 
 * @package Custom_Content_Scraper
 */

namespace Custom_Content_Scraper\Scraper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SavePostHandler {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'save_post', array( $this, 'queue_save_post_meta' ), 10, 2 );
		add_action( 'custom_wp_schedule_single_event', array( $this, 'save_post_meta' ) );
	}

	/**
	 * Update field wrapper.
	 * 
	 * @param int $post_id
	 * @param string $field_key
	 * @param mixed $field_value
	 */
	public function update_field( $post_id, $field_key, $field_value ) {
		if ( function_exists( 'update_field' ) ) {
			update_field( $field_key, $field_value, $post_id );
		} else {
			update_post_meta( $post_id, $field_key, $field_value );
		}
	}

	/**
	 * Queue the task for saving post meta using Action Scheduler.
	 * 
	 * @param int $post_id
	 * @param \WP_Post $post
	 */
	public function queue_save_post_meta( $post_id, $post ) {
		if ( ! wp_next_scheduled( 'custom_wp_schedule_single_event', array( $post_id ) ) ) {
			wp_schedule_single_event( time() + 5, 'custom_wp_schedule_single_event', array( $post_id ) );
		}

		// The schedule must be registered before the validation here.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
	}

	/**
	 * Save post meta.
	 *
	 * @param int $post_id
	 * @param \WP_Post $post
	 */
	public function save_post_meta( $post_id ) {
        $last_user = get_post_meta( $post_id, 'last_user', true );
        $current_user = get_current_user_id();

        if ( $last_user !== $current_user ) {
            update_post_meta( $post_id, 'last_user', $current_user );
        }

        $current_date = gmdate( 'Y-m-d H:i:s' );
        update_post_meta( $post_id, 'last_update_date', $current_date );
    }
}
