import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function Save({ attributes }) {
    const { headline, desc } = attributes;

    return (
        <div {...useBlockProps.save()}>
            <div className="virtuality__parallax"></div>
            <div className="virtuality__content">
                <div className="virtuality__content-contacthead">
                    <div>
                        <h2 className="contact-headline">{headline}</h2>
                        <p className="contact-desc">{desc}</p>
                    </div>
                </div>
                <InnerBlocks.Content />
            </div>
        </div>
    );
}
