import { useEffect, ReactNode } from 'react';

interface Props {
  isOpen: boolean;
  onClose: () => void;
  children: ReactNode;
  // Si 'column', se transforme en colonne static sur Desktop. Si 'hidden', est invisible sur Desktop.
  desktopMode?: 'hidden' | 'column';
}

export default function MobileBottomSheet({
  isOpen,
  onClose,
  children,
  desktopMode = 'hidden',
}: Props) {
  useEffect(() => {
    // Bloquer le scroll du background uniquement sur mobile quand ouvert
    const handleResize = () => {
      if (isOpen && window.innerWidth < 1024) {
        document.body.style.overflow = 'hidden';
      } else {
        document.body.style.overflow = '';
      }
    };

    handleResize(); // Appel initial
    window.addEventListener('resize', handleResize);

    return () => {
      document.body.style.overflow = '';
      window.removeEventListener('resize', handleResize);
    };
  }, [isOpen]);

  const desktopClasses =
    desktopMode === 'column'
      ? 'lg:static lg:z-auto lg:h-full lg:min-h-0 lg:p-0 lg:bg-transparent lg:rounded-none lg:shadow-none lg:flex lg:w-2/3 lg:translate-y-0 lg:opacity-100 lg:pointer-events-auto'
      : 'lg:hidden';

  return (
    <>
      {/* Backdrop Mobile */}
      <div
        className={`fixed inset-0 z-40 bg-text-color/20 backdrop-blur-sm lg:hidden transition-opacity duration-300 ${isOpen ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'}`}
        onClick={onClose}
      />

      {/* Bottom Sheet wrapper */}
      <div
        className={`
                    fixed inset-x-0 bottom-0 z-50 h-[80vh] bg-primary-color rounded-t-[2rem] shadow-[0_-10px_40px_rgba(0,0,0,0.15)] flex flex-col pt-2 px-4 pb-8 transition-transform duration-300 ease-out
                    ${desktopClasses}
                    ${isOpen ? 'translate-y-0' : 'translate-y-full ' + (desktopMode === 'column' ? 'lg:translate-y-0' : '')}
                `}
      >
        {/* Handle Mobile (Drag indicator) */}
        <div className="lg:hidden flex justify-center pb-4 cursor-pointer pt-2" onClick={onClose}>
          <div className="w-16 h-1.5 bg-border-color rounded-full hover:bg-text-gray transition-colors"></div>
        </div>

        {/* Contenu */}
        <div className="flex-1 min-h-0 h-full flex flex-col overflow-y-auto custom-scrollbar">
          {children}
        </div>
      </div>
    </>
  );
}
