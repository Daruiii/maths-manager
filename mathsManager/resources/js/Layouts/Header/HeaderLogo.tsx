import { Link } from '@inertiajs/react';
import Logo from '@/Components/Common/UI/Logo';

export default function HeaderLogo() {
  return (
    <Link href="/" className="flex items-center hover:opacity-80 transition translate-y-[-2px]">
      <Logo showBadge={true} size="md" />
    </Link>
  );
}
