import { ReactNode } from 'react';
import Header from '@/Layouts/Header/Header';
import Footer from '@/Layouts/Footer/Footer';
import FlashToast from '@/Components/Common/UI/FlashToast';
import { Head } from '@inertiajs/react';

interface AppLayoutProps {
  children: ReactNode;
  title?: string;
}

export default function AppLayout({ children, title }: AppLayoutProps) {
  return (
    <div className="min-h-screen flex flex-col bg-primary-color">
      <Head title={title} />

      <Header />
      <FlashToast />
      <main className="flex-grow pt-[72px]">{children}</main>
      <Footer />
    </div>
  );
}
