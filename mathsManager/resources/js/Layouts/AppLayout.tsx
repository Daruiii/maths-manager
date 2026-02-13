import { ReactNode } from 'react';
import Header from '@/Layouts/Header/Header';
import Footer from '@/Layouts/Footer/Footer';
import { Head } from '@inertiajs/react';

interface AppLayoutProps {
  children: ReactNode;
  title?: string;
}

export default function AppLayout({ children, title }: AppLayoutProps) {
  return (
    <div className="min-h-screen flex flex-col bg-primary-color dark:bg-gray-950">
      <Head title={title} />

      <Header />

      <main className="flex-grow pt-[72px]">{children}</main>

      <Footer />
    </div>
  );
}
