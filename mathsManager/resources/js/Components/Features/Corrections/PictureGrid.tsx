import { useState } from 'react';
import ImageLightbox from '@/Components/Common/UI/ImageLightbox';

interface Props {
  paths: string[];
  label?: string;
}

function privateUrl(path: string): string {
  const [context, identifier, ...rest] = path.split('/');
  return route('private.file.serve', { context, identifier, filename: rest.join('/') });
}

export default function PictureGrid({ paths, label = 'Photo' }: Props) {
  const [lightboxIndex, setLightboxIndex] = useState<number | null>(null);

  if (paths.length === 0) return null;

  const urls = paths.map(privateUrl);

  return (
    <>
      <div className="grid grid-cols-2 sm:grid-cols-3 gap-2">
        {urls.map((url, index) => (
          <button
            type="button"
            key={`${paths[index]}-${index}`}
            onClick={() => setLightboxIndex(index)}
            className="block aspect-square rounded-xl overflow-hidden border border-border-color hover:opacity-80 transition-opacity"
          >
            <img src={url} alt={`${label} ${index + 1}`} className="w-full h-full object-cover" />
          </button>
        ))}
      </div>

      <ImageLightbox
        images={urls}
        index={lightboxIndex}
        onClose={() => setLightboxIndex(null)}
        onIndexChange={setLightboxIndex}
      />
    </>
  );
}
