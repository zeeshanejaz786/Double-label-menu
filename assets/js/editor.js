import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, RangeControl } from '@wordpress/components';
import { Fragment } from '@wordpress/element';

/**
 * Add attributes to navigation-link
 */
addFilter(
  'blocks.registerBlockType',
  'double-label-menu/attrs',
  ( settings, name ) => {
    if ( name !== 'core/navigation-link' ) return settings;

    settings.attributes = {
      ...settings.attributes,
      dlmEnabled: {
        type: 'boolean',
        default: true,
      },
      dlmPrimarySize: {
        type: 'number',
        default: 16,
      },
      dlmSecondarySize: {
        type: 'number',
        default: 13,
      },
    };

    return settings;
  }
);

/**
 * Inspector controls
 */
addFilter(
  'editor.BlockEdit',
  'double-label-menu/controls',
  createHigherOrderComponent( ( BlockEdit ) => {
    return ( props ) => {
      if ( props.name !== 'core/navigation-link' ) {
        return <BlockEdit { ...props } />;
      }

      const { attributes, setAttributes } = props;

      return (
        <Fragment>
          <BlockEdit { ...props } />
          <InspectorControls>
            <PanelBody title="Double Label Menu" initialOpen={ true }>
              <ToggleControl
                label="Enable double label"
                checked={ attributes.dlmEnabled }
                onChange={ ( v ) => setAttributes({ dlmEnabled: v }) }
              />
              <RangeControl
                label="Primary font size"
                min={ 12 }
                max={ 24 }
                value={ attributes.dlmPrimarySize }
                onChange={ ( v ) => setAttributes({ dlmPrimarySize: v }) }
              />
              <RangeControl
                label="Secondary font size"
                min={ 10 }
                max={ 20 }
                value={ attributes.dlmSecondarySize }
                onChange={ ( v ) => setAttributes({ dlmSecondarySize: v }) }
              />
            </PanelBody>
          </InspectorControls>
        </Fragment>
      );
    };
  })
);
