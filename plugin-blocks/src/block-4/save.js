import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function Save() {
    return (
        <div className="offer__container">
            <InnerBlocks.Content />
        </div>
    );
}
