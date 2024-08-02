<?php

namespace WcAnalyticsProductsExample\Admin\Analytics\Rest_API;

use Automattic\WooCommerce\Admin\API\Reports\DataStoreInterface;
use Automattic\WooCommerce\Admin\API\Reports\StatsDataStoreTrait;
use Automattic\WooCommerce\Admin\API\Reports\TimeInterval;
use WcAnalyticsProductsExample\Admin\Analytics\Rest_API\DataStore as ProductsExampleDataStore;

/**
 * Data store for analytics products example stats.
 */
class StatsDataStore extends ProductsExampleDataStore implements DataStoreInterface {
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
	 * Mapping columns to data type to return correct response types.
	 *
	 * @override ProductsDataStore::$column_types
	 *
	 * @var array
	 */
	protected $column_types = array(
		'date_start'       => 'strval',
		'date_end'         => 'strval',
		'product_id'       => 'intval',
		'items_sold'       => 'intval',
		'net_revenue'      => 'floatval',
	);

	/**
	 * Assign report columns once full table name has been assigned.
	 *
	 * @override ProductsDataStore::assign_report_columns()
	 */
	protected function assign_report_columns() {
		$this->report_columns = array(
			'items_sold'       => 'SUM(product_qty) as items_sold',
			'net_revenue'      => 'SUM(product_net_revenue) AS net_revenue',
		);
	}

	/**
	 * Normalizes order_by clause to match to SQL query.
	 *
	 * @override ProductsDataStore::normalize_order_by()
	 *
	 * @param string $order_by Order by option requeste by user.
	 * @return string
	 */
	protected function normalize_order_by( $order_by ) {
		if ( 'date' === $order_by ) {
			return 'time_interval';
		}

		return $order_by;
	}

	/**
	 * Updates the database query with parameters used for Products Stats report: categories and order status.
	 *
	 * @param array $query_args       Query arguments supplied by the user.
	 */
	protected function update_sql_query_params( $query_args ) {
		$order_product_lookup_table = self::get_db_table_name();
		$this->add_time_period_sql_params( $query_args, $order_product_lookup_table );
		$this->add_intervals_sql_params( $query_args, $order_product_lookup_table );
		$this->interval_query->add_sql_clause( 'select', $this->get_sql_clause( 'select' ) . ' AS time_interval' );
	}

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
		global $wpdb;

		$table_name = self::get_db_table_name();

		$this->initialize_queries();

		$selections = $this->selected_columns( $query_args );

		$this->update_sql_query_params( $query_args );
		$this->get_limit_sql_params( $query_args );
		$this->interval_query->add_sql_clause( 'where_time', $this->get_sql_clause( 'where_time' ) );

		$db_intervals = $wpdb->get_col(
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- cache ok, DB call ok, unprepared SQL ok.
			$this->interval_query->get_query_statement()
		);

		$db_interval_count = count( $db_intervals );

		$intervals = array();
		$this->update_intervals_sql_params( $query_args, $db_interval_count, $expected_interval_count, $table_name );
		$this->total_query->add_sql_clause( 'select', $selections );
		$this->total_query->add_sql_clause( 'where_time', $this->get_sql_clause( 'where_time' ) );

		$totals = $wpdb->get_results(
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- cache ok, DB call ok, unprepared SQL ok.
			$this->total_query->get_query_statement(),
			ARRAY_A
		);

		wc_get_logger()->info( sprintf( 'totals: %s', $this->total_query->get_query_statement() ) );

		if ( null === $totals ) {
			return new \WP_Error( 'woocommerce_analytics_products_example_stats_result_failed', __( 'Sorry, fetching revenue data failed.', 'woocommerce' ) );
		}

		$this->interval_query->add_sql_clause( 'order_by', $this->get_sql_clause( 'order_by' ) );
		$this->interval_query->add_sql_clause( 'limit', $this->get_sql_clause( 'limit' ) );
		$this->interval_query->add_sql_clause( 'select', ", MAX({$table_name}.date_created) AS datetime_anchor" );
		if ( '' !== $selections ) {
			$this->interval_query->add_sql_clause( 'select', ', ' . $selections );
		}

		$intervals = $wpdb->get_results(
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- cache ok, DB call ok, unprepared SQL ok.
			$this->interval_query->get_query_statement(),
			ARRAY_A
		);
		wc_get_logger()->info( sprintf( 'intervals: %s', $this->interval_query->get_query_statement() ) );

		if ( null === $intervals ) {
			return new \WP_Error( 'woocommerce_analytics_products_stats_result_failed', __( 'Sorry, fetching revenue data failed.', 'woocommerce' ) );
		}

		$totals = (object) $this->cast_numbers( $totals[0] );

		$data->totals    = $totals;
		$data->intervals = $intervals;

		if ( TimeInterval::intervals_missing( $expected_interval_count, $db_interval_count, $params['per_page'], $query_args['page'], $query_args['order'], $query_args['orderby'], count( $intervals ) ) ) {
			$this->fill_in_missing_intervals( $db_intervals, $query_args['adj_after'], $query_args['adj_before'], $query_args['interval'], $data );
			$this->sort_intervals( $data, $query_args['orderby'], $query_args['order'] );
			$this->remove_extra_records( $data, $query_args['page'], $params['per_page'], $db_interval_count, $expected_interval_count, $query_args['orderby'], $query_args['order'] );
		} else {
			$this->update_interval_boundary_dates( $query_args['after'], $query_args['before'], $query_args['interval'], $data->intervals );
		}
		$this->create_interval_subtotals( $data->intervals );

		return $data;
	}
}
