import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function Edit({ attributes, setAttributes }) {
    const { headline, title, desc } = attributes;
    const blockProps = useBlockProps();

    return (
        <div {...blockProps}>
            <input
                type="text"
                className="offer-headline"
                value={headline}
                onChange={(e) => setAttributes({ headline: e.target.value })}
                placeholder="Enter headline"
            />
            <InnerBlocks />
            <input
                type="text"
                className="about-title"
                value={title}
                onChange={(e) => setAttributes({ title: e.target.value })}
                placeholder="Enter title"
            />
            <input
                type="text"
                className="about-desc"
                value={desc}
                onChange={(e) => setAttributes({ desc: e.target.value })}
                placeholder="Enter description"
            />
        </div>
    );
}
