/**
 * External dependencies
 */

import { addFilter } from '@wordpress/hooks';
import { _x } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import ProductsExample from './products-example';

/**
 * Use the 'woocommerce_admin_reports_list' filter to add a WC Analytics report page.
 */
addFilter( 'woocommerce_admin_reports_list', 'wc-analytics-products-example', ( reports ) => {
	return [
		...reports,
		{
			report: 'products-example',
			title: _x( 'Products Example', 'analytics report title', 'wc-analytics-products-example' ),
			component: ProductsExample,
			navArgs: {
				id: 'woocommerce-analytics-products-example',
			},
		},
	];
} );


