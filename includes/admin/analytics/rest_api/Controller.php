<?php

namespace WcAnalyticsProductsExample\Admin\Analytics\Rest_API;

/**
 * REST API Reports products example controller
 *
 * Handles requests to the /reports/products-example endpoint.
 *
 * @package wc-analytics-products-example
 */

defined( 'ABSPATH' ) || exit;

use Automattic\WooCommerce\Admin\API\Reports\GenericQuery;
use Automattic\WooCommerce\Admin\API\Reports\GenericController;

/**
 * REST API Reports products example controller class.
 *
 * @extends GenericController
 */
class Controller extends GenericController {

	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'reports/products-example';

	/**
	 * Data store name.
	 *
	 * @var string
	 */
	private $datastore_name = 'products-example';

	/**
	 * Forwards a Products Example Query constructor.
	 *
	 * @param array $query_args Set of args to be forwarded to the constructor.
	 * @return GenericQuery
	 */
	protected function construct_query( $query_args ) {
		return new GenericQuery( $query_args, $this->datastore_name );
	}

	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'report_products_example',
			'type'       => 'object',
			'properties' => array(
				'product_id' => array(
					'type'        => 'integer',
					'readonly'    => true,
					'context'     => array( 'view', 'edit' ),
					'description' => __( 'Product ID.', 'woocommerce' ),
				),
				'items_sold' => array(
					'type'        => 'integer',
					'readonly'    => true,
					'context'     => array( 'view', 'edit' ),
					'description' => __( 'Number of items sold.', 'woocommerce' ),
				),
			),
		);

		return $this->add_additional_fields_schema( $schema );
	}
}
