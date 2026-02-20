import { Disclosure, DisclosureButton, DisclosurePanel, Transition } from '@headlessui/react';
import { X, Menu } from 'lucide-react';
import { Classe, User } from '@/types';
import { useAuth } from '@/Hooks/useAuth';
import { Link } from '@inertiajs/react';

interface HeaderMobileMenuProps {
  user: User | null;
  classes?: Classe[];
  dsNotStarted?: number;
  exercisesSheetNotStarted?: number;
}

export default function HeaderMobileMenu({
  user,
  classes,
  dsNotStarted,
  exercisesSheetNotStarted,
}: HeaderMobileMenuProps) {
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
                {/* Classes Section */}
                <div className="space-y-1">
                  <p className="px-3 text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest">
                    Classes
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

                {/* Navigation Section */}
                <div className="space-y-1">
                  {isStudent && (
                    <>
                      <Link
                        href={`/ds/myDS/${user?.id}`}
                        className="flex items-center justify-between px-3 py-3 text-base font-comfortaa text-text-color hover:bg-surface-color rounded-xl transition-colors"
                      >
                        <span>Mes devoirs</span>
                        {(dsNotStarted ?? 0) > 0 && (
                          <span className="bg-error-color text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            {dsNotStarted}
                          </span>
                        )}
                      </Link>
                      <Link
                        href={`/exercises-sheet/my/${user?.id}`}
                        className="flex items-center justify-between px-3 py-3 text-base font-comfortaa text-text-color hover:bg-surface-color rounded-xl transition-colors"
                      >
                        <span>Mes fiches</span>
                        {(exercisesSheetNotStarted ?? 0) > 0 && (
                          <span className="bg-error-color text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            {exercisesSheetNotStarted}
                          </span>
                        )}
                      </Link>
                    </>
                  )}

                  {isStaff && (
                    <Link
                      href="/students"
                      className="block px-3 py-3 text-base font-comfortaa text-text-color hover:bg-surface-color rounded-xl transition-colors"
                    >
                      Mes élèves
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
