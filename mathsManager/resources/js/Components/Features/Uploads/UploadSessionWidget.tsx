import { useEffect, useRef } from 'react';
import { QRCodeSVG } from 'qrcode.react';
import { Camera, Check, Copy, Loader2, Smartphone, Trash2, Upload, X } from 'lucide-react';
import { useUploadSession } from '@/Hooks/Uploads/useUploadSession';
import Button from '@/Components/Common/UI/Button';
import type { UploadPurpose } from '@/types/api';

interface Props {
  purpose: UploadPurpose;
  accentColor?: 'student' | 'teacher';
  onTokenChange: (token: string | null) => void;
}

function formatBytes(bytes: number): string {
  return bytes < 1024 * 1024
    ? `${Math.round(bytes / 1024)} Ko`
    : `${(bytes / (1024 * 1024)).toFixed(1)} Mo`;
}

const ACCENT: Record<'student' | 'teacher', { text: string; hoverBorder: string }> = {
  student: { text: 'text-student-color', hoverBorder: 'hover:border-student-color/40' },
  teacher: { text: 'text-teacher-color', hoverBorder: 'hover:border-teacher-color/40' },
};

export default function UploadSessionWidget({
  purpose,
  accentColor = 'student',
  onTokenChange,
}: Props) {
  const fileInputRef = useRef<HTMLInputElement>(null);
  const {
    sessionToken,
    mobileUrl,
    files,
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
            Fichiers ({files.length})
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

        {files.length === 0 ? (
          <div className="flex items-center justify-center gap-2 py-6 text-sm text-text-gray border-2 border-dashed border-border-color rounded-xl">
            <Camera size={16} />
            Aucune photo encore — utilisez le QR ou le bouton ci-dessus
          </div>
        ) : (
          <ul className="space-y-1.5">
            {files.map((f) => (
              <li
                key={f.id}
                className="flex items-center gap-2 px-3 py-2 rounded-xl bg-surface-color border border-border-color"
              >
                <Check size={14} className="text-success-color shrink-0" />
                <span className="flex-1 text-sm text-text-color truncate">{f.original_name}</span>
                <span className="text-xs text-text-gray shrink-0">{formatBytes(f.size)}</span>
                <button
                  type="button"
                  onClick={() => removeFile(f.id)}
                  className="shrink-0 text-text-gray hover:text-error-color transition-colors"
                >
                  <Trash2 size={13} />
                </button>
              </li>
            ))}
          </ul>
        )}
      </div>
    </div>
  );
}
