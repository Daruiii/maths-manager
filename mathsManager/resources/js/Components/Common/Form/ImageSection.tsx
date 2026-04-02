import { useRef, useState } from 'react';
import { Check, Copy, Loader2, Upload, X } from 'lucide-react';
import { PrivateExercise } from '@/types/models';

interface Props {
  exercise: PrivateExercise;
  onUpload: (file: File) => Promise<unknown>;
  onDelete: (exerciseId: number, name: string) => Promise<void>;
  uploading: boolean;
  uploadError: string | null;
}

/**
 * Section images d'un exercice privé (édition uniquement).
 * Grille des images existantes + bouton upload.
 * L'upload insère automatiquement \graph{img-N} dans le champ LaTeX actif.
 */
export default function ExerciseImageSection({
  exercise,
  onUpload,
  onDelete,
  uploading,
  uploadError,
}: Props) {
  const fileInputRef = useRef<HTMLInputElement>(null);
  const [copiedName, setCopiedName] = useState<string | null>(null);
  const imagePaths = exercise.image_paths ?? {};
  const imageEntries = Object.entries(imagePaths);

  function copyLatex(name: string) {
    navigator.clipboard.writeText(`\\graph{${name}}{0.5}{Description}`);
    setCopiedName(name);
    setTimeout(() => setCopiedName(null), 2000);
  }

  return (
    <div className="space-y-3">
      <p className="text-xs font-comfortaa-bold text-text-color">Images</p>

      {imageEntries.length > 0 && (
        <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
          {imageEntries.map(([name, path]) => (
            <div
              key={name}
              className="relative group rounded-xl overflow-hidden border border-border-color"
            >
              <img src={`/storage/${path}`} alt={name} className="w-full h-24 object-cover" />

              <div className="absolute top-1.5 right-1.5 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <button
                  type="button"
                  onClick={() => copyLatex(name)}
                  className="p-1 bg-black/60 text-white rounded-full hover:bg-teacher-color transition-colors"
                  title="Copier le code LaTeX"
                >
                  {copiedName === name ? <Check size={10} /> : <Copy size={10} />}
                </button>
                <button
                  type="button"
                  onClick={() => onDelete(exercise.id, name)}
                  className="p-1 bg-black/60 text-white rounded-full hover:bg-error-color transition-colors"
                >
                  <X size={10} />
                </button>
              </div>

              <div className="absolute bottom-0 inset-x-0 bg-black/50 px-2 py-1">
                <p className="text-xxs text-white/80 truncate">{name}</p>
                <p className="text-xxs text-white/50 font-mono truncate">
                  \graph{'{'}
                  {name}
                  {'}{0.5}{…}'}
                </p>
              </div>
            </div>
          ))}
        </div>
      )}

      {uploadError && <p className="text-xxs text-error-color">{uploadError}</p>}

      <input
        ref={fileInputRef}
        type="file"
        accept="image/*"
        className="hidden"
        onChange={(e) => {
          const file = e.target.files?.[0];
          if (file) onUpload(file);
          e.target.value = '';
        }}
      />

      <button
        type="button"
        onClick={() => fileInputRef.current?.click()}
        disabled={uploading}
        className="flex items-center gap-2 px-3 py-2 text-xs border border-dashed border-border-color rounded-xl text-text-gray hover:border-teacher-color hover:text-teacher-color transition-colors disabled:opacity-50"
      >
        {uploading ? <Loader2 size={14} className="animate-spin" /> : <Upload size={14} />}
        {uploading ? 'Upload en cours…' : 'Ajouter une image'}
      </button>

      <p className="text-xxs text-text-gray/70 italic">
        Hover sur une image → copier{' '}
        <span className="font-mono">\graph{'{'}img-1{'}'}{'{'}0.5{'}'}{'{'}Description{'}'}</span>{' '}
        dans le clipboard.
      </p>
    </div>
  );
}
