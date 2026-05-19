import { useState } from 'react';

interface Params {
  onFileDrop: (file: File) => void | Promise<unknown>;
}

export function useImageUploadDropZone({ onFileDrop }: Params) {
  const [isUploadDragOver, setIsUploadDragOver] = useState(false);

  function handleUploadDragOver(e: React.DragEvent) {
    if (!e.dataTransfer.types.includes('Files')) return;
    e.preventDefault();
    setIsUploadDragOver(true);
  }

  function handleUploadDragLeave(e: React.DragEvent) {
    if (!e.currentTarget.contains(e.relatedTarget as Node)) {
      setIsUploadDragOver(false);
    }
  }

  function handleUploadDrop(e: React.DragEvent) {
    if (!e.dataTransfer.types.includes('Files')) return;
    e.preventDefault();
    setIsUploadDragOver(false);

    const file = e.dataTransfer.files?.[0];
    if (file && file.type.startsWith('image/')) {
      void onFileDrop(file);
    }
  }

  return {
    isUploadDragOver,
    handleUploadDragOver,
    handleUploadDragLeave,
    handleUploadDrop,
  };
}
