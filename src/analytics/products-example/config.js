/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { NAMESPACE } from '@woocommerce/data';

/**
 * Internal dependencies
 */
import { getRequestByIdString } from '../components/upstream/utils/async-requests';

export const charts = [
	{
		key: 'items_sold',
		label: __( 'Items sold', 'woocommerce' ),
		order: 'desc',
		orderby: 'date',
		type: 'number',
	},
	{
		key: 'net_revenue',
		label: __( 'Net revenue', 'woocommerce' ),
		order: 'desc',
		orderby: 'date',
		type: 'number',
	},
];

export const filters = [
	{
		label: __( 'Show Products', 'woocommerce' ),
		staticParams: [ 'paged', 'per_page' ],
		param: 'filter',
		showFilters: () => true,
		filters: [
			{ label: __( 'All products', 'woocommerce' ), value: 'all' },
			{
				label: __( 'Single product', 'woocommerce' ),
				value: 'select_product',
				subFilters: [
					{
						component: 'Search',
						value: 'single_product',
						path: [ 'select_product' ],
						settings: {
							type: 'products',
							param: 'products',
							getLabels: getRequestByIdString(
								NAMESPACE + '/products',
								( product ) => ( {
									key: product.id,
									label: product.name,
								} )
							),
							labels: {
								placeholder: __(
									'Type to search for a product',
									'woocommerce'
								),
								button: __( 'Single product', 'woocommerce' ),
							},
						},
					},
				],
			},
		],
	},
];
