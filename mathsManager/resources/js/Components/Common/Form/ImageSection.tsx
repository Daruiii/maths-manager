import { useRef, useState } from 'react';
import { Check, Copy, Image as ImageIcon, Plus, X } from 'lucide-react';
import { PrivateExercise } from '@/types/models';
import { useImageUploadDropZone } from '@/Hooks/UI/useImageUploadDropZone';
import { buildGraphSnippet } from '@/Utils/latexInsertion';
import { normalizeStoragePath } from '@/Utils/pickableItemContent';

interface Props {
  exercise: PrivateExercise;
  onUpload: (file: File) => Promise<unknown>;
  onDelete: (exerciseId: number, name: string) => Promise<void>;
  uploading: boolean;
  uploadError: string | null;
}

export default function ExerciseImageSection({
  exercise,
  onUpload,
  onDelete,
  uploading,
  uploadError,
}: Props) {
  const fileInputRef = useRef<HTMLInputElement>(null);
  const [copiedName, setCopiedName] = useState<string | null>(null);
  const { isUploadDragOver, handleUploadDragOver, handleUploadDragLeave, handleUploadDrop } =
    useImageUploadDropZone({ onFileDrop: onUpload });
  const imagePaths = exercise.image_paths ?? {};
  const imageEntries = Object.entries(imagePaths);

  function copyLatex(name: string) {
    navigator.clipboard.writeText(buildGraphSnippet(name));
    setCopiedName(name);
    setTimeout(() => setCopiedName(null), 2000);
  }

  return (
    <div className="flex flex-col gap-1 px-3 py-2">
      <div className="flex items-center gap-1.5 text-xxs text-text-gray">
        <ImageIcon size={11} className="text-teacher-color" />
        <span>Images LaTeX — glissez/déposez ou cliquez sur +</span>
      </div>

      {uploadError && <p className="text-[10px] text-error-color">{uploadError}</p>}

      <div
        className={[
          'relative flex items-center gap-2 overflow-x-auto custom-scrollbar rounded-xl transition-colors',
          isUploadDragOver ? 'bg-teacher-color/10 ring-2 ring-teacher-color/40 ring-inset' : '',
        ].join(' ')}
        onDragOver={handleUploadDragOver}
        onDragLeave={handleUploadDragLeave}
        onDrop={handleUploadDrop}
      >
        {imageEntries.map(([name, path]) => (
          <div
            key={name}
            draggable
            onDragStart={(e) => {
              e.dataTransfer.effectAllowed = 'copy';
              e.dataTransfer.setData('application/x-maths-latex-image-name', name);
            }}
            className="relative group w-16 h-16 shrink-0 rounded-xl overflow-hidden border border-border-color"
          >
            <img
              src={normalizeStoragePath(path)}
              alt={name}
              className="w-full h-full object-cover"
            />
            <div className="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors" />
            <div className="absolute top-0.5 right-0.5 flex gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
              <button
                type="button"
                onClick={() => copyLatex(name)}
                className="p-0.5 bg-black/60 text-white rounded hover:bg-teacher-color transition-colors"
                title="Copier le code LaTeX"
              >
                {copiedName === name ? <Check size={9} /> : <Copy size={9} />}
              </button>
              <button
                type="button"
                onClick={() => onDelete(exercise.id, name)}
                className="p-0.5 bg-black/60 text-white rounded hover:bg-error-color transition-colors"
              >
                <X size={9} />
              </button>
            </div>
            <div className="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/70 to-transparent px-1 py-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
              <p className="text-[9px] text-white/80 font-mono truncate">{name}</p>
            </div>
          </div>
        ))}

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
          className="w-16 h-16 shrink-0 flex flex-col items-center justify-center gap-1 rounded-xl border-2 border-dashed border-border-color text-text-gray hover:border-teacher-color/50 hover:text-teacher-color/70 transition-colors disabled:opacity-50"
        >
          <Plus size={16} strokeWidth={1.5} className={uploading ? 'animate-pulse' : ''} />
          <span className="text-[9px] font-comfortaa leading-tight text-center">
            {uploading ? 'Upload…' : 'Ajouter'}
          </span>
        </button>

        {isUploadDragOver && (
          <div className="pointer-events-none absolute inset-1 z-10 flex items-center justify-center rounded-lg border-2 border-dashed border-teacher-color bg-teacher-color/10">
            <p className="px-3 text-center text-xs font-comfortaa-bold text-teacher-color">
              Déposer pour ajouter l'image
            </p>
          </div>
        )}
      </div>
    </div>
  );
}
