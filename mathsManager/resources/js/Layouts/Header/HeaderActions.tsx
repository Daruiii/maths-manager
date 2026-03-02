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
          className="flex px-3 lg:px-4 py-1.5 text-xs shadow-sm bg-secondary-color/50 text-text-color hover:bg-surface-color transition-colors rounded-xl font-comfortaa-bold tracking-widest items-center justify-center border-2 border-border-color"
        >
          Connexion
        </Link>
      )}
    </div>
  );
}
