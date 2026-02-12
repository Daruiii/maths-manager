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
          className="text-xs md:text-sm text-text-gray hover:text-admin-color transition-colors"
        >
          {link.label}
        </a>
      ))}
    </div>
  );
}
