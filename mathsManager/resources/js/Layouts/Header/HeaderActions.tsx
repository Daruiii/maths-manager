import { useState } from 'react';
import UserMenu from '@/Layouts/Header/UserMenu';
import DarkModeToggle from '@/Components/Common/UI/DarkModeToggle';
import NotificationBell from '@/Layouts/Header/NotificationBell';
import { User } from '@/types';
import { Link } from '@inertiajs/react';

interface HeaderActionsProps {
  user: User | null;
}

export default function HeaderActions({ user }: HeaderActionsProps) {
  const [panel, setPanel] = useState<'bell' | 'user' | null>(null);

  return (
    <div className="flex items-center gap-2">
      <div className="hidden lg:block">
        <DarkModeToggle />
      </div>

      {user ? (
        <>
          <NotificationBell
            open={panel === 'bell'}
            onToggle={(open) => setPanel(open ? 'bell' : null)}
          />
          <UserMenu
            user={user}
            open={panel === 'user'}
            onToggle={(open) => setPanel(open ? 'user' : null)}
          />
        </>
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
