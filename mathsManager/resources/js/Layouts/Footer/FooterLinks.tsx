export default function FooterLinks() {
  const links = [
    { label: 'CGU', href: '/terms' },
    { label: 'Confidentialité', href: '/privacy' },
    { label: 'Contact', href: '/contact' },
  ];

  return (
    <div className="flex items-center justify-center gap-6">
      {links.map((link) => (
        <a
          key={link.href}
          href={link.href}
          className="text-xs md:text-sm text-text-gray dark:text-gray-400 hover:text-admin-color dark:hover:text-admin-color transition-colors"
        >
          {link.label}
        </a>
      ))}
    </div>
  );
}
