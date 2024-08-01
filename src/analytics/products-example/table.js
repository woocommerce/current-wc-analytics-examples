/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import ComposedReportTable from '../components/upstream/woocommerce-admin-analytics/analytics/components/report-table';

const getHeadersContent = () => {
	return [
		{
			key: 'product_id',
			label: __( 'Product ID', 'woocommerce' ),
			screenReaderLabel: __( 'Product IDx', 'woocommerce' ),
			isNumeric: true,
			isLeftAligned: true,
			required: true,
		},
		{
			key: 'items_sold',
			label: __( 'Items sold', 'woocommerce' ),
			isNumeric: true,
			required: true,
			isLeftAligned: true,
		},
	];
};

const getRowsContent = ( data ) => {
	return data.map( ( datum ) => {
		const {
			product_id: productId,
			items_sold: itemsSold,
		} = datum;

		return [
			{
				display: productId,
				value: productId,
			},
			{
				display: itemsSold,
				value: itemsSold,
			},
		];
	} );
};

export default function ProductsExampleTable ( { endpoint, query } ) {
	return (
			<ComposedReportTable
				title={ __( 'Products Example', 'woocommerce' ) }
				endpoint={ endpoint }
				query={ query }
				filters={ [] }
				getHeadersContent={ getHeadersContent }
				getRowsContent={ getRowsContent }
			/>
	);
}
