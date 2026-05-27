import { Bell, BookOpen, CheckCircle2, FileUp, Unlock } from 'lucide-react';
import { Fragment, useEffect, useRef, useState } from 'react';
import { Transition } from '@headlessui/react';
import { router, usePage } from '@inertiajs/react';
import type { AppNotification, PageProps } from '@/types';

type NotificationFilter = 'all' | 'unread';

function timeAgo(dateStr: string): string {
  const diff = Date.now() - new Date(dateStr).getTime();
  const mins = Math.floor(diff / 60000);
  if (mins < 1) return 'maintenant';
  if (mins < 60) return `il y a ${mins} min`;
  const hrs = Math.floor(mins / 60);
  if (hrs < 24) return `il y a ${hrs}h`;
  return `il y a ${Math.floor(hrs / 24)}j`;
}

function NotifIcon({ type }: { type: string | null }) {
  switch (type) {
    case 'work_assigned':
      return <BookOpen size={13} className="text-student-color shrink-0" />;
    case 'correction_submitted':
      return <FileUp size={13} className="text-teacher-color shrink-0" />;
    case 'correction_sent':
      return <CheckCircle2 size={13} className="text-success-color shrink-0" />;
    case 'unlock_requested':
      return <Unlock size={13} className="text-teacher-color shrink-0" />;
    case 'td_unlocked':
      return <BookOpen size={13} className="text-student-color shrink-0" />;
    default:
      return <Bell size={13} className="text-text-gray shrink-0" />;
  }
}

function NotificationItem({ notif, onClose }: { notif: AppNotification; onClose: () => void }) {
  const isUnread = !notif.read_at;

  const handleClick = () => {
    onClose();
    router.visit(route('notifications.redirect', notif.id));
  };

  return (
    <button
      onClick={handleClick}
      className={`w-full flex items-start gap-2.5 px-3 py-3 text-left transition-colors cursor-pointer ${
        isUnread ? 'bg-error-color/5 hover:bg-error-color/10' : 'hover:bg-surface-color'
      }`}
    >
      <span className="mt-0.5 shrink-0">
        <NotifIcon type={notif.data.type} />
      </span>
      <div className="flex-1 min-w-0">
        <div className="flex items-start gap-2">
          {isUnread && <span className="mt-1.5 h-1.5 w-1.5 rounded-full bg-error-color shrink-0" />}
          <p
            className={`text-xs leading-snug ${
              isUnread ? 'text-text-color font-comfortaa-bold' : 'text-text-gray font-comfortaa'
            }`}
          >
            {notif.data.message}
          </p>
        </div>
        <p className="text-[10px] text-text-gray/60 mt-0.5 font-comfortaa">
          {timeAgo(notif.created_at)}
        </p>
      </div>
    </button>
  );
}

interface NotificationBellProps {
  open: boolean;
  onToggle: (open: boolean) => void;
}

export default function NotificationBell({ open, onToggle }: NotificationBellProps) {
  const { notifications } = usePage<PageProps>().props;
  const ref = useRef<HTMLDivElement>(null);
  const [filter, setFilter] = useState<NotificationFilter>('all');

  useEffect(() => {
    function onDown(e: MouseEvent) {
      if (ref.current && !ref.current.contains(e.target as Node)) onToggle(false);
    }
    if (open) document.addEventListener('mousedown', onDown);
    return () => document.removeEventListener('mousedown', onDown);
  }, [open, onToggle]);

  if (!notifications) return null;

  const { unread_count, recent } = notifications;
  const visibleNotifications =
    filter === 'unread' ? recent.filter((notif) => !notif.read_at) : recent;

  const handleClick = () => {
    onToggle(!open);
  };

  return (
    <div ref={ref} className="relative">
      <button
        onClick={handleClick}
        className="relative p-1.5 rounded-lg text-text-color/60 hover:text-text-color hover:bg-surface-color transition-colors"
        aria-label="Notifications"
      >
        <Bell size={18} />
        {unread_count > 0 && (
          <span className="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1 bg-error-color text-white text-[10px] font-comfortaa-bold rounded-full flex items-center justify-center leading-none">
            {unread_count > 9 ? '9+' : unread_count}
          </span>
        )}
      </button>

      <Transition
        as={Fragment}
        show={open}
        enter="transition ease-out duration-150"
        enterFrom="opacity-0 translate-y-1"
        enterTo="opacity-100 translate-y-0"
        leave="transition ease-in duration-100"
        leaveFrom="opacity-100 translate-y-0"
        leaveTo="opacity-0 translate-y-1"
      >
        <div className="absolute right-0 top-full mt-2 w-80 z-[100] rounded-2xl bg-secondary-color border border-border-color shadow-xl overflow-hidden">
          <div className="px-3 py-3 border-b border-border-color space-y-3">
            <div className="flex items-center justify-between gap-3">
              <span className="text-xs font-comfortaa-bold text-text-color uppercase tracking-wider">
                Notifications
              </span>
              {unread_count > 0 && (
                <button
                  onClick={() => {
                    router.post(route('notifications.readAll'), {}, { preserveScroll: true });
                    onToggle(false);
                  }}
                  className="text-[10px] font-comfortaa-bold text-student-color hover:underline"
                >
                  Tout marquer lu
                </button>
              )}
            </div>
            <div className="grid grid-cols-2 gap-1 rounded-xl bg-surface-color p-1">
              <FilterButton active={filter === 'all'} onClick={() => setFilter('all')}>
                Toutes
              </FilterButton>
              <FilterButton active={filter === 'unread'} onClick={() => setFilter('unread')}>
                Non lues
                {unread_count > 0 && (
                  <span className="ml-1 rounded-full bg-error-color px-1.5 py-0.5 text-[9px] text-white">
                    {unread_count > 9 ? '9+' : unread_count}
                  </span>
                )}
              </FilterButton>
            </div>
          </div>
          <div className="max-h-80 overflow-y-auto divide-y divide-border-color">
            {visibleNotifications.length === 0 ? (
              <div className="flex flex-col items-center gap-2 px-4 py-8 text-text-gray">
                <Bell size={20} className="opacity-30" />
                <p className="text-xs">
                  {filter === 'unread' ? 'Aucune notification non lue.' : 'Aucune notification.'}
                </p>
              </div>
            ) : (
              visibleNotifications.map((notif) => (
                <NotificationItem key={notif.id} notif={notif} onClose={() => onToggle(false)} />
              ))
            )}
          </div>
        </div>
      </Transition>
    </div>
  );
}

function FilterButton({
  active,
  onClick,
  children,
}: {
  active: boolean;
  onClick: () => void;
  children: React.ReactNode;
}) {
  return (
    <button
      type="button"
      onClick={onClick}
      className={`inline-flex items-center justify-center rounded-lg px-2 py-1.5 text-[10px] font-comfortaa-bold transition-colors ${
        active
          ? 'bg-secondary-color text-text-color shadow-warm-xs'
          : 'text-text-gray hover:text-text-color'
      }`}
    >
      {children}
    </button>
  );
}
