export default function FooterCopyright() {
  const currentYear = new Date().getFullYear();

  return (
    <div className="text-text-gray text-xs md:text-sm">
      © {currentYear}. Tous droits réservés — Merci de faire partie de l’équation.
    </div>
  );
}
