import { Link } from '@inertiajs/react';

export default function FooterLinks() {
  const links = [
    { label: 'À propos', href: '/a-propos' },
    { label: 'CGU', href: '/conditions-utilisation' },
    { label: 'Confidentialité', href: '/confidentialite' },
    { label: 'Contact', href: '/contact' },
  ];

  return (
    <div className="flex items-center justify-center gap-6">
      {links.map((link) => (
        <Link
          key={link.href}
          href={link.href}
          className="text-xs md:text-sm text-text-gray hover:text-text-color transition-colors"
        >
          {link.label}
        </Link>
      ))}
    </div>
  );
}
