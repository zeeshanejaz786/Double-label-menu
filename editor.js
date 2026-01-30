/**
 * Double Label Menu — Editor Controls
 */
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { Fragment } from '@wordpress/element';

/**
 * Add attributes to Navigation Link block
 */
addFilter(
    'blocks.registerBlockType',
    'double-label-menu/add-attributes',
    (settings, name) => {
        if ( name !== 'core/navigation-link' ) {
            return settings;
        }

        settings.attributes = {
            ...settings.attributes,
            dlmEnableDoubleLabel: {
                type: 'boolean',
                default: true,
            },
            dlmShowChevron: {
                type: 'boolean',
                default: true,
            },
        };

        return settings;
    }
);

/**
 * Add inspector controls
 */
const withInspectorControls = createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
        if ( props.name !== 'core/navigation-link' ) {
            return <BlockEdit {...props} />;
        }

        const { attributes, setAttributes } = props;

        return (
            <Fragment>
                <BlockEdit {...props} />
                <InspectorControls>
                    <PanelBody title="Double Label Menu">
                        <ToggleControl
                            label="Enable double label"
                            checked={ !! attributes.dlmEnableDoubleLabel }
                            onChange={ ( value ) =>
                                setAttributes( { dlmEnableDoubleLabel: value } )
                            }
                        />
                        <ToggleControl
                            label="Show chevron"
                            checked={ !! attributes.dlmShowChevron }
                            onChange={ ( value ) =>
                                setAttributes( { dlmShowChevron: value } )
                            }
                        />
                    </PanelBody>
                </InspectorControls>
            </Fragment>
        );
    };
}, 'withInspectorControls' );

addFilter(
    'editor.BlockEdit',
    'double-label-menu/with-inspector-controls',
    withInspectorControls
);
