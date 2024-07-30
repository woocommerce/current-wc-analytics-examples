<?php

namespace WcAnalyticsProductsExample\Admin\Analytics\Rest_API;

use Automattic\WooCommerce\Admin\API\Reports\DataStore as ReportsDataStore;
use Automattic\WooCommerce\Admin\API\Reports\DataStoreInterface;
use Automattic\WooCommerce\Admin\API\Reports\StatsDataStoreTrait;

/**
 * Data store for analytics products example stats.
 */
class StatsDataStore extends ReportsDataStore implements DataStoreInterface {
	use StatsDataStoreTrait;

	/**
	 * Cache identifier.
	 *
	 * @var string
	 */
	protected $cache_key = 'products_example_stats';

	/**
	 * Data store context used to pass to filters.
	 *
	 * @var string
	 */
	protected $context = 'products_example_stats';

	/**
	 * Returns the report data based on normalized parameters.
	 * Will be called by `get_data` if there is no data in cache.
	 *
	 * @override ProductsDataStore::get_noncached_data()
	 *
	 * @see get_data
	 * @see get_noncached_stats_data
	 * @param array    $query_args Query parameters.
	 * @param array    $params                  Query limit parameters.
	 * @param stdClass $data                    Reference to the data object to fill.
	 * @param int      $expected_interval_count Number of expected intervals.
	 * @return stdClass|WP_Error Data object `{ totals: *, intervals: array, total: int, pages: int, page_no: int }`, or error.
	 */
	public function get_noncached_stats_data( $query_args, $params, &$data, $expected_interval_count ) {
		return (object) array(
			'totals'    => (object) array(
				'items_sold' => 2,
				'net_revenue'  => 100,
			),
			'intervals' => array(
				array(
					'date_start'     => '2024-07-22 00:00:00',
					'date_start_gmt' => '2024-07-22 00:00:00',
					'date_end'       => '2024-07-22 23:59:59',
					'date_end_gmt'   => '2024-07-22 23:59:59',
					'interval'       => '2024-07-22',
					'subtotals'      => array(
						'items_sold' => 0,
						'segments'   => array()
					),
				),
				array(
					'date_start'     => '2024-07-23 00:00:00',
					'date_start_gmt' => '2024-07-23 00:00:00',
					'date_end'       => '2024-07-23 23:59:59',
					'date_end_gmt'   => '2024-07-23 23:59:59',
					'interval'       => '2024-07-23',
					'subtotals'      => array(
						'items_sold' => 0,
						'segments'   => array()
					),
				),
				array(
					'date_start'     => '2024-07-24 00:00:00',
					'date_start_gmt' => '2024-07-24 00:00:00',
					'date_end'       => '2024-07-24 23:59:59',
					'date_end_gmt'   => '2024-07-24 23:59:59',
					'interval'       => '2024-07-24',
					'subtotals'      => array(
						'items_sold' => 2,
						'net_revenue'  => 100,
						'segments'   => array()
					),
				),
				array(
					'date_start'     => '2024-07-25 00:00:00',
					'date_start_gmt' => '2024-07-25 00:00:00',
					'date_end'       => '2024-07-25 23:59:59',
					'date_end_gmt'   => '2024-07-25 23:59:59',
					'interval'       => '2024-07-25',
					'subtotals'      => array(
						'items_sold' => 0,
						'segments'   => array()
					),
				),
				array(
					'date_start'     => '2024-07-26 00:00:00',
					'date_start_gmt' => '2024-07-26 00:00:00',
					'date_end'       => '2024-07-26 23:59:59',
					'date_end_gmt'   => '2024-07-26 23:59:59',
					'interval'       => '2024-07-26',
					'subtotals'      => array(
						'items_sold' => 0,
						'segments'   => array()
					),
				),
			),
			'total'     => 1,
			'page_no'   => 1,
			'pages'     => 1,
		);
	}
}
