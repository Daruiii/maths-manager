import { Menu, MenuButton, MenuItem, MenuItems, Transition } from '@headlessui/react';
import { Fragment } from 'react';
import { ChevronDown } from 'lucide-react';
import { Classe, User } from '@/types';
import { useAuth } from '@/Hooks/Auth/useAuth';
import { Link, usePage } from '@inertiajs/react';

interface HeaderNavProps {
  user: User | null;
  classes?: Classe[];
  dsNotStarted?: number;
  tdNotStarted?: number;
}

export default function HeaderNav({ user, classes, dsNotStarted, tdNotStarted }: HeaderNavProps) {
  const { isStaff, isStudent, isGuest, hasNoRole } = useAuth();
  const { url } = usePage();

  // Retourne 'active' si l'URL courante commence par le préfixe donné
  const isActive = (prefix: string) => (url.startsWith(prefix) ? 'active' : '');

  // User is authenticated but hasn't chosen a role yet — no nav to show
  if (hasNoRole) return null;

  return (
    <div className="hidden lg:flex items-center space-x-8">
      {/* 1. STAFF & GUEST: Classes in row (horizontal list) */}
      {(isStaff || isGuest) && (
        <div
          className={`flex items-center space-x-6 ${
            isStaff ? 'border-r border-border-color pr-8 mr-2' : ''
          }`}
        >
          {classes?.map((classe) => (
            <Link
              key={classe.id}
              href={`/classe/${classe.level}`}
              className="nav-link text-xs uppercase tracking-widest font-comfortaa-bold opacity-70 hover:opacity-100 transition-opacity whitespace-nowrap"
            >
              {classe.name}
            </Link>
          ))}
        </div>
      )}

      {/* 2. STUDENT: Classes Dropdown (kept separate to maintain space-x-8 with other links) */}
      {isStudent && (
        <Menu as="div" className="relative flex items-center h-full">
          <MenuButton className="nav-link flex items-center gap-1 focus:outline-none focus-visible:ring-0 outline-none">
            Exercices
            <ChevronDown className="h-4 w-4" />
          </MenuButton>
          <Transition
            as={Fragment}
            enter="transition ease-out duration-200"
            enterFrom="transform opacity-0 scale-95"
            enterTo="transform opacity-100 scale-100"
            leave="transition ease-in duration-75"
            leaveFrom="transform opacity-100 scale-100"
            leaveTo="transform opacity-0 scale-95"
          >
            <MenuItems className="absolute left-1/2 -translate-x-1/2 top-full z-[100] mt-2 w-56 origin-top rounded-xl bg-secondary-color p-2 shadow-2xl ring-1 ring-text-color/10 ring-opacity-5 focus:outline-none border border-border-color">
              {(classes ?? []).length > 0 ? (
                classes?.map((classe) => (
                  <MenuItem key={classe.id}>
                    {({ active }) => (
                      <Link
                        href={`/classe/${classe.level}`}
                        className={`${
                          active ? 'bg-primary-color text-tertiary-color' : 'text-text-gray'
                        } block px-4 py-2.5 text-sm rounded-lg font-comfortaa transition-all duration-200`}
                      >
                        {classe.name}
                      </Link>
                    )}
                  </MenuItem>
                ))
              ) : (
                <div className="px-4 py-2 text-xs text-text-gray italic font-comfortaa">
                  Aucune classe
                </div>
              )}
            </MenuItems>
          </Transition>
        </Menu>
      )}

      {/* 3. STUDENT ONLY: Specific links */}
      {isStudent && (
        <>
          <Link
            href={`/ds/myDS/${user?.id}`}
            className={`nav-link relative focus:outline-none ${isActive('/ds/myDS')}`}
          >
            Mes devoirs
            {(dsNotStarted ?? 0) > 0 && (
              <span className="absolute -top-1 -right-3 bg-error-color text-white text-xxs font-bold rounded-full w-4 h-4 flex items-center justify-center animate-pulse border border-white">
                {dsNotStarted}
              </span>
            )}
          </Link>
          <Link
            href={`/exercises-sheet/my/${user?.id}`}
            className={`nav-link relative focus:outline-none ${isActive('/exercises-sheet/my')}`}
          >
            Mes fiches
            {(tdNotStarted ?? 0) > 0 && (
              <span className="absolute -top-1 -right-3 bg-error-color text-white text-xxs font-bold rounded-full w-4 h-4 flex items-center justify-center animate-pulse border border-white">
                {tdNotStarted}
              </span>
            )}
          </Link>
        </>
      )}

      {/* 4. STAFF ONLY: Mes élèves + Mon Bureau */}
      {isStaff && (
        <>
          <Link
            href={route('teacher.students.index')}
            className={`nav-link focus:outline-none ${isActive('/teacher/students')}`}
          >
            Mes élèves
          </Link>
          <Link
            href={route('teacher.bureau.index')}
            className={`nav-link focus:outline-none ${isActive('/teacher/bureau')}`}
          >
            Mon Bureau
          </Link>
        </>
      )}
    </div>
  );
}
