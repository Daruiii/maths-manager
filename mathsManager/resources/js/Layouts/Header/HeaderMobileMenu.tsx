import { Disclosure, DisclosureButton, DisclosurePanel, Transition } from '@headlessui/react';
import { X, Menu } from 'lucide-react';
import { Classe, User } from '@/types';
import { useAuth } from '@/Hooks/useAuth';

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
  const { isStaff, isStudent } = useAuth();

  return (
    <Disclosure as="nav" className="lg:hidden">
      {({ open }) => (
        <>
          <div className="flex items-center">
            <DisclosureButton className="p-2 text-text-color dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-colors outline-none focus:outline-none">
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
            <DisclosurePanel className="absolute top-full left-0 right-0 bg-white dark:bg-gray-900 shadow-xl border-t border-gray-100 dark:border-gray-800 overflow-hidden">
              <div className="px-4 pt-4 pb-6 space-y-2 max-h-[80vh] overflow-y-auto">
                {/* Classes Section */}
                <div className="space-y-1">
                  <p className="px-3 text-[10px] font-comfortaa-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                    Classes
                  </p>
                  {(classes ?? []).length > 0 ? (
                    classes?.map((classe) => (
                      <a
                        key={classe.id}
                        href={`/classe/${classe.level}`}
                        className="block px-3 py-3 text-base font-comfortaa text-text-color dark:text-white hover:bg-primary-color dark:hover:bg-gray-800 rounded-xl transition-colors"
                      >
                        {classe.name}
                      </a>
                    ))
                  ) : (
                    <p className="px-3 py-2 text-sm italic text-gray-400 dark:text-gray-500">Aucune classe</p>
                  )}
                </div>

                <div className="h-px bg-gray-100 dark:bg-gray-800 my-4" />

                {/* Navigation Section */}
                <div className="space-y-1">
                  {isStudent && (
                    <>
                      <a
                        href={`/ds/myDS/${user?.id}`}
                        className="flex items-center justify-between px-3 py-3 text-base font-comfortaa text-text-color dark:text-white hover:bg-primary-color dark:hover:bg-gray-800 rounded-xl transition-colors"
                      >
                        <span>Mes devoirs</span>
                        {(dsNotStarted ?? 0) > 0 && (
                          <span className="bg-error-color text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            {dsNotStarted}
                          </span>
                        )}
                      </a>
                      <a
                        href={`/exercises-sheet/my/${user?.id}`}
                        className="flex items-center justify-between px-3 py-3 text-base font-comfortaa text-text-color dark:text-white hover:bg-primary-color dark:hover:bg-gray-800 rounded-xl transition-colors"
                      >
                        <span>Mes fiches</span>
                        {(exercisesSheetNotStarted ?? 0) > 0 && (
                          <span className="bg-error-color text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            {exercisesSheetNotStarted}
                          </span>
                        )}
                      </a>
                    </>
                  )}

                  {isStaff && (
                    <a
                      href="/students"
                      className="block px-3 py-3 text-base font-comfortaa text-text-color dark:text-white hover:bg-primary-color dark:hover:bg-gray-800 rounded-xl transition-colors"
                    >
                      Mes élèves
                    </a>
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
