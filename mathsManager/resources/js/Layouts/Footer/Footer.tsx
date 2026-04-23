import FooterLogo from './FooterLogo';
import FooterLinks from './FooterLinks';
import FooterCopyright from './FooterCopyright';
import DarkModeToggle from '@/Components/Common/UI/DarkModeToggle';

export default function Footer() {
  return (
    <footer className="w-full bg-secondary-color border-t border-border-color py-6 mt-auto font-comfortaa">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col lg:flex-row items-center justify-between gap-6 lg:gap-4">
          <div className="flex flex-col items-center lg:items-start gap-1 text-center lg:text-left">
            <FooterLogo />
            <FooterCopyright />
            <div className="lg:hidden mt-3">
              <DarkModeToggle />
            </div>
          </div>
          <FooterLinks />
        </div>
      </div>
    </footer>
  );
}
