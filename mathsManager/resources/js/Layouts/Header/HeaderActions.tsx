import UserMenu from '@/Layouts/Header/UserMenu';
import DarkModeToggle from '@/Components/Common/UI/DarkModeToggle';
import { User } from '@/types';

interface HeaderActionsProps {
  user: User | null;
}

export default function HeaderActions({ user }: HeaderActionsProps) {
  return (
    <div className="flex items-center gap-4">
      {/* Dark mode toggle - caché sur mobile (disponible dans le menu mobile) */}
      <div className="hidden lg:block">
        <DarkModeToggle />
      </div>

      {user ? (
        <UserMenu user={user} />
      ) : (
        <a
          href="/login"
          className="flex btn btn-secondary !px-4 lg:!px-8 !py-2 text-xs lg:text-sm shadow-sm bg-white/50 dark:bg-gray-800/50 dark:text-gray-100"
        >
          Connexion
        </a>
      )}
    </div>
  );
}
