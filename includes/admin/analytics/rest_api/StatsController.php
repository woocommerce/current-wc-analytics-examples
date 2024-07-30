<?php

namespace WcAnalyticsProductsExample\Admin\Analytics\Rest_API;

/**
 * REST API Reports products example stats controller
 *
 * Handles requests to the /reports/products-example/stats endpoint.
 *
 * @package wc-analytics-products-example
 */

defined( 'ABSPATH' ) || exit;

use Automattic\WooCommerce\Admin\API\Reports\GenericQuery;
use Automattic\WooCommerce\Admin\API\Reports\GenericStatsController;

/**
 * REST API Reports products example stats controller class.
 *
 * @extends GenericStatsController
 */
class StatsController extends GenericStatsController {

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'reports/products-example/stats';

	/**
	 * Data store name.
	 *
	 * @var string
	 */
	private $datastore_name = 'products-example-stats';

	/**
	 * Forwards a Products Example Query constructor.
	 *
	 * @param array $query_args Set of args to be forwarded to the constructor.
	 * @return GenericQuery
	 */
	protected function construct_query( $query_args ) {
		return new GenericQuery( $query_args, $this->datastore_name );
	}

	/**
	 * Get the Report's item properties schema.
	 * Will be used by `get_item_schema` as `totals` and `subtotals`.
	 *
	 * @override
	 *
	 * @return array
	 */
	protected function get_item_properties_schema() {
		return array(
			'items_sold' => array(
				'title'       => __( 'Products sold', 'woocommerce' ),
				'type'        => 'integer',
				'readonly'    => true,
				'context'     => array( 'view', 'edit' ),
				'description' => __( 'Number of items sold.', 'woocommerce' ),
				'indicator'    => true,
			),
			'net_revenue' => array(
				'title'       => __( 'Net revenue', 'woocommerce' ),
				'type'        => 'integer',
				'readonly'    => true,
				'context'     => array( 'view', 'edit' ),
				'description' => __( 'Net revenue.', 'woocommerce' ),
				'indicator'    => true,
			),
		);
	}

	public function get_item_schema() {
		$schema          = parent::get_item_schema();
		$schema['title'] = 'report_products_example_stats';

		return $this->add_additional_fields_schema( $schema );
	}
}
