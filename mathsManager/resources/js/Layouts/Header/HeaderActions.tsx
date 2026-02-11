import UserMenu from '@/Layouts/Header/UserMenu';
import { User } from '@/types';

interface HeaderActionsProps {
  user: User | null;
}

export default function HeaderActions({ user }: HeaderActionsProps) {
  return (
    <div className="flex items-center gap-4">
      {user ? (
        <UserMenu user={user} />
      ) : (
        <a href="/login" className="hidden lg:flex btn btn-secondary !px-8 !py-2 text-sm shadow-sm bg-white/50">
          Se connecter
        </a>
      )}
    </div>
  );
}
