<?php
/**
 * The Notice main class.
 * This class works as a base for all notices.
 * 
 * @package Custom_Content_Scraper
 */

namespace Custom_Content_Scraper\Notice;
 
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Notice {

	/**
	 * The notice ID.
	 * 
	 */
	public $id = '';

	/**
	 * Display conditions.
	 * 
	 */
	public $display_conditions = array( 
		'woocommerce_page_wc-settings', 
		'index.php', 
		'plugins.php', 
		'edit.php', 
		'plugin-install.php', 
	);

	/**
	 * End date target.
	 * 
	 */
	public $end_date_target = '';

	/**
	 * The single class instance.
	 * 
	 */
	private static $instance = null;
	
	/**
	 * Instance.
	 * 
	 * @return Notice
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 * 
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'notice' ), 20 );
        add_action( 'admin_init', array( $this, 'dimiss_notice' ), 0 );
	}

	/**
	 * Get notice dismiss status.
	 * 
	 */
	public function is_notice_dismissed() {
		$user_id = get_current_user_id();

		return get_user_meta( $user_id, $this->id . '_dismiss', true ) ? true : false;
	}

	/**
	 * Notice.
	 * 
	 * @return void
	 */
	public function notice() {
		$dismissed_notice = $this->is_notice_dismissed();

		if ( $dismissed_notice ) {
			return;
		}

		// Display Conditions
		global $hook_suffix;

		if( ! in_array( $hook_suffix, $this->display_conditions, true ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if( $hook_suffix === 'edit.php' && ! isset( $_GET[ 'post_type' ] ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if( $hook_suffix === 'edit.php' && ( isset( $_GET[ 'post_type' ] ) && $_GET[ 'post_type' ] !== 'product' ) ) {
			return;
		}

		$this->notice_markup();
	}

	/**
	 * Notice HTML markup.
	 * 
	 */
	public function notice_markup() {}

    /**
	 * Dismiss notice permanently
	 * 
	 * @return void
	 */
	public function dimiss_notice() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET[$this->id . '_dismiss'] ) && '1' === $_GET[$this->id . '_dismiss'] ) {
			add_user_meta( get_current_user_id(), $this->id . '_dismiss', 'true', true );
		}
	}
}