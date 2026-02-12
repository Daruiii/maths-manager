/**
 * Interface for quick access links.
 */
interface QuickLinkProps {
  icon: string | React.ReactNode;
  label: string;
  href: string;
}

/**
 * A simple card-based link for quick navigation.
 */
export default function QuickLink({ icon, label, href }: QuickLinkProps) {
  return (
    <a
      href={href}
      className="bg-white p-4 rounded-2xl shadow-sm border border-gray-50 flex items-center gap-3 hover:border-admin-color/30 transition shadow-lg shadow-black/[0.02]"
    >
      <span className="text-2xl">{icon}</span>
      <span className="text-sm font-comfortaa-bold text-text-color">{label}</span>
    </a>
  );
}
