<?php

/**
 * Required Plugins Notice.
 * This class is responsible for displaying the required plugins notice.
 * 
 * @package Custom_Content_Scraper
 */

namespace Custom_Content_Scraper\Scraper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Custom_Content_Scraper\Notice\Notice;

class RequiredPluginsNotice extends Notice {

    /**
	 * Constructor.
	 * 
	 */
	public function __construct() {
		$this->id        = 'merchant-campaign-notice';

		parent::__construct();
	}

    /**
     * The notice HTML output.
     * The advanced custom fields plugin URL is directing to their website due to Matt Mullenweg and WPEngine drama.
     * 
     * @return void
     */
    public function notice_markup() {
		?>

        <div class="notice" style="position:relative;">
			<p><?php esc_html_e( 'This plugin requires the following plugins:', 'custom-content-scraper' ); ?></p>
            <ul>
                <li><a href="https://wordpress.org/plugins/classic-editor/" target="_blank"><?php esc_html_e( 'Classic Editor', 'custom-content-scraper' ); ?></a></li>
                <li><a href="https://www.advancedcustomfields.com/" target="_blank"><?php esc_html_e( 'Advanced Custom Fields', 'custom-content-scraper' ); ?></a></li>
            </ul>

			<a class="notice-dismiss" href="?page=merchant&<?php echo esc_attr( $this->id ); ?>_dismiss=1" style="text-decoration:none;"></a>             
		</div>

        <?php
    }
}