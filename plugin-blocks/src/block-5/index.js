import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import Save from './save';
import './editor.scss';
import './style.scss';

registerBlockType('plugin-blocks/block-5', {
    edit: Edit,
    save: Save,
});
