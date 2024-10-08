import { useBlockProps } from '@wordpress/block-editor';

export default function Save({ attributes }) {
    const { mediaSRC, title, desc, alt } = attributes;
    return (
        <div {...useBlockProps.save()}>
            <div className="offer">
                <img src={mediaSRC} alt={alt} className="offer-image" />
                <h3 className="offer-title">{title}</h3>
                <p className="offer-desc">{desc}</p>
            </div>
        </div>
    );
}
