<?php
/**
 * Plugin Name: Custom Content Scraper
 * Plugin URI:  https://github.com/maaprote
 * Description: A custom content scraper for pages.
 * Version:     1.0.0
 * Author:      Rodrigo Teixeira
 * Author URI:  https://github.com/maaprote
 * License:     GPLv3 or later License
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: custom-content-scraper
 * Domain Path: /languages
 *
 * @package Custom_Content_Scraper
 * @since 1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CCS_VERSION', '1.10.4' );
define( 'CCS_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'CCS_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

use Custom_Content_Scraper\Scraper\RequiredPluginsNotice;
use Custom_Content_Scraper\Scraper\Integration\AdvancedCustomFields;
use Custom_Content_Scraper\Scraper\CheckButton;
use Custom_Content_Scraper\Scraper\SavePostHandler;

class Custom_Content_Scraper {

    /**
     * Constructor.
     * 
     */
    public function __construct() {
        require 'vendor/autoload.php';
        
        if ( ! $this->has_required_plugins() ) {
            new RequiredPluginsNotice();

            return;
        }

        new AdvancedCustomFields();
        new CheckButton();
        new SavePostHandler();
    }

    /**
     * Has required scraper plugins activated?
     * 
     * @return bool
     */
    public function has_required_plugins() {
        return class_exists( 'acf' );
    }
}

new Custom_Content_Scraper();