import { Camera, X } from 'lucide-react';
import type { UploadedFileInfo } from '@/types/api';
import { privateUrl } from '@/Components/Features/Uploads/Partials/uploadWidgetUtils';

interface Props {
  files: UploadedFileInfo[];
  previews: Record<number, string>;
  existingPictures: string[];
  totalCount: number;
  onPreview: (index: number) => void;
  onRemoveFile: (id: number) => void;
  onRemoveExisting?: (path: string) => void;
}

export default function UploadGallery({
  files,
  previews,
  existingPictures,
  totalCount,
  onPreview,
  onRemoveFile,
  onRemoveExisting,
}: Props) {
  if (totalCount === 0) {
    return (
      <div className="flex items-center justify-center gap-2 py-6 text-sm text-text-gray border-2 border-dashed border-border-color rounded-xl">
        <Camera size={16} />
        Aucune photo encore — utilisez le QR ou le bouton ci-dessus
      </div>
    );
  }

  return (
    <div
      className="grid gap-2"
      style={{ gridTemplateColumns: 'repeat(auto-fill, minmax(64px, 1fr))' }}
    >
      {existingPictures.map((path, i) => (
        <ExistingPicture
          key={path}
          path={path}
          index={i}
          onPreview={onPreview}
          onRemoveExisting={onRemoveExisting}
        />
      ))}
      {files.map((file, i) => (
        <UploadedPicture
          key={file.id}
          file={file}
          previewUrl={previews[file.id]}
          galleryIndex={existingPictures.length + i}
          onPreview={onPreview}
          onRemoveFile={onRemoveFile}
        />
      ))}
    </div>
  );
}

function ExistingPicture({
  path,
  index,
  onPreview,
  onRemoveExisting,
}: {
  path: string;
  index: number;
  onPreview: (index: number) => void;
  onRemoveExisting?: (path: string) => void;
}) {
  const url = privateUrl(path);

  return (
    <div className="relative aspect-square group">
      <button
        type="button"
        onClick={() => onPreview(index)}
        className="block w-full h-full rounded-xl overflow-hidden border border-border-color hover:opacity-80 transition-opacity"
      >
        <img src={url} alt="" className="w-full h-full object-cover" />
      </button>
      {onRemoveExisting && <RemoveButton onClick={() => onRemoveExisting(path)} />}
    </div>
  );
}

function UploadedPicture({
  file,
  previewUrl,
  galleryIndex,
  onPreview,
  onRemoveFile,
}: {
  file: UploadedFileInfo;
  previewUrl?: string;
  galleryIndex: number;
  onPreview: (index: number) => void;
  onRemoveFile: (id: number) => void;
}) {
  return (
    <div className="relative aspect-square group">
      {previewUrl ? (
        <button
          type="button"
          onClick={() => onPreview(galleryIndex)}
          className="block w-full h-full rounded-xl overflow-hidden border border-border-color hover:opacity-80 transition-opacity"
        >
          <img src={previewUrl} alt={file.original_name} className="w-full h-full object-cover" />
        </button>
      ) : (
        <div className="w-full h-full rounded-xl border border-border-color bg-surface-color flex items-center justify-center">
          <Camera size={16} className="text-text-gray" />
        </div>
      )}
      <RemoveButton onClick={() => onRemoveFile(file.id)} />
    </div>
  );
}

function RemoveButton({ onClick }: { onClick: () => void }) {
  return (
    <button
      type="button"
      onClick={onClick}
      className="absolute top-1 right-1 flex items-center justify-center w-5 h-5 rounded-full bg-black/60 hover:bg-error-color text-white opacity-0 group-hover:opacity-100 transition-opacity"
      aria-label="Supprimer"
    >
      <X size={10} />
    </button>
  );
}
