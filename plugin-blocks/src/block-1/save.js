import { useBlockProps } from '@wordpress/block-editor';

export default function Save({ attributes }) {
    const { mediaSRC, title, desc, alt } = attributes;
    return (
        <div {...useBlockProps.save()}>
            <div className="virtuality__parallax"></div>
            <div className="virtuality__content">
                <div className="virtuality__content-pros">
                    <div>
                        <h2 className="pros-title">{title}</h2>
                        <p className="pros-desc">{desc}</p>
                    </div>
                    <div className="pros">
                        {mediaSRC && (
                            <img src={mediaSRC} alt={alt} className="pros-image" />
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}
