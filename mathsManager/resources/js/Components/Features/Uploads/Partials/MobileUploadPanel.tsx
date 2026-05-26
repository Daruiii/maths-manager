import { QRCodeSVG } from 'qrcode.react';
import { Copy, Smartphone } from 'lucide-react';
import { UPLOAD_ACCENT } from '@/Components/Features/Uploads/Partials/uploadWidgetUtils';

interface Props {
  mobileUrl: string | null;
  accentColor: 'student' | 'teacher';
}

export default function MobileUploadPanel({ mobileUrl, accentColor }: Props) {
  const accent = UPLOAD_ACCENT[accentColor];

  function copyLink() {
    if (mobileUrl) navigator.clipboard.writeText(mobileUrl);
  }

  return (
    <div className="flex gap-4 items-start">
      <div className="shrink-0 bg-white p-2 rounded-xl border border-border-color shadow-sm">
        {mobileUrl && <QRCodeSVG value={mobileUrl} size={96} />}
      </div>

      <div className="flex-1 space-y-2">
        <p className="text-sm text-text-color font-comfortaa-bold flex items-center gap-1.5">
          <Smartphone size={14} className={accent.text} />
          Upload depuis mobile
        </p>
        <p className="text-xs text-text-gray leading-relaxed">
          Scannez le QR code ou copiez le lien sur votre téléphone pour prendre vos photos.
        </p>
        <button
          type="button"
          onClick={copyLink}
          className={`inline-flex items-center gap-1.5 text-xs px-2.5 py-1.5 rounded-lg border border-border-color bg-surface-color ${accent.hoverBorder} transition-colors text-text-gray hover:text-text-color`}
        >
          <Copy size={12} />
          Copier le lien
        </button>
      </div>
    </div>
  );
}
