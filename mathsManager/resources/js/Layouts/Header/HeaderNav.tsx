import { Menu, MenuButton, MenuItem, MenuItems, Transition } from '@headlessui/react';
import { Fragment } from 'react';
import { ChevronDown } from 'lucide-react';
import { Classe, User } from '@/types';
import { useAuth } from '@/Hooks/useAuth';

interface HeaderNavProps {
  user: User | null;
  classes?: Classe[];
  dsNotStarted?: number;
  exercisesSheetNotStarted?: number;
}

export default function HeaderNav({ user, classes, dsNotStarted, exercisesSheetNotStarted }: HeaderNavProps) {
  const { isStaff, isStudent, isGuest } = useAuth();

  return (
    <div className="hidden lg:flex items-center space-x-8">
      {/* 1. STAFF & GUEST: Classes in row (horizontal list) */}
      {(isStaff || isGuest) && (
        <div className={`flex items-center space-x-6 ${isStaff ? 'border-r border-gray-200 pr-8 mr-2' : ''}`}>
          {classes?.map((classe) => (
            <a
              key={classe.id}
              href={`/classe/${classe.level}`}
              className="nav-link !text-xs uppercase tracking-widest font-comfortaa-bold opacity-70 hover:opacity-100 transition-opacity whitespace-nowrap"
            >
              {classe.name}
            </a>
          ))}
        </div>
      )}

      {/* 2. STUDENT: Classes Dropdown (kept separate to maintain space-x-8 with other links) */}
      {isStudent && (
        <Menu as="div" className="relative flex items-center h-full">
          <MenuButton className="nav-link flex items-center gap-1 focus:outline-none focus-visible:ring-0 outline-none">
            Classes
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
            <MenuItems className="absolute left-1/2 -translate-x-1/2 top-full z-[100] mt-2 w-56 origin-top rounded-xl bg-white p-2 shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none border border-gray-100">
              {(classes ?? []).length > 0 ? (
                classes?.map((classe) => (
                  <MenuItem key={classe.id}>
                    {({ active }) => (
                      <a
                        href={`/classe/${classe.level}`}
                        className={`${
                          active ? 'bg-primary-color text-admin-color' : 'text-text-gray'
                        } block px-4 py-2.5 text-sm rounded-lg font-comfortaa transition-all duration-200`}
                      >
                        {classe.name}
                      </a>
                    )}
                  </MenuItem>
                ))
              ) : (
                <div className="px-4 py-2 text-xs text-text-gray italic font-comfortaa">Aucune classe</div>
              )}
            </MenuItems>
          </Transition>
        </Menu>
      )}

      {/* 3. STUDENT ONLY: Specific links */}
      {isStudent && (
        <>
          <a href={`/ds/myDS/${user?.id}`} className="nav-link relative focus:outline-none">
            Mes devoirs
            {(dsNotStarted ?? 0) > 0 && (
              <span className="absolute -top-1 -right-3 bg-error-color text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center animate-pulse border border-white">
                {dsNotStarted}
              </span>
            )}
          </a>
          <a href={`/exercises-sheet/my/${user?.id}`} className="nav-link relative focus:outline-none">
            Mes fiches
            {(exercisesSheetNotStarted ?? 0) > 0 && (
              <span className="absolute -top-1 -right-3 bg-error-color text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center animate-pulse border border-white">
                {exercisesSheetNotStarted}
              </span>
            )}
          </a>
        </>
      )}

      {/* 4. STAFF ONLY: Mes élèves */}
      {isStaff && (
        <a href="/students" className="nav-link focus:outline-none">
          Mes élèves
        </a>
      )}
    </div>
  );
}
