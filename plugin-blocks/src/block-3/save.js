import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function Save({ attributes }) {
    const { headline, title, desc } = attributes;
    return (
        <div {...useBlockProps.save()}>
            <div className="virtuality__parallax"></div>
            <div className="virtuality__content">
                <div className="virtuality__content-offer">
                    <h2 className="offer-headline">{headline}</h2>
                    <InnerBlocks.Content />
                </div>
                <div className="virtuality__content-about">
                    <div>
                        <h2 className="about-title">{title}</h2>
                        <p className="about-desc">{desc}</p>
                    </div>
                </div>
            </div>
        </div>
    );
}
