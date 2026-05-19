import { useState, useEffect } from 'react';
import AvatarSelector from '@/Components/Common/Avatar/AvatarSelector';
import AvatarCropModal from '@/Components/Common/Avatar/AvatarCropModal';
import { User } from '@/types';

interface AvatarInputProps {
  user?: User;
  value: File | null;
  isRemoved?: boolean;
  onChange: (_file: File | null) => void;
  onRemove: () => void;
  className?: string;
}

export default function AvatarInput({
  user,
  value,
  isRemoved,
  onChange,
  onRemove,
  className = '',
}: AvatarInputProps) {
  const [imageSrc, setImageSrc] = useState<string | null>(null);
  const [originalImageSrc, setOriginalImageSrc] = useState<string | null>(null); // Store original for re-cropping
  const [showCropper, setShowCropper] = useState(false);

  // If user exists: show their avatar or default.jpg (unless removed)
  // If user is undefined (Register): show nothing (undefined) so the placeholder appears
  const currentAvatarUrl = user
    ? !isRemoved && user.avatar
      ? user.avatar.startsWith('http')
        ? user.avatar
        : `/storage/images/${user.avatar}`
      : isRemoved
        ? undefined
        : `/storage/images/default.jpg`
    : undefined;

  // Handle file selection
  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files.length > 0) {
      const file = e.target.files[0];
      if (imageSrc && imageSrc.startsWith('blob:')) {
        URL.revokeObjectURL(imageSrc);
      }
      if (originalImageSrc && originalImageSrc.startsWith('blob:')) {
        URL.revokeObjectURL(originalImageSrc);
      }

      const url = URL.createObjectURL(file);
      setImageSrc(url);
      setOriginalImageSrc(url); // Keep original
      setShowCropper(true);
      // We don't call onChange yet, we wait for crop
      e.target.value = ''; // Reset input so same file selection triggers change
    }
  };

  const handleCropSave = (croppedFile: File | null) => {
    if (croppedFile) {
      onChange(croppedFile);
      // Valid cropped file: update preview but KEEP originalImageSrc for re-cropping
      const croppedUrl = URL.createObjectURL(croppedFile);

      // Revoke previous preview if it was a blob (but NOT the original source)
      if (imageSrc && imageSrc.startsWith('blob:') && imageSrc !== originalImageSrc) {
        URL.revokeObjectURL(imageSrc);
      }
      setImageSrc(croppedUrl);
    }
    setShowCropper(false);
  };

  const handleCropCurrentAvatar = () => {
    if (currentAvatarUrl) {
      // For current avatar, we can't really "uncrop" unless we stored the original somewhere else.
      // But we can at least crop what we have.
      setImageSrc(currentAvatarUrl);
      setOriginalImageSrc(currentAvatarUrl);
      setShowCropper(true);
    }
  };

  const handleRemove = () => {
    if (imageSrc && imageSrc.startsWith('blob:')) {
      URL.revokeObjectURL(imageSrc);
    }
    setImageSrc(null);
    onRemove();
  };

  // Cleanup originalImageSrc only when it changes or unmounts
  useEffect(() => {
    return () => {
      if (originalImageSrc && originalImageSrc.startsWith('blob:')) {
        URL.revokeObjectURL(originalImageSrc);
      }
    };
  }, [originalImageSrc]);

  // Cleanup imageSrc only when it changes/unmounts AND it is not the same as originalImageSrc
  // (because if it IS the original, the other effect handles it, or we still need it)
  useEffect(() => {
    return () => {
      if (imageSrc && imageSrc.startsWith('blob:') && imageSrc !== originalImageSrc) {
        URL.revokeObjectURL(imageSrc);
      }
    };
  }, [imageSrc, originalImageSrc]);

  return (
    <div className={className}>
      <AvatarSelector
        avatarFile={value}
        currentAvatarUrl={currentAvatarUrl}
        onFileChange={handleFileChange}
        onCropClick={() => originalImageSrc && setShowCropper(true)}
        onCropCurrentAvatar={handleCropCurrentAvatar}
        onRemoveClick={handleRemove}
      />

      {showCropper && (originalImageSrc || imageSrc) && (
        <AvatarCropModal
          imageSrc={originalImageSrc ?? imageSrc!}
          onClose={() => setShowCropper(false)}
          onSave={handleCropSave}
        />
      )}
    </div>
  );
}
