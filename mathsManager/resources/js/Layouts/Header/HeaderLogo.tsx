import { Link } from '@inertiajs/react';
import Logo from '@/Components/Common/UI/Logo';

export default function HeaderLogo() {
  return (
    <Link
      href="/"
      className="flex items-center min-w-0 hover:opacity-80 transition translate-y-[-2px]"
    >
      <span className="hidden sm:inline-flex min-w-0">
        <Logo showBadge={true} size="md" />
      </span>
      <span className="sm:hidden text-lg font-comfortaa-bold text-text-color tracking-tight">
        MM
      </span>
    </Link>
  );
}
