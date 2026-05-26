import { useEffect, useRef, useState } from 'react';
import { Loader2 } from 'lucide-react';
import ImageLightbox from '@/Components/Common/UI/ImageLightbox';
import MobileUploadPanel from '@/Components/Features/Uploads/Partials/MobileUploadPanel';
import UploadErrorBanner from '@/Components/Features/Uploads/Partials/UploadErrorBanner';
import UploadFileHeader from '@/Components/Features/Uploads/Partials/UploadFileHeader';
import UploadGallery from '@/Components/Features/Uploads/Partials/UploadGallery';
import { privateUrl } from '@/Components/Features/Uploads/Partials/uploadWidgetUtils';
import { useUploadSession } from '@/Hooks/Uploads/useUploadSession';
import type { UploadPurpose } from '@/types/api';

interface Props {
  purpose: UploadPurpose;
  accentColor?: 'student' | 'teacher';
  onTokenChange: (token: string | null) => void;
  onFileCountChange?: (count: number) => void;
  existingPictures?: string[];
  onRemoveExisting?: (path: string) => void;
}

export default function UploadSessionWidget({
  purpose,
  accentColor = 'student',
  onTokenChange,
  onFileCountChange,
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
  const [lightboxIndex, setLightboxIndex] = useState<number | null>(null);
  const totalCount = existingPictures.length + files.length;
  const galleryUrls = [
    ...existingPictures.map(privateUrl),
    ...files.map((file) => previews[file.id] ?? ''),
  ];

  useEffect(() => {
    onTokenChange(sessionToken);
  }, [sessionToken, onTokenChange]);

  useEffect(() => {
    onFileCountChange?.(totalCount);
  }, [totalCount, onFileCountChange]);

  function handleFileChange(event: React.ChangeEvent<HTMLInputElement>) {
    const picked = Array.from(event.target.files ?? []);
    picked.forEach((file) => uploadFile(file));
    event.target.value = '';
  }

  if (!isReady) {
    return (
      <div className="flex items-center justify-center py-8 gap-2 text-text-gray text-sm">
        <Loader2 size={16} className="animate-spin" />
        Préparation de la session d&apos;upload…
      </div>
    );
  }

  return (
    <div className="space-y-4">
      <UploadErrorBanner error={error} onClear={clearError} />

      <MobileUploadPanel mobileUrl={mobileUrl} accentColor={accentColor} />

      <div className="border-t border-border-color pt-4 space-y-3">
        <UploadFileHeader
          totalCount={totalCount}
          accentColor={accentColor}
          isUploading={isUploading}
          inputRef={fileInputRef}
          onFileChange={handleFileChange}
        />

        <UploadGallery
          files={files}
          previews={previews}
          existingPictures={existingPictures}
          totalCount={totalCount}
          onPreview={setLightboxIndex}
          onRemoveFile={removeFile}
          onRemoveExisting={onRemoveExisting}
        />
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
