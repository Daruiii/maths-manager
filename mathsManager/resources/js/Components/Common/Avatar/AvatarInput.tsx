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
      const url = URL.createObjectURL(file);
      setImageSrc(url);
      setShowCropper(true);
      // We don't call onChange yet, we wait for crop
      e.target.value = ''; // Reset input so same file selection triggers change
    }
  };

  const handleCropSave = (croppedFile: File | null) => {
    if (croppedFile) {
      onChange(croppedFile);
    }
    setShowCropper(false);
  };

  const handleCropCurrentAvatar = () => {
    if (currentAvatarUrl) {
      setImageSrc(currentAvatarUrl);
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

  // Cleanup object URLs on unmount
  useEffect(() => {
    return () => {
      if (imageSrc && imageSrc.startsWith('blob:')) {
        URL.revokeObjectURL(imageSrc);
      }
    };
  }, []);

  return (
    <div className={className}>
      <AvatarSelector
        avatarFile={value}
        currentAvatarUrl={currentAvatarUrl}
        onFileChange={handleFileChange}
        onCropClick={() => imageSrc && setShowCropper(true)}
        onCropCurrentAvatar={handleCropCurrentAvatar}
        onRemoveClick={handleRemove}
      />

      {showCropper && imageSrc && (
        <AvatarCropModal
          imageSrc={imageSrc}
          onClose={() => setShowCropper(false)}
          onSave={handleCropSave}
        />
      )}
    </div>
  );
}
