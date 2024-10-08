import { useBlockProps, MediaUpload } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
    const { mediaID, mediaSRC, title, desc, alt } = attributes;
    const blockProps = useBlockProps();

    const onSelectImage = (media) => {
        setAttributes({
            mediaSRC: media.url,
            mediaID: media.id,
            alt: media.alt,
        });
    };

    return (
        <div {...blockProps}>
            <div className="pros">
                {mediaSRC && <img src={mediaSRC} alt={alt} />}
                <MediaUpload
                    onSelect={onSelectImage}
                    allowedTypes={['image']}
                    value={mediaID}
                    render={({ open }) => (
                        <Button
                            className={mediaID ? 'image-button' : 'button button-large'}
                            onClick={open}
                        >
                            {mediaID ? 'Change Image' : 'Add Image'}
                        </Button>
                    )}
                />
            </div>
            <input
                type="text"
                className="pros-title"
                value={title}
                onChange={(e) => setAttributes({ title: e.target.value })}
                placeholder="Enter title"
            />
            <input
                type="text"
                className="pros-desc"
                value={desc}
                onChange={(e) => setAttributes({ desc: e.target.value })}
                placeholder="Enter description"
            />
        </div>
    );
}
