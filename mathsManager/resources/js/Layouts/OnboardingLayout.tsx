import { ReactNode } from 'react';
import Header from '@/Layouts/Header/Header';
import Footer from '@/Layouts/Footer/Footer';

interface OnboardingLayoutProps {
  children: ReactNode;
  /** Optional max-width override, defaults to max-w-xl */
  maxWidth?: string;
}

export default function OnboardingLayout({
  children,
  maxWidth = 'max-w-xl',
}: OnboardingLayoutProps) {
  return (
    <div className="min-h-screen flex flex-col bg-primary-color font-sans antialiased">
      {/* Vrai Header — la nav est masquée automatiquement si user sans rôle (hasNoRole) */}
      <Header />

      {/* Espace pour le header fixe */}
      <div className="h-[72px] shrink-0" />

      {/* Main */}
      <main className="flex-1 flex flex-col items-center justify-center px-4 py-10">
        <div
          className={`w-full ${maxWidth} animate-in fade-in slide-in-from-bottom-4 duration-500`}
        >
          {children}
        </div>
      </main>

      <Footer />
    </div>
  );
}
