import { X } from 'lucide-react';

interface Props {
  error: string | null;
  onClear: () => void;
}

export default function UploadErrorBanner({ error, onClear }: Props) {
  if (!error) return null;

  return (
    <div className="flex items-center justify-between gap-2 px-3 py-2 rounded-xl bg-error-color/10 border border-error-color/20 text-sm text-error-color">
      <span>{error}</span>
      <button type="button" onClick={onClear}>
        <X size={14} />
      </button>
    </div>
  );
}
