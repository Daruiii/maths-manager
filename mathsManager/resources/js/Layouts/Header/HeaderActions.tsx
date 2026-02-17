import UserMenu from '@/Layouts/Header/UserMenu';
import DarkModeToggle from '@/Components/Common/UI/DarkModeToggle';
import { User } from '@/types';
import { Link } from '@inertiajs/react';

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
        <Link
          href="/login"
          className="flex !px-3 lg:!px-4 !py-1.5 text-xs lg:text-xs shadow-sm bg-white/50 dark:bg-gray-800/50 dark:text-gray-100 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors rounded-xl font-comfortaa-bold tracking-widest items-center justify-center border-2 border-gray-200"
        >
          Connexion
        </Link>
      )}
    </div>
  );
}
