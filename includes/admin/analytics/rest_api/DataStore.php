<?php

namespace WcAnalyticsProductsExample\Admin\Analytics\Rest_API;

use Automattic\WooCommerce\Admin\API\Reports\DataStore as ReportsDataStore;
use Automattic\WooCommerce\Admin\API\Reports\DataStoreInterface;

/**
 * Data store for analytics products example.
 */
class DataStore extends ReportsDataStore implements DataStoreInterface {

	/**
	 * Cache identifier.
	 *
	 * @var string
	 */
	protected $cache_key = 'products_example';

	/**
	 * Data store context used to pass to filters.
	 *
	 * @var string
	 */
	protected $context = 'products_example';

	/**
	 * Returns the report data based on normalized parameters.
	 * Will be called by `get_data` if there is no data in cache.
	 *
	 * @override ReportsDataStore::get_noncached_data()
	 *
	 * @see get_data
	 * @param array $query_args Query parameters.
	 * @return stdClass|WP_Error Data object `{ totals: *, intervals: array, total: int, pages: int, page_no: int }`, or error.
	 */
	public function get_noncached_data( $query ) {
		$product_data = array(
			array(
				'product_id' => 11,
				'items_sold' => 1,
			),
			array(
				'product_id' => 12,
				'items_sold' => 1,
			),
		);

		return (object) array(
			'data' => $product_data,
			'total' => 1,
			'page_no' => 1,
			'pages' => 1,
		);
	}
}
