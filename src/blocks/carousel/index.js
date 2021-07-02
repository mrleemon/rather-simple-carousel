/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import {
    G,
    Path,
    SVG,
    Disabled,
    PanelBody,
    SelectControl,
} from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Internal dependencies
 */

const blockAttributes = {
    id: {
        type: 'integer',
        default: 0,
    },
};

export const name = 'occ/rather-simple-carousel';

export const settings = {
    title: __( 'Rather Simple Carousel', 'rather-simple-carousel' ),
    description: __( 'Display a carousel.', 'rather-simple-carousel' ),
    icon: <SVG viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><Path fill="none" d="M0 0h24v24H0V0z" /><G><Path d="M20 4v12H8V4h12m0-2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 9.67l1.69 2.26 2.48-3.1L19 15H9zM2 6v14c0 1.1.9 2 2 2h14v-2H4V6H2z" /></G></SVG>,
    category: 'common',
    keywords: [ __( 'images', 'rather-simple-carousel' ), __( 'photos', 'rather-simple-carousel' ) ],
    attributes: blockAttributes,

    edit: ( props => {
        const { attributes, className } = props;

        const carousels = useSelect(
            ( select ) => select( 'core' ).getEntityRecords( 'postType', 'carousel', { per_page: -1, orderby: 'title', order: 'asc', _fields: 'id,title' } ),
            []
        );

        const setID = value => {
            props.setAttributes( { id: Number( value ) } );
        };

        if ( ! carousels ) {
            return __( 'Loading...', 'rather-simple-carousel' );
        }

        if ( carousels.length === 0 ) {
            return __( 'No carousels found', 'rather-simple-carousel' );
        }

        var options = [];
        options.push( {
            label: __( 'Select a carousel...', 'rather-simple-carousel' ),
            value: ''
        } );

        for ( var i = 0; i < carousels.length; i++ ) {
            options.push( {
                label: carousels[i].title.raw,
                value: carousels[i].id
            } );
        }

        return (
            <Fragment>
                <InspectorControls>
                    <PanelBody
                        title={ __( 'Settings', 'rather-simple-carousel' ) }
                    >
                        <SelectControl
                            label={ __( 'Select a carousel:', 'rather-simple-carousel' ) }
                            value={ attributes.id }
                            options={ options }
                            onChange={ setID }
                        />
                    </PanelBody>
                </InspectorControls>
                 <ServerSideRender
                    block="occ/rather-simple-carousel"
                    attributes={ attributes }
                    className={ className }
                />
            </Fragment>
        );

    } ),

    save: () => {
        return null;
    },

};

registerBlockType( name, settings );
