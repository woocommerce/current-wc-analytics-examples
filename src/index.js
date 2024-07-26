/**
 * External dependencies
 */
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import { Dropdown } from '@wordpress/components';
import * as Woo from '@woocommerce/components';
import { Fragment } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './index.scss';
import './analytics';

const MyExamplePage = () => (
	<Fragment>
		<Woo.Section component="article">
			<Woo.SectionHeader
				title={ __( 'Search', 'wc-analytics-products-example' ) }
			/>
			<Woo.Search
				type="products"
				placeholder="Search for something"
				selected={ [] }
				onChange={ ( items ) => setInlineSelect( items ) }
				inlineTags
			/>
		</Woo.Section>

		<Woo.Section component="article">
			<Woo.SectionHeader
				title={ __( 'Dropdown', 'wc-analytics-products-example' ) }
			/>
			<Dropdown
				renderToggle={ ( { isOpen, onToggle } ) => (
					<Woo.DropdownButton
						onClick={ onToggle }
						isOpen={ isOpen }
						labels={ [ 'Dropdown' ] }
					/>
				) }
				renderContent={ () => <p>Dropdown content here</p> }
			/>
		</Woo.Section>

		<Woo.Section component="article">
			<Woo.SectionHeader
				title={ __(
					'Pill shaped container',
					'wc-analytics-products-example'
				) }
			/>
			<Woo.Pill className={ 'pill' }>
				{ __(
					'Pill Shape Container',
					'wc-analytics-products-example'
				) }
			</Woo.Pill>
		</Woo.Section>

		<Woo.Section component="article">
			<Woo.SectionHeader
				title={ __( 'Spinner', 'wc-analytics-products-example' ) }
			/>
			<Woo.H>I am a spinner!</Woo.H>
			<Woo.Spinner />
		</Woo.Section>

		<Woo.Section component="article">
			<Woo.SectionHeader
				title={ __( 'Datepicker', 'wc-analytics-products-example' ) }
			/>
			<Woo.DatePicker
				text={ __(
					'I am a datepicker!',
					'wc-analytics-products-example'
				) }
				dateFormat={ 'MM/DD/YYYY' }
			/>
		</Woo.Section>
	</Fragment>
);

addFilter(
	'woocommerce_admin_pages_list',
	'wc-analytics-products-example',
	( pages ) => {
		pages.push( {
			container: MyExamplePage,
			path: '/wc-analytics-products-example',
			breadcrumbs: [
				__(
					'Wc Analytics Products Example',
					'wc-analytics-products-example'
				),
			],
			navArgs: {
				id: 'wc_analytics_products_example',
			},
		} );

		return pages;
	}
);
