export default function FooterCopyright() {
  const currentYear = new Date().getFullYear();

  return (
    <div className="text-text-gray text-xs md:text-sm">
      © {currentYear} — Fait avec passion pour les mathématiques.
    </div>
  );
}
