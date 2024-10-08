import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function Edit() {
    const blockProps = useBlockProps();
    const TEMPLATE = [['plugin-blocks/block-2'], ['plugin-blocks/block-2'], ['plugin-blocks/block-2']];

    return (
        <div {...blockProps}>
            <InnerBlocks template={TEMPLATE} templateLock="all" />
        </div>
    );
}
