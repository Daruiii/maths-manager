import { ReactNode, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { Head } from '@inertiajs/react';
import confetti from 'canvas-confetti';
import Header from '@/Layouts/Header/Header';
import Footer from '@/Layouts/Footer/Footer';
import FlashToast from '@/Components/Common/UI/FlashToast';
import QuickActionHub from '@/Components/Common/UI/QuickActionHub';
import { useAuth } from '@/Hooks/Auth/useAuth';
import type { PageProps } from '@/types';

interface AppLayoutProps {
  children: ReactNode;
  title?: string;
  hideFooter?: boolean;
}

export default function AppLayout({ children, title, hideFooter = false }: AppLayoutProps) {
  const { canActAsTeacher } = useAuth();
  const { flash } = usePage<PageProps>().props;

  useEffect(() => {
    if (flash?.confetti) {
      confetti({
        particleCount: 120,
        spread: 80,
        origin: { y: 0.55 },
        colors: ['#6d28d9', '#f59e0b', '#10b981', '#3b82f6', '#ec4899'],
        disableForReducedMotion: true,
      });
    }
  }, [flash?.confetti]);

  return (
    <div className="min-h-screen flex flex-col bg-primary-color">
      <Head title={title} />

      <Header />
      <FlashToast />
      <main className="flex-grow pt-[72px]">{children}</main>
      {!hideFooter && <Footer />}

      {canActAsTeacher && <QuickActionHub />}
    </div>
  );
}
