/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import ComposedReportChart from '../components/upstream/woocommerce-admin-analytics/analytics/components/report-chart';
import ComposedReportSummary from '../components/upstream/woocommerce-admin-analytics/analytics/components/report-summary';
import { charts } from './config';
import ProductsExampleTable from './table';

const ENDPOINT = 'products-example';

const getSelectedChart = ( query, charts ) =>
	charts.find( ( item ) => item.key === query.chart ) || charts[ 0 ];

/**
 * Products Example report.
 *
 * @param {Object} props       Props provided by WooCommerce routing.
 * @param {Object} props.query
 * @param {string} props.path
 */
export default function ProductsExample( { query, path } ) {
	return (
		<>
			<ComposedReportSummary
				charts={ charts }
				endpoint={ ENDPOINT }
				query={ query }
				selectedChart={ getSelectedChart( query, charts ) }
			/>
			<ComposedReportChart
				endpoint={ ENDPOINT }
				path={ path }
				query={ query }
				selectedChart={ getSelectedChart( query, charts ) }
				charts={ charts }
			/>
			<ProductsExampleTable
				endpoint={ ENDPOINT }
				query={ query }
			/>
		</>
	);
}
