/**
 * WordPress dependencies
 */
const { registerBlockType } = wp.blocks;
const { G, Path, SVG, Placeholder, SelectControl } = wp.components;
const { useSelect } = wp.data;
const { RawHTML } = wp.element;
const { __ } = wp.i18n;

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

        const posts = useSelect(
            ( select ) => select( 'core' ).getEntityRecords( 'postType', 'carousel', { per_page: -1, orderby: 'title', order: 'asc', _fields: 'id,title' } ),
            []
        );

        const setID = value => {
            props.setAttributes( { id: Number( value ) } );
        };

        if ( ! posts ) {
            return __( 'Loading...', 'rather-simple-carousel' );
        }

        if ( posts.length === 0 ) {
            return __( 'No carousels found', 'rather-simple-carousel' );
        }

        var options = [];
        options.push( {
            label: __( 'Select a carousel...', 'rather-simple-carousel' ),
            value: ''
        } );

        for ( var i = 0; i < posts.length; i++ ) {
            options.push( {
                label: posts[i].title.raw,
                value: posts[i].id
            } );
        }

        return (
            <Placeholder
                key='rather-simple-carousel-block'
                icon='images-alt2'
                label={ __( 'Rather Simple Carousel', 'rather-simple-carousel' ) }
                className={ className }>
                    <SelectControl
                        label={ __( 'Select a carousel:', 'rather-simple-carousel' ) }
                        value={ attributes.id }
                        options={ options }
                        onChange={ setID }
                    />
            </Placeholder>
        );

    } ),

    save( { attributes } ) {
        const { id } = attributes;
        var shortcode = '[carousel id="' + id + '"]';
        return (
            <RawHTML>{ shortcode }</RawHTML>
        );
    },

};

registerBlockType( name, settings );
