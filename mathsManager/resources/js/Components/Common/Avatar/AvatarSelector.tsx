import React, { useRef } from 'react';
import { Crop, Trash2, Plus, User as UserIcon } from 'lucide-react';
import UserAvatar from '@/Components/Common/UI/UserAvatar';

interface AvatarSelectorProps {
  avatarFile: File | null;
  currentAvatarUrl?: string;
  onFileChange: (_e: React.ChangeEvent<HTMLInputElement>) => void;
  onCropClick: () => void;
  onCropCurrentAvatar?: () => void;
  onRemoveClick: () => void;
}

export default function AvatarSelector({
  avatarFile,
  currentAvatarUrl,
  onFileChange,
  onCropClick,
  onCropCurrentAvatar,
  onRemoveClick,
}: AvatarSelectorProps) {
  const fileInputRef = useRef<HTMLInputElement>(null);

  return (
    <div className="flex flex-col items-center shrink-0">
      <div className={`relative group ${!avatarFile ? 'cursor-pointer' : ''}`}>
        <input
          ref={fileInputRef}
          id="avatar"
          type="file"
          name="avatar"
          className={`absolute inset-0 opacity-0 cursor-pointer z-10 ${avatarFile ? 'hidden' : ''}`}
          onChange={onFileChange}
          accept="image/*"
          title="Cliquez pour changer la photo"
        />

        {/* Avatar circle */}
        <div
          className={`h-20 w-20 rounded-full border-2 border-dashed border-border-color flex items-center justify-center overflow-hidden bg-surface-color transition-all ${avatarFile || currentAvatarUrl ? 'border-none shadow-md' : 'group-hover:border-tertiary-color/50'}`}
        >
          {avatarFile ? (
            <img
              src={URL.createObjectURL(avatarFile)}
              alt="Avatar preview"
              className="h-full w-full object-cover"
            />
          ) : currentAvatarUrl ? (
            <UserAvatar
              src={currentAvatarUrl}
              alt="Avatar actuel"
              className="h-full w-full object-cover !rounded-none !border-0"
              size="2xl"
            />
          ) : (
            <div className="text-text-gray text-[10px] text-center px-1 font-comfortaa flex flex-col items-center">
              <UserIcon className="h-5 w-5 mb-0.5" strokeWidth={2} />
              Photo
            </div>
          )}
        </div>

        {/* Plus button — no avatar yet */}
        {!avatarFile && !currentAvatarUrl && (
          <div className="absolute -bottom-0.5 -right-0.5 bg-secondary-color rounded-full p-1.5 shadow-md border border-border-color text-tertiary-color group-hover:scale-110 transition-transform">
            <Plus className="h-3.5 w-3.5" strokeWidth={3} />
          </div>
        )}

        {/* New file selected — crop + remove */}
        {avatarFile && (
          <>
            <button
              type="button"
              onClick={(e) => {
                e.stopPropagation();
                onCropClick();
              }}
              className="absolute top-0 right-0 bg-secondary-color/90 backdrop-blur-sm p-1.5 rounded-full shadow-lg border border-border-color text-tertiary-color hover:bg-tertiary-color hover:text-white transition-all transform hover:scale-110 translate-x-1 -translate-y-1 z-20"
              title="Recadrer la photo"
            >
              <Crop className="h-3.5 w-3.5" />
            </button>

            <button
              type="button"
              onClick={(e) => {
                e.stopPropagation();
                onRemoveClick();
              }}
              className="absolute bottom-0 left-0 bg-secondary-color/90 backdrop-blur-sm p-1.5 rounded-full shadow-lg border border-border-color text-error-color hover:bg-error-color hover:text-white transition-all transform hover:scale-110 -translate-x-1 translate-y-1 z-20"
              title="Retirer la photo"
            >
              <Trash2 className="h-3.5 w-3.5" />
            </button>
          </>
        )}

        {/* Existing avatar — crop + remove */}
        {!avatarFile && currentAvatarUrl && (
          <>
            {onCropCurrentAvatar && (
              <button
                type="button"
                onClick={(e) => {
                  e.stopPropagation();
                  onCropCurrentAvatar();
                }}
                className="absolute top-0 right-0 bg-secondary-color/90 backdrop-blur-sm p-1.5 rounded-full shadow-lg border border-border-color text-tertiary-color hover:bg-tertiary-color hover:text-white transition-all transform hover:scale-110 translate-x-1 -translate-y-1 z-20"
                title="Recadrer la photo"
              >
                <Crop className="h-3.5 w-3.5" />
              </button>
            )}

            <button
              type="button"
              onClick={(e) => {
                e.stopPropagation();
                onRemoveClick();
              }}
              className="absolute bottom-0 left-0 bg-secondary-color/90 backdrop-blur-sm p-1.5 rounded-full shadow-lg border border-border-color text-error-color hover:bg-error-color hover:text-white transition-all transform hover:scale-110 -translate-x-1 translate-y-1 z-20"
              title="Supprimer la photo actuelle"
            >
              <Trash2 className="h-3.5 w-3.5" />
            </button>
          </>
        )}
      </div>
    </div>
  );
}
