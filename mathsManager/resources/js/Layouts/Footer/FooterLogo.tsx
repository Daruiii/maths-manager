import { Link } from '@inertiajs/react';

export default function FooterLogo() {
  return (
    <Link href="/" className="flex items-center gap-2 transition-opacity hover:opacity-80">
      <span className="text-lg font-comfortaa-bold text-text-color dark:text-white tracking-tight">
        Maths Manager
      </span>
    </Link>
  );
}
