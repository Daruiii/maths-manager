import { Loader2, Upload } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';

interface Props {
  totalCount: number;
  accentColor: 'student' | 'teacher';
  isUploading: boolean;
  inputRef: React.RefObject<HTMLInputElement | null>;
  onFileChange: (event: React.ChangeEvent<HTMLInputElement>) => void;
}

export default function UploadFileHeader({
  totalCount,
  accentColor,
  isUploading,
  inputRef,
  onFileChange,
}: Props) {
  return (
    <div className="flex items-center justify-between">
      <p className="text-xs font-comfortaa-bold text-text-gray uppercase tracking-wide">
        Fichiers ({totalCount})
      </p>
      <div>
        <input
          ref={inputRef}
          type="file"
          accept="image/*"
          multiple
          className="hidden"
          onChange={onFileChange}
        />
        <Button
          type="button"
          variant={accentColor}
          size="sm"
          icon={isUploading ? Loader2 : Upload}
          onClick={() => inputRef.current?.click()}
          disabled={isUploading}
        >
          Ajouter depuis le bureau
        </Button>
      </div>
    </div>
  );
}
