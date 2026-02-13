import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import { useScrollDirection } from '@/Hooks/useScrollDirection';
import { useAuth } from '@/Hooks/useAuth';
import HeaderLogo from '@/Layouts/Header/HeaderLogo';
import HeaderNav from '@/Layouts/Header/HeaderNav';
import HeaderActions from '@/Layouts/Header/HeaderActions';
import HeaderMobileMenu from '@/Layouts/Header/HeaderMobileMenu';

export default function Header() {
  const { user } = useAuth();
  const { classes, dsNotStarted, exercisesSheetNotStarted } = usePage<PageProps>().props;
  const scrollDirection = useScrollDirection();

  return (
    <header
      className={`
        bg-secondary-color dark:bg-gray-900 shadow-sm dark:shadow-gray-800 fixed top-0 left-0 right-0 z-50 min-h-[72px] flex items-center transition-transform duration-300 ease-in-out
        ${scrollDirection === 'down' ? '-translate-y-full shadow-none' : 'translate-y-0'}
      `}
    >
      <nav className="w-full flex items-center mx-auto px-4 lg:px-8">
        <div className="flex-1 flex justify-start items-center">
          <HeaderLogo />
        </div>

        <div className="flex-none flex justify-center items-center">
          <HeaderNav
            user={user}
            classes={classes}
            dsNotStarted={dsNotStarted}
            exercisesSheetNotStarted={exercisesSheetNotStarted}
          />
        </div>

        <div className="flex-1 flex justify-end items-center gap-2">
          <HeaderActions user={user} />
          <HeaderMobileMenu
            user={user}
            classes={classes}
            dsNotStarted={dsNotStarted}
            exercisesSheetNotStarted={exercisesSheetNotStarted}
          />
        </div>
      </nav>
    </header>
  );
}
