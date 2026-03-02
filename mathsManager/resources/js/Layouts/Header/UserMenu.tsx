import { Menu, MenuButton, MenuItem, MenuItems, Transition } from '@headlessui/react';
import { Fragment } from 'react';
import { LogOut, User as UserIcon, Settings, Crown, GraduationCap, BookOpen } from 'lucide-react';
import { User } from '@/types';
import { router, Link } from '@inertiajs/react';
import { useAuth } from '@/Hooks/useAuth';

interface UserMenuProps {
  user: User;
}

export default function UserMenu({ user: propUser }: UserMenuProps) {
  const { user, isAdmin, isTeacher, isStudent } = useAuth();

  const currentUser = user || propUser;

  const getRoleBorderClass = () => {
    if (isAdmin)
      return 'border-admin-color ring-2 ring-admin-color/40 ring-offset-2 ring-offset-primary-color';
    if (isTeacher)
      return 'border-teacher-color ring-2 ring-teacher-color/30 ring-offset-2 ring-offset-primary-color';
    if (isStudent)
      return 'border-student-color ring-2 ring-student-color/30 ring-offset-2 ring-offset-primary-color';
    return 'border-border-color';
  };

  const renderRoleBadge = () => {
    if (isAdmin) {
      return (
        <div className="absolute -top-1.5 -right-1.5 bg-secondary-color rounded-full p-0.5 shadow-sm border border-admin-color/20">
          <Crown className="h-3 w-3 text-admin-color fill-admin-color drop-shadow-sm" />
        </div>
      );
    }
    if (isTeacher) {
      return (
        <div className="absolute -top-1.5 -right-1.5 bg-secondary-color rounded-full p-0.5 shadow-sm border border-teacher-color/20">
          <GraduationCap className="h-3 w-3 text-teacher-color fill-teacher-color/10" />
        </div>
      );
    }
    if (isStudent) {
      return (
        <div className="absolute -top-1.5 -right-1.5 bg-secondary-color rounded-full p-0.5 shadow-sm border border-student-color/20">
          <BookOpen className="h-3 w-3 text-student-color fill-student-color/10" />
        </div>
      );
    }
    return null;
  };

  const avatarUrl = currentUser.avatar?.startsWith('http')
    ? currentUser.avatar
    : `/storage/images/${currentUser.avatar || 'default_avatar.png'}`;

  const logout = () => {
    router.post(route('logout'));
  };

  return (
    <Menu as="div" className="relative ml-2 sm:ml-3">
      <div>
        <MenuButton className="flex rounded-full text-sm focus:outline-none transition-all hover:brightness-90 outline-none group relative">
          <span className="sr-only">Open user menu</span>
          <img
            className={`h-9 w-9 aspect-square rounded-full object-cover shrink-0 border-2 transition-all duration-300 ${getRoleBorderClass()}`}
            src={avatarUrl}
            alt={`${currentUser.first_name} ${currentUser.last_name}'s avatar`}
          />
          {renderRoleBadge()}
        </MenuButton>
      </div>
      <Transition
        as={Fragment}
        enter="transition ease-out duration-100"
        enterFrom="transform opacity-0 scale-95"
        enterTo="transform opacity-100 scale-100"
        leave="transition ease-in duration-75"
        leaveFrom="transform opacity-100 scale-100"
        leaveTo="transform opacity-0 scale-95"
      >
        <MenuItems className="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-md bg-secondary-color py-1 shadow-lg ring-1 ring-text-color/10 ring-opacity-5 focus:outline-none divide-y divide-border-color">
          <div className="px-4 py-3 flex flex-col items-start w-full">
            <p className="text-sm text-text-color font-comfortaa-bold truncate w-full text-left">
              {currentUser.first_name} {currentUser.last_name}
            </p>
            <p className="text-sm text-text-gray font-comfortaa truncate w-full text-left">
              {currentUser.email}
            </p>
          </div>

          <div className="py-1">
            <MenuItem>
              {({ active }) => (
                <Link
                  href={route('profile.show')}
                  className={`${
                    active ? 'bg-surface-color' : ''
                  } flex items-center w-full px-4 py-2 text-sm text-text-color font-comfortaa transition text-left`}
                >
                  <UserIcon className="mr-3 h-4 w-4 shrink-0" />
                  <span className="flex-1 text-left">Mon profil</span>
                </Link>
              )}
            </MenuItem>
            <MenuItem>
              {({ active }) => (
                <Link
                  href={route('profile.edit')}
                  className={`${
                    active ? 'bg-surface-color' : ''
                  } flex items-center w-full px-4 py-2 text-sm text-text-color font-comfortaa transition text-left`}
                >
                  <Settings className="mr-3 h-4 w-4 shrink-0" />
                  <span className="flex-1 text-left">Paramètres</span>
                </Link>
              )}
            </MenuItem>
          </div>

          <div className="py-1">
            <MenuItem>
              {({ active }) => (
                <button
                  onClick={logout}
                  className={`${
                    active ? 'bg-surface-color' : ''
                  } flex w-full items-center px-4 py-2 text-sm text-error-color font-comfortaa-bold transition text-left`}
                >
                  <LogOut className="mr-3 h-4 w-4 shrink-0" />
                  <span className="flex-1 text-left">Se déconnecter</span>
                </button>
              )}
            </MenuItem>
          </div>
        </MenuItems>
      </Transition>
    </Menu>
  );
}
