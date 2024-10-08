import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function Edit({ attributes, setAttributes }) {
    const { headline, desc } = attributes;
    const blockProps = useBlockProps();

    return (
        <div {...blockProps}>
            <input
                type="text"
                className="contact-headline"
                value={headline}
                onChange={(e) => setAttributes({ headline: e.target.value })}
                placeholder="Enter headline"
            />
            <input
                type="text"
                className="contact-desc"
                value={desc}
                onChange={(e) => setAttributes({ desc: e.target.value })}
                placeholder="Enter description"
            />
            <InnerBlocks />
        </div>
    );
}
