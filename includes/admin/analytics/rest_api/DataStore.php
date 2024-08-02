<?php

namespace WcAnalyticsProductsExample\Admin\Analytics\Rest_API;

use Automattic\WooCommerce\Admin\API\Reports\DataStore as ReportsDataStore;
use Automattic\WooCommerce\Admin\API\Reports\DataStoreInterface;
use Automattic\WooCommerce\Admin\API\Reports\SqlQuery;

/**
 * Data store for analytics products example.
 */
class DataStore extends ReportsDataStore implements DataStoreInterface {

	/**
	 * Table used to get the data.
	 *
	 * @override ReportsDataStore::$table_name
	 *
	 * @var string
	 */
	protected static $table_name = 'wc_order_product_lookup';

	/**
	 * Cache identifier.
	 *
	 * @override ReportsDataStore::$cache_key
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
	 * Mapping columns to data type to return correct response types.
	 *
	 * @override ReportsDataStore::$column_types
	 *
	 * @var array
	 */
	protected $column_types = array(
		'date_start' => 'strval',
		'date_end'   => 'strval',
		'product_id' => 'intval',
		'items_sold' => 'intval',
	);

	/**
	 * Assign report columns once full table name has been assigned.
	 *
	 * @override ReportsDataStore::assign_report_columns()
	 */
	protected function assign_report_columns() {
		$table_name           = self::get_db_table_name();
		$this->report_columns = array(
			'product_id'   => 'product_id',
			'items_sold'   => 'SUM(product_qty) as items_sold',
		);
	}

	/**
	 * Initialize query objects.
	 */
	protected function initialize_queries() {
		$this->clear_all_clauses();
		$this->subquery = new SqlQuery( $this->context . '_subquery' );
		$this->subquery->add_sql_clause( 'select', 'product_id' );
		$this->subquery->add_sql_clause( 'from', self::get_db_table_name() );
		$this->subquery->add_sql_clause( 'group_by', 'product_id' );
	}

	/**
	 * Updates the database query with parameters used for Products report: categories and order status.
	 *
	 * @param array $query_args Query arguments supplied by the user.
	 */
	protected function add_sql_query_params( $query_args ) {
		$order_product_lookup_table = self::get_db_table_name();

		$this->add_time_period_sql_params( $query_args, $order_product_lookup_table );
		$this->get_limit_sql_params( $query_args );
		$this->add_order_by_sql_params( $query_args );
	}

	/**
	 * Maps ordering specified by the user to columns in the database/fields in the data.
	 *
	 * @override ReportsDataStore::normalize_order_by()
	 *
	 * @param string $order_by Sorting criterion.
	 * @return string
	 */
	protected function normalize_order_by( $order_by ) {
		if ( 'date' === $order_by ) {
			return self::get_db_table_name() . '.date_created';
		}
		return $order_by;
	}

	/**
	 * Get data for the produts.
	 *
	 * Returns the report data based on normalized parameters.
	 * Will be called by `get_data` if there is no data in cache.
	 *
	 * @override ReportsDataStore::get_noncached_data()
	 *
	 * @see get_data
	 * @param array $query_args Query parameters.
	 * @return stdClass|WP_Error Data object `{ totals: *, intervals: array, total: int, pages: int, page_no: int }`, or error.
	 */
	public function get_noncached_data( $query_args ) {
		global $wpdb;

		$table_name = self::get_db_table_name();

		$this->initialize_queries();

		$data = (object) array(
			'data'    => array(),
			'total'   => 0,
			'pages'   => 0,
			'page_no' => 0,
		);

		$selections        = $this->selected_columns( $query_args );
		$params            = $this->get_limit_params( $query_args );
		$this->add_sql_query_params( $query_args );

		$count_query      = "SELECT COUNT(*) FROM (
				{$this->subquery->get_query_statement()}
			) AS tt";
		$db_records_count = (int) $wpdb->get_var(
			$count_query // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		);

		$total_results = $db_records_count;
		$total_pages   = (int) ceil( $db_records_count / $params['per_page'] );

		if ( ( $query_args['page'] < 1 || $query_args['page'] > $total_pages ) ) {
			return $data;
		}

		$this->subquery->clear_sql_clause( 'select' );
		$this->subquery->add_sql_clause( 'select', $selections );
		$this->subquery->add_sql_clause( 'order_by', $this->get_sql_clause( 'order_by' ) );
		$this->subquery->add_sql_clause( 'limit', $this->get_sql_clause( 'limit' ) );
		$products_query = $this->subquery->get_query_statement();

		$product_data = $wpdb->get_results(
			$products_query, // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			ARRAY_A
		);

		if ( null === $product_data ) {
			return $data;
		}

		$product_data = array_map( array( $this, 'cast_numbers' ), $product_data );
		$data         = (object) array(
			'data'    => $product_data,
			'total'   => $total_results,
			'pages'   => $total_pages,
			'page_no' => (int) $query_args['page'],
		);

		return $data;
	}
}
