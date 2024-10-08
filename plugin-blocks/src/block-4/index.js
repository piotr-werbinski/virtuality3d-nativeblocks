import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import Save from './save';
import './editor.scss';
import './style.scss';

registerBlockType('plugin-blocks/block-4', {
    edit: Edit,
    save: Save,
});
