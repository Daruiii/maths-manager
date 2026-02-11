import FooterLogo from './FooterLogo';
import FooterLinks from './FooterLinks';
import FooterCopyright from './FooterCopyright';

export default function Footer() {
  return (
    <footer className="w-full bg-white border-t border-gray-100 py-6 mt-auto font-comfortaa">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col md:flex-row items-center justify-between gap-6 md:gap-4">
          <div className="flex flex-col items-center md:items-start gap-1 text-center md:text-left">
            <FooterLogo />
            <FooterCopyright />
          </div>
          <FooterLinks />
        </div>
      </div>
    </footer>
  );
}
