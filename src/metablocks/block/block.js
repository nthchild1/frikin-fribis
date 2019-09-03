/**
 * BLOCK: frikin-fribis
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './editor.scss';
import './style.scss';

const {__} = wp.i18n; // Import __() from wp.i18n
const {registerBlockType} = wp.blocks; // Import registerBlockType() from wp.blocks
import {DropZoneProvider, DropZone} from '@wordpress/components';
import React, {useState} from 'react';

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType('frik-in/fribi-converter', {
    // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
    title: __('Fribi - SVG'), // Block title.
    icon: 'shield', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
    category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    keywords: [
        __('Fribi'),
    ],

    /**
     * The edit function describes the structure of your block in the context of the editor.
     * This represents what the editor will render when the block is used.
     *
     * The "edit" property must be a valid function.
     *
     * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
     *
     * @param {Object} props Props.
     * @returns {Mixed} JSX Component.
     */
    edit: ({
               className,
               setAttributes,
               attributes: {
                   'fribi-object': fribiObject,
               },
           }) => {

        fribiObject = fribiObject ? JSON.parse(fribiObject) : {};
        const [theFribi, setFribiObj] = useState(fribiObject);
        const [hasDropped, setDropped] = useState(false);
        const [wrongFormat, triggerWrongFormatError] = useState(false);
        let statusNotice;

        if (!hasDropped) {
            statusNotice = 'Drop something Here';
        } else if (hasDropped && wrongFormat) {
            statusNotice = 'Not an SVG';
        } else {
            statusNotice = 'Dropped';
        }

        // Creates a <p class='wp-block-cgb-block-frikin-fribis'></p>.
        return (
            <div className={className}>
                <DropZoneProvider>
                    <div>
                        {statusNotice}
                        <DropZone
                            onFilesDrop={files => {
                                let file = files[0];
                                let reader = new FileReader();

                                reader.onload = (event) => {
                                    setDropped(true);

                                    let FribiSVG = event.target.result;
                                    let FribiObj = {};
                                    let partNamesArr = FribiSVG.match(/<!-- .* -->/g);

                                    if (partNamesArr) {
                                        triggerWrongFormatError(false);
                                        partNamesArr = partNamesArr.map(partName => partName.replace(/(<!--) | (-->)/g, ''));
                                        partNamesArr.forEach((part) => {
                                            let regex = RegExp(`<!-- ${part} -->[\\s\\S]*?<!-- .* -->`, 'gm');
                                            FribiSVG.match(regex) ? FribiObj[part] = FribiSVG.match(regex)[0] : FribiObj[part] = null;
                                        });
                                        setFribiObj(FribiObj);

                                    } else {
                                        triggerWrongFormatError(true);
                                    }
                                };

                                reader.readAsText(file);
                                setAttributes({'fribi-object': JSON.stringify(theFribi)});
                            }}
                        />
                    </div>
                </DropZoneProvider>
            </div>
        );
    },

    /**
     * The save function defines the way in which the different attributes should be combined
     * into the final markup, which is then serialized by Gutenberg into post_content.
     *
     * The "save" property must be specified and must be a valid function.
     *
     * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
     *
     * @param {Object} props Props.
     * @returns {Mixed} JSX Frontend HTML.
     */
    save: () => null,
});
