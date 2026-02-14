import { ReactNode } from 'react';
import Logo from '@/Components/Common/UI/Logo';
import DarkModeToggle from '@/Components/Common/UI/DarkModeToggle';

interface GuestLayoutProps {
  children: ReactNode;
}

export default function GuestLayout({ children }: GuestLayoutProps) {
  return (
    <div className="min-h-screen flex flex-col bg-background-light dark:bg-gray-950 font-sans antialiased">
      <div className="flex-grow flex flex-col justify-center items-center px-4 py-4 sm:py-8">
        <div className="mb-4 transition-all hover:scale-105 duration-300">
          <a href="/">
            <Logo size="xl" showBadge={true} />
          </a>
        </div>

        <div className="w-full max-w-md animate-in fade-in slide-in-from-bottom-4 duration-700">
          {children}
        </div>

        {/* Dark mode toggle - discret en dessous du formulaire */}
        <div className="mt-6 opacity-60 hover:opacity-100 transition-opacity">
          <DarkModeToggle />
        </div>
      </div>
    </div>
  );
}
