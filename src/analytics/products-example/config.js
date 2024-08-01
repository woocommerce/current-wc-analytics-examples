/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';

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
