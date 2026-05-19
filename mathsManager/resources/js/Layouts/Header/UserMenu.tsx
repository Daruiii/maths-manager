import { Fragment, useEffect, useRef } from 'react';
import { Transition } from '@headlessui/react';
import { LogOut, User as UserIcon, Settings, Crown, GraduationCap, BookOpen } from 'lucide-react';
import { User } from '@/types';
import { router, Link } from '@inertiajs/react';
import { useAuth } from '@/Hooks/Auth/useAuth';

interface UserMenuProps {
  user: User;
  open: boolean;
  onToggle: (open: boolean) => void;
}

export default function UserMenu({ user: propUser, open, onToggle }: UserMenuProps) {
  const { user, isAdmin, isTeacher, isStudent } = useAuth();
  const ref = useRef<HTMLDivElement>(null);
  const currentUser = user || propUser;

  useEffect(() => {
    function onDown(e: MouseEvent) {
      if (ref.current && !ref.current.contains(e.target as Node)) onToggle(false);
    }
    if (open) document.addEventListener('mousedown', onDown);
    return () => document.removeEventListener('mousedown', onDown);
  }, [open, onToggle]);

  const getRoleBorderClass = () => {
    if (isAdmin)
      return 'border-admin-color ring-2 ring-admin-color/40 ring-offset-2 ring-offset-primary-color';
    if (isTeacher)
      return 'border-teacher-color ring-2 ring-teacher-color/30 ring-offset-2 ring-offset-primary-color';
    if (isStudent)
      return 'border-student-color ring-2 ring-student-color/30 ring-offset-2 ring-offset-primary-color';
    return 'border-border-color';
  };

  const getRoleInfo = () => {
    if (isAdmin) return { label: 'Administrateur', color: 'text-admin-color', Icon: Crown };
    if (isTeacher) return { label: 'Professeur', color: 'text-teacher-color', Icon: GraduationCap };
    if (isStudent) return { label: 'Élève', color: 'text-student-color', Icon: BookOpen };
    return null;
  };

  const role = getRoleInfo();

  const avatarUrl = currentUser.avatar?.startsWith('http')
    ? currentUser.avatar
    : `/storage/images/${currentUser.avatar || 'default_avatar.png'}`;

  return (
    <div ref={ref} className="relative ml-1">
      <button
        onClick={() => onToggle(!open)}
        className="flex rounded-full focus:outline-none outline-none relative hover:brightness-90 transition-all"
      >
        <span className="sr-only">Open user menu</span>
        <img
          className={`h-9 w-9 aspect-square rounded-full object-cover shrink-0 border-2 transition-all duration-300 ${getRoleBorderClass()}`}
          src={avatarUrl}
          alt={`${currentUser.first_name} ${currentUser.last_name}`}
        />
      </button>

      <Transition
        as={Fragment}
        show={open}
        enter="transition ease-out duration-150"
        enterFrom="opacity-0 translate-y-1 scale-95"
        enterTo="opacity-100 translate-y-0 scale-100"
        leave="transition ease-in duration-100"
        leaveFrom="opacity-100 translate-y-0 scale-100"
        leaveTo="opacity-0 translate-y-1 scale-95"
      >
        <div className="absolute right-0 z-50 mt-2 w-52 origin-top-right rounded-xl bg-secondary-color border border-border-color shadow-xl overflow-hidden">
          <div className="px-4 py-3 border-b border-border-color">
            <p className="text-sm font-comfortaa-bold text-text-color truncate">
              {currentUser.first_name} {currentUser.last_name}
            </p>
            {role && (
              <p className={`text-xs font-comfortaa flex items-center gap-1 mt-0.5 ${role.color}`}>
                <role.Icon size={10} />
                {role.label}
              </p>
            )}
          </div>

          <div className="py-1">
            <Link
              href={route('profile.show')}
              onClick={() => onToggle(false)}
              className="flex items-center gap-3 w-full px-4 py-2 text-sm text-text-color font-comfortaa transition-colors hover:bg-surface-color"
            >
              <UserIcon size={14} className="shrink-0 text-text-gray" />
              Mon profil
            </Link>
            <Link
              href={route('profile.edit')}
              onClick={() => onToggle(false)}
              className="flex items-center gap-3 w-full px-4 py-2 text-sm text-text-color font-comfortaa transition-colors hover:bg-surface-color"
            >
              <Settings size={14} className="shrink-0 text-text-gray" />
              Paramètres
            </Link>
          </div>

          <div className="py-1 border-t border-border-color">
            <button
              onClick={() => router.post(route('logout'))}
              className="flex items-center gap-3 w-full px-4 py-2 text-sm text-error-color font-comfortaa-bold transition-colors hover:bg-error-color/5"
            >
              <LogOut size={14} className="shrink-0" />
              Se déconnecter
            </button>
          </div>
        </div>
      </Transition>
    </div>
  );
}
