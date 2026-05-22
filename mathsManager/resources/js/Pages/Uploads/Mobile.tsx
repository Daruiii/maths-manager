import { Head } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';
import axios from 'axios';
import { Camera, CheckCircle, Loader2, X } from 'lucide-react';
import type { UploadedFileInfo, UploadPurpose } from '@/types/api';

interface Props {
  token: string;
  purpose: UploadPurpose;
  expiresAt: string;
}

interface UploadedEntry {
  info: UploadedFileInfo;
  previewUrl: string;
}

const PURPOSE_LABEL: Record<UploadPurpose, string> = {
  correction_submission: 'Envoi de copie',
  teacher_correction: 'Envoi de correction',
};

export default function MobileUpload({ token, purpose, expiresAt }: Props) {
  const [uploaded, setUploaded] = useState<UploadedEntry[]>([]);
  const [uploading, setUploading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const inputRef = useRef<HTMLInputElement>(null);

  const isExpired = new Date(expiresAt) < new Date();

  useEffect(() => {
    return () => {
      uploaded.forEach((e) => URL.revokeObjectURL(e.previewUrl));
    };
  }, []);

  async function handleFiles(e: React.ChangeEvent<HTMLInputElement>) {
    const files = Array.from(e.target.files ?? []);
    e.target.value = '';
    if (!files.length) return;

    setUploading(true);
    setError(null);

    for (const file of files) {
      const previewUrl = URL.createObjectURL(file);
      const formData = new FormData();
      formData.append('file', file);
      try {
        const res = await axios.post<UploadedFileInfo>(
          route('uploads.files.add', token),
          formData,
          { headers: { 'Content-Type': 'multipart/form-data' } }
        );
        setUploaded((prev) => [...prev, { info: res.data, previewUrl }]);
      } catch {
        URL.revokeObjectURL(previewUrl);
        setError("Un fichier n'a pas pu être envoyé. Vérifiez sa taille (max 5 Mo).");
      }
    }

    setUploading(false);
  }

  return (
    <>
      <Head title={PURPOSE_LABEL[purpose]} />

      <div className="min-h-screen bg-primary-color flex flex-col items-center justify-center px-6 py-12 gap-8">
        <div className="text-center space-y-1">
          <p className="text-xs font-comfortaa text-text-gray uppercase tracking-widest">
            Maths Manager
          </p>
          <h1 className="text-xl font-comfortaa-bold text-text-color">{PURPOSE_LABEL[purpose]}</h1>
        </div>

        {isExpired ? (
          <div className="w-full max-w-sm bg-secondary-color rounded-2xl border border-border-color p-6 text-center space-y-2">
            <X size={32} className="mx-auto text-error-color" />
            <p className="font-comfortaa-bold text-text-color">Session expirée</p>
            <p className="text-sm text-text-gray">
              Ce lien n'est plus valide. Redemandez un nouveau lien depuis votre ordinateur.
            </p>
          </div>
        ) : (
          <div className="w-full max-w-sm space-y-4">
            {uploaded.length > 0 && (
              <div className="bg-secondary-color rounded-2xl border border-border-color p-4 space-y-3">
                <p className="text-xs font-comfortaa-bold text-text-gray uppercase tracking-wide">
                  {uploaded.length} photo{uploaded.length > 1 ? 's' : ''} envoyée
                  {uploaded.length > 1 ? 's' : ''}
                </p>
                <div className="grid grid-cols-3 gap-2">
                  {uploaded.map((entry) => (
                    <div
                      key={entry.info.id}
                      className="relative aspect-[3/4] rounded-lg overflow-hidden border border-border-color"
                    >
                      <img
                        src={entry.previewUrl}
                        alt={entry.info.original_name}
                        className="w-full h-full object-cover"
                      />
                      <div className="absolute inset-0 bg-success-color/10" />
                      <CheckCircle
                        size={16}
                        className="absolute bottom-1.5 right-1.5 text-success-color drop-shadow"
                      />
                    </div>
                  ))}
                </div>
              </div>
            )}

            {error && (
              <div className="bg-error-color/10 border border-error-color/20 rounded-xl px-4 py-3 text-sm text-error-color">
                {error}
              </div>
            )}

            <input
              ref={inputRef}
              type="file"
              accept="image/*"
              capture="environment"
              multiple
              className="hidden"
              onChange={handleFiles}
            />

            <button
              type="button"
              onClick={() => inputRef.current?.click()}
              disabled={uploading}
              className="w-full flex items-center justify-center gap-3 py-5 rounded-2xl bg-student-color text-white font-comfortaa-bold text-lg shadow-lg active:scale-95 transition-transform disabled:opacity-60"
            >
              {uploading ? <Loader2 size={22} className="animate-spin" /> : <Camera size={22} />}
              {uploading ? 'Envoi en cours…' : 'Ajouter des photos'}
            </button>

            <p className="text-center text-xs text-text-gray leading-relaxed">
              Ajoutez une ou plusieurs pages. Vous pouvez revenir ici pour en ajouter d'autres.
            </p>

            {uploaded.length > 0 && !uploading && (
              <div className="flex items-center justify-center gap-2 py-3 text-sm text-success-color">
                <CheckCircle size={16} />
                Photos reçues — vous pouvez en ajouter d'autres ou fermer cette fenêtre.
              </div>
            )}
          </div>
        )}
      </div>
    </>
  );
}
