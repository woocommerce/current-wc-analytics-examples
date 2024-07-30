<?php

namespace WcAnalyticsProductsExample\Admin\Analytics;

/**
 * WcAnalyticsProductsExample Rest_API Class
 */
class Rest_API {
	/**
	 * Constructor.
	 */
	public function __construct() {
		// Register REST API Controllers for retrieving analytics data by frontend.
		add_filter( 'woocommerce_admin_rest_controllers', array( $this, 'add_rest_api_controllers' ), 2 );

		// Register data stores.
		add_filter( 'woocommerce_data_stores', array( $this, 'register_data_stores' ) );
	}

	/**
	 * Adds Analytics REST contollers.
	 * To be used with `woocommerce_admin_rest_controllers` filter.
	 *
	 * @param  array $controllers An array of controller classes.
	 * @return array Extended with AW Analytics controllers.
	 */
	public function add_rest_api_controllers( $controllers ) {
		$controllers[] = 'WcAnalyticsProductsExample\Admin\Analytics\Rest_API\Controller';
		$controllers[] = 'WcAnalyticsProductsExample\Admin\Analytics\Rest_API\StatsController';
		return $controllers;
	}

	/**
	 * Register Analytics data stores.
	 * To be used with `woocommerce_data_stores` filter.
	 *
	 * @param  array $stores An array of data store classes.
	 * @return array Extended with AW Analytics stores.
	 */
	public function register_data_stores( $stores ) {
		$stores['report-products-example'] = 'WcAnalyticsProductsExample\Admin\Analytics\Rest_API\DataStore';
		$stores['report-products-example-stats'] = 'WcAnalyticsProductsExample\Admin\Analytics\Rest_API\StatsDataStore';
		return $stores;
	}
}
