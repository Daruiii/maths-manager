import { Transition } from '@headlessui/react';
import { useState, Fragment } from 'react';
import { ChevronDown } from 'lucide-react';
import { Classe } from '@/types';
import { useAuth } from '@/Hooks/Auth/useAuth';
import { Link, usePage } from '@inertiajs/react';

interface HeaderNavProps {
  classes?: Classe[];
}

export default function HeaderNav({ classes }: HeaderNavProps) {
  const { isStaff, isStudent, isGuest, hasNoRole } = useAuth();
  const { url } = usePage();
  const [dropdownOpen, setDropdownOpen] = useState(false);

  const isActive = (prefix: string) => (url.startsWith(prefix) ? 'active' : '');

  if (hasNoRole) return null;

  return (
    <div className="hidden lg:flex items-center space-x-1">
      {/* Guest only: classes en horizontal */}
      {isGuest && (
        <div className="flex items-center space-x-1">
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

      {/* Student + Staff: dropdown Exercices au hover */}
      {(isStudent || isStaff) && (
        <div
          className="relative flex items-center h-full"
          onMouseEnter={() => setDropdownOpen(true)}
          onMouseLeave={() => setDropdownOpen(false)}
        >
          <button className="nav-link flex items-center gap-1 focus:outline-none">
            Exercices
            <ChevronDown
              className={`h-3.5 w-3.5 transition-transform duration-200 ${dropdownOpen ? 'rotate-180' : ''}`}
            />
          </button>
          <Transition
            as={Fragment}
            show={dropdownOpen}
            enter="transition ease-out duration-150"
            enterFrom="opacity-0 translate-y-1"
            enterTo="opacity-100 translate-y-0"
            leave="transition ease-in duration-100"
            leaveFrom="opacity-100 translate-y-0"
            leaveTo="opacity-0 translate-y-1"
          >
            <div className="absolute left-1/2 -translate-x-1/2 top-full z-[100] pt-2 w-52">
              <div className="rounded-xl bg-secondary-color p-1.5 shadow-xl border border-border-color">
                {(classes ?? []).length > 0 ? (
                  classes?.map((classe) => (
                    <Link
                      key={classe.id}
                      href={`/classe/${classe.level}`}
                      className="block px-3 py-2 text-sm rounded-lg font-comfortaa text-text-gray hover:bg-surface-color hover:text-text-color transition-colors"
                    >
                      {classe.name}
                    </Link>
                  ))
                ) : (
                  <div className="px-3 py-2 text-xs text-text-gray italic font-comfortaa">
                    Aucune classe
                  </div>
                )}
              </div>
            </div>
          </Transition>
        </div>
      )}

      {/* Student: Mes Ressources */}
      {isStudent && (
        <Link
          href={route('student.ressources')}
          className={`nav-link focus:outline-none ${isActive('/student/ressources')}`}
        >
          Mes Ressources
        </Link>
      )}

      {/* Staff: Mon Bureau */}
      {isStaff && (
        <Link
          href={route('teacher.bureau.index')}
          className={`nav-link focus:outline-none ${isActive('/teacher/bureau')}`}
        >
          Mon Bureau
        </Link>
      )}
    </div>
  );
}
