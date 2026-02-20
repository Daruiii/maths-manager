import { useState, useCallback } from 'react';
import Cropper, { Area } from 'react-easy-crop';
import { X } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';
import { getCroppedImg } from '@/Utils/imageUtils';

interface AvatarCropModalProps {
  imageSrc: string;
  onClose: () => void;
  onSave: (_croppedFile: File | null) => void;
}

export default function AvatarCropModal({ imageSrc, onClose, onSave }: AvatarCropModalProps) {
  const [crop, setCrop] = useState({ x: 0, y: 0 });
  const [zoom, setZoom] = useState(1);
  const [croppedAreaPixels, setCroppedAreaPixels] = useState<Area | null>(null);
  const [isImageLoading, setIsImageLoading] = useState(true);
  const [isProcessingCrop, setIsProcessingCrop] = useState(false);

  const onCropComplete = useCallback((_croppedArea: Area, croppedAreaPixels: Area) => {
    setCroppedAreaPixels(croppedAreaPixels);
  }, []);

  const handleSave = async () => {
    if (imageSrc && croppedAreaPixels) {
      setIsProcessingCrop(true);
      try {
        const croppedImage = await getCroppedImg(imageSrc, croppedAreaPixels);
        onSave(croppedImage);
      } catch (error) {
        console.error('Erreur lors du recadrage :', error);
        // Important: Stop loading even on error
        setIsProcessingCrop(false);
      }
      // Note: We don't set isProcessingCrop(false) here because onSave usually closes the modal,
      // but if the parent keeps it open, we should behave correctly.
      // Actually, onSave calls handleCropSave which calls setShowCropper(false).
    }
  };

  return (
    <div className="fixed inset-0 z-[100] bg-black/80 flex items-center justify-center p-4">
      <div className="bg-secondary-color rounded-3xl w-full max-w-md overflow-hidden flex flex-col h-[520px]">
        <div className="p-4 border-b border-border-color flex justify-between items-center">
          <h3 className="font-comfortaa-bold text-lg text-text-color">Recadrer la photo</h3>
          <button
            type="button"
            onClick={onClose}
            className="text-text-gray hover:text-text-color transition-colors"
            disabled={isProcessingCrop}
          >
            <X className="h-6 w-6" />
          </button>
        </div>

        <div className="relative flex-1 bg-gray-900">
          {isImageLoading && (
            <div className="absolute inset-0 z-10 flex flex-col items-center justify-center bg-gray-900/50 backdrop-blur-sm">
              <div className="w-10 h-10 border-4 border-white/20 border-t-white rounded-full animate-spin mb-3"></div>
              <p className="text-white text-sm font-comfortaa">Chargement de l'image...</p>
            </div>
          )}
          <Cropper
            image={imageSrc}
            crop={crop}
            zoom={zoom}
            aspect={1}
            onCropChange={setCrop}
            onCropComplete={onCropComplete}
            onZoomChange={setZoom}
            onMediaLoaded={() => setIsImageLoading(false)}
            cropShape="round"
            showGrid={false}
          />
        </div>

        <div className="p-4 space-y-4 bg-secondary-color">
          <div className="flex items-center gap-4">
            <span className="text-sm text-text-gray font-comfortaa">Zoom</span>
            <input
              type="range"
              value={zoom}
              min={1}
              max={3}
              step={0.1}
              aria-labelledby="Zoom"
              onChange={(e) => setZoom(Number(e.target.value))}
              className="flex-1 accent-tertiary-color"
            />
          </div>
          <div className="flex gap-3 pt-2">
            <button
              type="button"
              onClick={onClose}
              className="flex-1 py-2 text-sm font-comfortaa-bold text-text-gray bg-surface-color rounded-xl hover:bg-border-color transition-colors"
              disabled={isProcessingCrop}
            >
              Annuler
            </button>
            <Button
              type="button"
              onClick={handleSave}
              className="flex-1 justify-center py-2.5"
              disabled={isImageLoading || isProcessingCrop}
            >
              {isProcessingCrop ? (
                <div className="flex items-center gap-2">
                  <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                  Traitement...
                </div>
              ) : (
                'Valider'
              )}
            </Button>
          </div>
        </div>
      </div>
    </div>
  );
}
