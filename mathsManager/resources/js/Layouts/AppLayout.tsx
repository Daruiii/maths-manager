import { ReactNode } from 'react';
import Header from '@/Layouts/Header/Header';
import Footer from '@/Layouts/Footer/Footer';
import FlashToast from '@/Components/Common/UI/FlashToast';
import QuickActionHub from '@/Components/Common/UI/QuickActionHub';
import { Head } from '@inertiajs/react';
import { useAuth } from '@/Hooks/Auth/useAuth';

interface AppLayoutProps {
  children: ReactNode;
  title?: string;
  hideFooter?: boolean;
}

export default function AppLayout({ children, title, hideFooter = false }: AppLayoutProps) {
  const { canActAsTeacher } = useAuth();

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
