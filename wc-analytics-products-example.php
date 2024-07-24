<?php
/**
 * Plugin Name: WC Analytics Products Example
 * Version: 0.1.0
 * Author: The WordPress Contributors
 * Author URI: https://woo.com
 * Text Domain: wc-analytics-products-example
 * Domain Path: /languages
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package extension
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'MAIN_PLUGIN_FILE' ) ) {
	define( 'MAIN_PLUGIN_FILE', __FILE__ );
}

require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload_packages.php';

use WcAnalyticsProductsExample\Admin\Setup;

// phpcs:disable WordPress.Files.FileName

/**
 * WooCommerce fallback notice.
 *
 * @since 0.1.0
 */
function wc_analytics_products_example_missing_wc_notice() {
	/* translators: %s WC download URL link. */
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Wc Analytics Products Example requires WooCommerce to be installed and active. You can download %s here.', 'wc_analytics_products_example' ), '<a href="https://woo.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
}

register_activation_hook( __FILE__, 'wc_analytics_products_example_activate' );

/**
 * Activation hook.
 *
 * @since 0.1.0
 */
function wc_analytics_products_example_activate() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'wc_analytics_products_example_missing_wc_notice' );
		return;
	}
}

if ( ! class_exists( 'wc_analytics_products_example' ) ) :
	/**
	 * The wc_analytics_products_example class.
	 */
	class wc_analytics_products_example {
		/**
		 * This class instance.
		 *
		 * @var \wc_analytics_products_example single instance of this class.
		 */
		private static $instance;

		/**
		 * Constructor.
		 */
		public function __construct() {
			if ( is_admin() ) {
				new Setup();
			}
		}

		/**
		 * Cloning is forbidden.
		 */
		public function __clone() {
			wc_doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'wc_analytics_products_example' ), $this->version );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 */
		public function __wakeup() {
			wc_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'wc_analytics_products_example' ), $this->version );
		}

		/**
		 * Gets the main instance.
		 *
		 * Ensures only one instance can be loaded.
		 *
		 * @return \wc_analytics_products_example
		 */
		public static function instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
endif;

add_action( 'plugins_loaded', 'wc_analytics_products_example_init', 10 );

/**
 * Initialize the plugin.
 *
 * @since 0.1.0
 */
function wc_analytics_products_example_init() {
	load_plugin_textdomain( 'wc_analytics_products_example', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'wc_analytics_products_example_missing_wc_notice' );
		return;
	}

	wc_analytics_products_example::instance();

}
