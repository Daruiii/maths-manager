import { Fragment, useEffect } from 'react';
import { Dialog, Transition } from '@headlessui/react';
import { ChevronLeft, ChevronRight, X } from 'lucide-react';

interface Props {
  images: string[];
  index: number | null;
  onClose: () => void;
  onIndexChange: (index: number) => void;
}

export default function ImageLightbox({ images, index, onClose, onIndexChange }: Props) {
  const isOpen = index !== null;
  const canNavigate = images.length > 1;

  useEffect(() => {
    if (index === null) return;
    function onKey(e: KeyboardEvent) {
      if (e.key === 'ArrowLeft') onIndexChange((index! - 1 + images.length) % images.length);
      if (e.key === 'ArrowRight') onIndexChange((index! + 1) % images.length);
    }
    window.addEventListener('keydown', onKey);
    return () => window.removeEventListener('keydown', onKey);
  }, [index, images.length, onIndexChange]);

  return (
    <Transition show={isOpen} as={Fragment}>
      <Dialog as="div" className="relative z-[60]" onClose={onClose}>
        <Transition.Child
          as={Fragment}
          enter="ease-out duration-200"
          enterFrom="opacity-0"
          enterTo="opacity-100"
          leave="ease-in duration-150"
          leaveFrom="opacity-100"
          leaveTo="opacity-0"
        >
          <div className="fixed inset-0 bg-black/85 backdrop-blur-sm" />
        </Transition.Child>

        <Transition.Child
          as={Fragment}
          enter="ease-out duration-200"
          enterFrom="opacity-0"
          enterTo="opacity-100"
          leave="ease-in duration-150"
          leaveFrom="opacity-100"
          leaveTo="opacity-0"
        >
          <Dialog.Panel
            className="fixed inset-0 flex items-center justify-center p-4 sm:p-10"
            onClick={(e) => {
              if (e.target === e.currentTarget) onClose();
            }}
          >
            <button
              type="button"
              onClick={onClose}
              className="absolute top-4 right-4 flex items-center justify-center w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors"
              aria-label="Fermer"
            >
              <X size={20} />
            </button>

            {canNavigate && index !== null && (
              <button
                type="button"
                onClick={() => onIndexChange((index - 1 + images.length) % images.length)}
                className="absolute left-4 sm:left-8 flex items-center justify-center w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors"
                aria-label="Précédente"
              >
                <ChevronLeft size={24} />
              </button>
            )}

            {index !== null && (
              <img
                src={images[index]}
                alt=""
                className="max-h-[85vh] max-w-full rounded-xl object-contain shadow-2xl"
              />
            )}

            {canNavigate && index !== null && (
              <button
                type="button"
                onClick={() => onIndexChange((index + 1) % images.length)}
                className="absolute right-4 sm:right-8 flex items-center justify-center w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors"
                aria-label="Suivante"
              >
                <ChevronRight size={24} />
              </button>
            )}

            {canNavigate && index !== null && (
              <div className="absolute bottom-5 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-white/10 text-white text-xs font-comfortaa-bold">
                {index + 1} / {images.length}
              </div>
            )}
          </Dialog.Panel>
        </Transition.Child>
      </Dialog>
    </Transition>
  );
}
