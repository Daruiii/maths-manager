import { RefObject } from 'react';
import { Check, Copy, Image as ImageIcon, Plus, X } from 'lucide-react';
import { useImageUploadDropZone } from '@/Hooks/UI/useImageUploadDropZone';

interface Props {
  pendingImageMap: Record<string, string>;
  fileInputRef: RefObject<HTMLInputElement | null>;
  copiedName: string | null;
  onCopy: (name: string) => void;
  onRemove: (name: string) => void;
  onFileChange: (file: File) => void;
}

export default function PendingImagesSection({
  pendingImageMap,
  fileInputRef,
  copiedName,
  onCopy,
  onRemove,
  onFileChange,
}: Props) {
  const { isUploadDragOver, handleUploadDragOver, handleUploadDragLeave, handleUploadDrop } =
    useImageUploadDropZone({ onFileDrop: onFileChange });
  const entries = Object.entries(pendingImageMap);

  return (
    <div className="flex flex-col gap-1 px-3 py-2">
      <div className="flex items-center gap-1.5 text-xxs text-text-gray">
        <ImageIcon size={11} className="text-teacher-color" />
        <span>Images LaTeX — glissez/déposez ou cliquez sur +</span>
      </div>

      <div
        className={[
          'relative flex items-center gap-2 overflow-x-auto custom-scrollbar rounded-xl transition-colors',
          isUploadDragOver ? 'bg-teacher-color/10 ring-2 ring-teacher-color/40 ring-inset' : '',
        ].join(' ')}
        onDragOver={handleUploadDragOver}
        onDragLeave={handleUploadDragLeave}
        onDrop={handleUploadDrop}
      >
        {entries.map(([name, blobUrl]) => (
          <div
            key={name}
            draggable
            onDragStart={(e) => {
              e.dataTransfer.effectAllowed = 'copy';
              e.dataTransfer.setData('application/x-maths-latex-image-name', name);
            }}
            className="relative group w-16 h-16 shrink-0 rounded-xl overflow-hidden border border-border-color"
          >
            <img src={blobUrl} alt={name} className="w-full h-full object-cover" />
            <div className="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors" />
            <div className="absolute top-0.5 right-0.5 flex gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
              <button
                type="button"
                onClick={() => onCopy(name)}
                className="p-0.5 bg-black/60 text-white rounded hover:bg-teacher-color transition-colors"
                title="Copier le code LaTeX"
              >
                {copiedName === name ? <Check size={9} /> : <Copy size={9} />}
              </button>
              <button
                type="button"
                onClick={() => onRemove(name)}
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
            if (file) onFileChange(file);
            e.target.value = '';
          }}
        />

        <button
          type="button"
          onClick={() => fileInputRef.current?.click()}
          className="w-16 h-16 shrink-0 flex flex-col items-center justify-center gap-1 rounded-xl border-2 border-dashed border-border-color text-text-gray hover:border-teacher-color/50 hover:text-teacher-color/70 transition-colors"
        >
          <Plus size={16} strokeWidth={1.5} />
          <span className="text-[9px] font-comfortaa leading-tight text-center">Ajouter</span>
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
