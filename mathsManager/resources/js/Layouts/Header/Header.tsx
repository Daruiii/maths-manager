import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import { useScrollDirection } from '@/Hooks/UI/useScrollDirection';
import { useAuth } from '@/Hooks/Auth/useAuth';
import HeaderLogo from '@/Layouts/Header/HeaderLogo';
import HeaderNav from '@/Layouts/Header/HeaderNav';
import HeaderActions from '@/Layouts/Header/HeaderActions';
import HeaderMobileMenu from '@/Layouts/Header/HeaderMobileMenu';

export default function Header() {
  const { user, isGuest } = useAuth();
  const { classes } = usePage<PageProps>().props;
  const scrollDirection = useScrollDirection();

  return (
    <header
      className={`
        bg-secondary-color border-b border-border-color/50 shadow-warm-xs fixed top-0 left-0 right-0 z-50 min-h-[72px] flex items-center transition-transform duration-300 ease-in-out
        ${scrollDirection === 'down' ? '-translate-y-full' : 'translate-y-0'}
      `}
    >
      <nav className="w-full flex items-center mx-auto px-3 sm:px-4 lg:px-8 gap-2 lg:gap-8">
        {/* Logo — toujours à gauche */}
        <div className="min-w-0 flex items-center">
          <HeaderLogo />
        </div>

        {/* Authentifié : nav collée au logo */}
        {!isGuest && (
          <div className="hidden lg:flex items-center">
            <HeaderNav classes={classes} />
          </div>
        )}

        {/* Guest : classes centrées */}
        {isGuest && (
          <div className="hidden lg:flex flex-1 justify-center items-center">
            <HeaderNav classes={classes} />
          </div>
        )}

        {/* Spacer auth (desktop + mobile) */}
        {!isGuest && <div className="flex-1" />}
        {/* Spacer guest mobile uniquement — desktop géré par flex-1 du nav */}
        {isGuest && <div className="flex-1 lg:hidden" />}

        {/* Actions — toujours à droite */}
        <div className="shrink-0 flex items-center gap-1.5 sm:gap-2">
          <HeaderActions user={user} />
          <HeaderMobileMenu classes={classes} />
        </div>
      </nav>
    </header>
  );
}
