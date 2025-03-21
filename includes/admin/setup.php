<?php

namespace WcAnalyticsProductsExample\Admin;

/**
 * WcAnalyticsProductsExample Setup Class
 */
class Setup {
	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_menu', array( $this, 'register_page' ) );
		add_filter( 'woocommerce_analytics_report_menu_items', array( $this, 'add_analytics_report_menu_item' ) );
	}

	/**
	 * Add 'Products Example' under WC Analytics.
	 *
	 * @param array $reports Tabs
	 */
	public function add_analytics_report_menu_item( $reports ) {
		$products_example_report = array(
			'id'     => 'woocommerce-analytics-products-example',
			'title'  => __( 'Products Example', 'woocommerce-analytics-products-example' ),
			'parent' => 'woocommerce-analytics',
			'path'   => '/analytics/products-example',
		);

		// Ensure "Products Example" is located before "Settings".
		$settings_key = array_search( 'woocommerce-analytics-settings', array_column( $reports, 'id' ), true );
		array_splice( $reports, $settings_key, 0, array( $products_example_report ) );

		return $reports;
	}

	/**
	 * Load all necessary dependencies.
	 *
	 * @since 1.0.0
	 */
	public function register_scripts() {
		if ( ! method_exists( 'Automattic\WooCommerce\Admin\PageController', 'is_admin_or_embed_page' ) ||
		! \Automattic\WooCommerce\Admin\PageController::is_admin_or_embed_page()
		) {
			return;
		}

		$script_path       = '/build/index.js';
		$script_asset_path = dirname( MAIN_PLUGIN_FILE ) . '/build/index.asset.php';
		$script_asset      = file_exists( $script_asset_path )
		? require $script_asset_path
		: array(
			'dependencies' => array(),
			'version'      => filemtime( $script_path ),
		);
		$script_url        = plugins_url( $script_path, MAIN_PLUGIN_FILE );

		wp_register_script(
			'wc-analytics-products-example',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_register_style(
			'wc-analytics-products-example',
			plugins_url( '/build/index.css', MAIN_PLUGIN_FILE ),
			// Add any dependencies styles may have, such as wp-components.
			array(),
			filemtime( dirname( MAIN_PLUGIN_FILE ) . '/build/index.css' )
		);

		wp_enqueue_script( 'wc-analytics-products-example' );
		wp_enqueue_style( 'wc-analytics-products-example' );
	}

	/**
	 * Register page in wc-admin.
	 *
	 * @since 1.0.0
	 */
	public function register_page() {

		if ( ! function_exists( 'wc_admin_register_page' ) ) {
			return;
		}

		wc_admin_register_page(
			array(
				'id'     => 'wc_analytics_products_example-example-page',
				'title'  => __( 'Wc Analytics Products Example', 'wc_analytics_products_example' ),
				'parent' => 'woocommerce',
				'path'   => '/wc-analytics-products-example',
			)
		);
	}
}
