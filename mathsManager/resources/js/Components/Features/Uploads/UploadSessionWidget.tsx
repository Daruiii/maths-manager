import { useEffect, useRef, useState } from 'react';
import { QRCodeSVG } from 'qrcode.react';
import { Camera, Copy, Loader2, Smartphone, Upload, X } from 'lucide-react';
import { useUploadSession } from '@/Hooks/Uploads/useUploadSession';
import Button from '@/Components/Common/UI/Button';
import ImageLightbox from '@/Components/Common/UI/ImageLightbox';
import type { UploadPurpose } from '@/types/api';

interface Props {
  purpose: UploadPurpose;
  accentColor?: 'student' | 'teacher';
  onTokenChange: (token: string | null) => void;
  existingPictures?: string[];
  onRemoveExisting?: (path: string) => void;
}

function privateUrl(path: string): string {
  const [context, identifier, ...rest] = path.split('/');
  return route('private.file.serve', { context, identifier, filename: rest.join('/') });
}

const ACCENT: Record<'student' | 'teacher', { text: string; hoverBorder: string }> = {
  student: { text: 'text-student-color', hoverBorder: 'hover:border-student-color/40' },
  teacher: { text: 'text-teacher-color', hoverBorder: 'hover:border-teacher-color/40' },
};

export default function UploadSessionWidget({
  purpose,
  accentColor = 'student',
  onTokenChange,
  existingPictures = [],
  onRemoveExisting,
}: Props) {
  const fileInputRef = useRef<HTMLInputElement>(null);
  const {
    sessionToken,
    mobileUrl,
    files,
    previews,
    isReady,
    isUploading,
    error,
    uploadFile,
    removeFile,
    clearError,
  } = useUploadSession({ purpose });

  const ac = ACCENT[accentColor];

  useEffect(() => {
    onTokenChange(sessionToken);
  }, [sessionToken]);

  function handleFileChange(e: React.ChangeEvent<HTMLInputElement>) {
    const picked = Array.from(e.target.files ?? []);
    picked.forEach((f) => uploadFile(f));
    e.target.value = '';
  }

  function copyLink() {
    if (mobileUrl) navigator.clipboard.writeText(mobileUrl);
  }

  const [lightboxIndex, setLightboxIndex] = useState<number | null>(null);
  const totalCount = existingPictures.length + files.length;
  const galleryUrls = [
    ...existingPictures.map(privateUrl),
    ...files.map((f) => previews[f.id] ?? ''),
  ];

  if (!isReady) {
    return (
      <div className="flex items-center justify-center py-8 gap-2 text-text-gray text-sm">
        <Loader2 size={16} className="animate-spin" />
        Préparation de la session d'upload…
      </div>
    );
  }

  return (
    <div className="space-y-4">
      {error && (
        <div className="flex items-center justify-between gap-2 px-3 py-2 rounded-xl bg-error-color/10 border border-error-color/20 text-sm text-error-color">
          <span>{error}</span>
          <button onClick={clearError}>
            <X size={14} />
          </button>
        </div>
      )}

      <div className="flex gap-4 items-start">
        <div className="shrink-0 bg-white p-2 rounded-xl border border-border-color shadow-sm">
          {mobileUrl && <QRCodeSVG value={mobileUrl} size={96} />}
        </div>

        <div className="flex-1 space-y-2">
          <p className="text-sm text-text-color font-comfortaa-bold flex items-center gap-1.5">
            <Smartphone size={14} className={ac.text} />
            Upload depuis mobile
          </p>
          <p className="text-xs text-text-gray leading-relaxed">
            Scannez le QR code ou copiez le lien sur votre téléphone pour prendre vos photos.
          </p>
          <div className="flex gap-2">
            <button
              type="button"
              onClick={copyLink}
              className={`inline-flex items-center gap-1.5 text-xs px-2.5 py-1.5 rounded-lg border border-border-color bg-surface-color ${ac.hoverBorder} transition-colors text-text-gray hover:text-text-color`}
            >
              <Copy size={12} />
              Copier le lien
            </button>
          </div>
        </div>
      </div>

      <div className="border-t border-border-color pt-4 space-y-3">
        <div className="flex items-center justify-between">
          <p className="text-xs font-comfortaa-bold text-text-gray uppercase tracking-wide">
            Fichiers ({totalCount})
          </p>
          <div>
            <input
              ref={fileInputRef}
              type="file"
              accept="image/*"
              multiple
              className="hidden"
              onChange={handleFileChange}
            />
            <Button
              type="button"
              variant={accentColor}
              size="sm"
              icon={isUploading ? Loader2 : Upload}
              onClick={() => fileInputRef.current?.click()}
              disabled={isUploading}
            >
              Ajouter depuis le bureau
            </Button>
          </div>
        </div>

        {totalCount === 0 ? (
          <div className="flex items-center justify-center gap-2 py-6 text-sm text-text-gray border-2 border-dashed border-border-color rounded-xl">
            <Camera size={16} />
            Aucune photo encore — utilisez le QR ou le bouton ci-dessus
          </div>
        ) : (
          <div
            className="grid gap-2"
            style={{ gridTemplateColumns: 'repeat(auto-fill, minmax(64px, 1fr))' }}
          >
            {existingPictures.map((path, i) => {
              const url = privateUrl(path);
              return (
                <div key={path} className="relative aspect-square group">
                  <button
                    type="button"
                    onClick={() => setLightboxIndex(i)}
                    className="block w-full h-full rounded-xl overflow-hidden border border-border-color hover:opacity-80 transition-opacity"
                  >
                    <img src={url} alt="" className="w-full h-full object-cover" />
                  </button>
                  {onRemoveExisting && (
                    <button
                      type="button"
                      onClick={() => onRemoveExisting(path)}
                      className="absolute top-1 right-1 flex items-center justify-center w-5 h-5 rounded-full bg-black/60 hover:bg-error-color text-white opacity-0 group-hover:opacity-100 transition-opacity"
                      aria-label="Supprimer"
                    >
                      <X size={10} />
                    </button>
                  )}
                </div>
              );
            })}
            {files.map((f, i) => {
              const previewUrl = previews[f.id];
              const galleryIndex = existingPictures.length + i;
              return (
                <div key={f.id} className="relative aspect-square group">
                  {previewUrl ? (
                    <button
                      type="button"
                      onClick={() => setLightboxIndex(galleryIndex)}
                      className="block w-full h-full rounded-xl overflow-hidden border border-border-color hover:opacity-80 transition-opacity"
                    >
                      <img
                        src={previewUrl}
                        alt={f.original_name}
                        className="w-full h-full object-cover"
                      />
                    </button>
                  ) : (
                    <div className="w-full h-full rounded-xl border border-border-color bg-surface-color flex items-center justify-center">
                      <Camera size={16} className="text-text-gray" />
                    </div>
                  )}
                  <button
                    type="button"
                    onClick={() => removeFile(f.id)}
                    className="absolute top-1 right-1 flex items-center justify-center w-5 h-5 rounded-full bg-black/60 hover:bg-error-color text-white opacity-0 group-hover:opacity-100 transition-opacity"
                    aria-label="Supprimer"
                  >
                    <X size={10} />
                  </button>
                </div>
              );
            })}
          </div>
        )}
      </div>

      <ImageLightbox
        images={galleryUrls}
        index={lightboxIndex}
        onClose={() => setLightboxIndex(null)}
        onIndexChange={setLightboxIndex}
      />
    </div>
  );
}
