/**
 * BLOCK: frikin-block-event-additional-info
 * Registering a basic block with Gutenberg.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';
import React, {useState, useEffect} from 'react';

const {__} = wp.i18n; // Import __() from wp.i18n
const {registerBlockType} = wp.blocks; // Import registerBlockType() from wp.blocks
import {TextControl, TextareaControl} from '@wordpress/components';

/**
 * Register: a Gutenberg Block.
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
registerBlockType('frik-in/fribi-info', {
    // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
    title: __('Fribis - info'), // Block title.
    icon: 'calendar-alt', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
    category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    attributes: {

        'uid': {
            type: 'text',
            source: 'meta',
            meta: 'uid',
        },
        'fribi_id': {
            type: 'text',
            source: 'meta',
            meta: 'fribi_id',
        },
        'set': {
            type: 'text',
            source: 'meta',
            meta: 'set',
        },
        'name': {
            type: 'text',
            source: 'meta',
            meta: 'name',
        },
        'slug': {
            type: 'text',
            source: 'meta',
            meta: 'slug',
        },
        'parts': {
            type: 'text',
            source: 'meta',
            meta: 'parts',
        },
        'bio': {
            type: 'text',
            source: 'meta',
            meta: 'bio',
        },
        'locked_bio': {
            type: 'text',
            source: 'meta',
            meta: 'locked_bio',
        },
    },
    keywords: [
        __('Eventos'),
        __('Frik-in'),
        __('Información adicional'),
    ],
    /**
     * The edit function describes the structure of your block in the context of the editor.
     * This represents what the editor will render when the block is used.
     *
     * The "edit" property must be a valid function.
     *
     * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
     */
    edit: ({className, setAttributes, attributes: {uid, fribi_id, set, slug, parts, bio, locked_bio}}) => {

        const [UID, setUID] = useState(uid);
        const [ID, setID] = useState(fribi_id);
        const [FRIBISET, setFRIBISET] = useState(set);
        const [SLUG, setSLUG] = useState(slug);
        const [PARTS, setPARTS] = useState(parts);
        const [BIO, setBIO] = useState(bio);
        const [LOCKEDBIO, setLOCKEDBIO] = useState(locked_bio);

        useEffect(() => setAttributes({
            uid: UID, fribi_id: ID, set: FRIBISET,
            slug: SLUG, parts: PARTS, bio: BIO, locked_bio: LOCKEDBIO
        }), [UID, ID, FRIBISET, SLUG, PARTS, BIO, LOCKEDBIO]);

        return <form autocomplete="off" className={className}>
            <TextControl
                label='UID'
                type='number'
                value={UID}
                onChange={value => setUID(value)}
            />
            <TextControl
                label='ID'
                type='number'
                value={ID}
                onChange={value => setID(value)}
            />
            <TextControl
                label='SET'
                value={FRIBISET}
                onChange={value => setFRIBISET(value)}
            />
            <TextControl
                label='SLUG'
                value={SLUG}
                onChange={value => setSLUG(value)}
            />
            <TextareaControl
                label='BIO'
                value={BIO}
                onChange={value => setBIO(value)}
            />
            <TextareaControl
                label='LOCKEDBIO'
                value={LOCKEDBIO}
                onChange={value => setLOCKEDBIO(value)}
            />
            <div>
                PARTS
            </div>
        </form>;
    },

    /**
     * The save function defines the way in which the different attributes should be combined
     * into the final markup, which is then serialized by Gutenberg into post_content.
     *
     * The "save" property must be specified and must be a valid function.
     *
     * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
     */
    save: () => null,
});
