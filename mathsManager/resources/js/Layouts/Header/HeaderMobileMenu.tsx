import { Disclosure, DisclosureButton, DisclosurePanel, Transition } from '@headlessui/react';
import { X, Menu } from 'lucide-react';
import { Classe } from '@/types';
import { useAuth } from '@/Hooks/Auth/useAuth';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface HeaderMobileMenuProps {
  classes?: Classe[];
}

export default function HeaderMobileMenu({ classes }: HeaderMobileMenuProps) {
  const { isStaff, isStudent, hasNoRole } = useAuth();

  if (hasNoRole) return null;

  return (
    <Disclosure as="nav" className="lg:hidden">
      {({ open }) => (
        <>
          <div className="flex items-center">
            <DisclosureButton className="p-2 text-text-color hover:bg-surface-color rounded-xl transition-colors outline-none focus:outline-none">
              <span className="sr-only">Ouvrir le menu</span>
              {open ? (
                <X className="block h-6 w-6" aria-hidden="true" />
              ) : (
                <Menu className="block h-6 w-6" aria-hidden="true" />
              )}
            </DisclosureButton>
          </div>

          <Transition
            enter="transition duration-200 ease-out"
            enterFrom="opacity-0 -translate-y-4"
            enterTo="opacity-100 translate-y-0"
            leave="transition duration-150 ease-in"
            leaveFrom="opacity-100 translate-y-0"
            leaveTo="opacity-0 -translate-y-4"
          >
            <DisclosurePanel className="absolute top-full left-0 right-0 bg-secondary-color shadow-xl border-t border-border-color overflow-hidden">
              <div className="px-4 pt-4 pb-6 space-y-2 max-h-[80vh] overflow-y-auto">
                {/* Exercices / Classes */}
                <div className="space-y-1">
                  <p className="px-3 text-xxs font-comfortaa-bold text-text-gray uppercase tracking-widest">
                    Exercices
                  </p>
                  {(classes ?? []).length > 0 ? (
                    classes?.map((classe) => (
                      <Link
                        key={classe.id}
                        href={`/classe/${classe.level}`}
                        className="block px-3 py-3 text-base font-comfortaa text-text-color hover:bg-surface-color rounded-xl transition-colors"
                      >
                        {classe.name}
                      </Link>
                    ))
                  ) : (
                    <p className="px-3 py-2 text-sm italic text-text-gray">Aucune classe</p>
                  )}
                </div>

                <div className="h-px bg-border-color my-4" />

                {/* Navigation */}
                <div className="space-y-1">
                  {isStudent && (
                    <Link
                      href="/student/ressources"
                      className="block px-3 py-3 text-base font-comfortaa text-text-color hover:bg-surface-color rounded-xl transition-colors"
                    >
                      Mes Ressources
                    </Link>
                  )}

                  {isStaff && (
                    <Link
                      href={route('teacher.bureau.index')}
                      className="block px-3 py-3 text-base font-comfortaa text-text-color hover:bg-surface-color rounded-xl transition-colors"
                    >
                      Mon Bureau
                    </Link>
                  )}
                </div>
              </div>
            </DisclosurePanel>
          </Transition>
        </>
      )}
    </Disclosure>
  );
}
