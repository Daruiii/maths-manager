import { useState, useEffect, useRef } from 'react';
import { router } from '@inertiajs/react';

export function useDsTimer(dsId: number, initialSeconds: number, isOngoing: boolean) {
  const [remaining, setRemaining] = useState(initialSeconds);

  // Always reflects the latest server value without restarting the interval
  const initialRef = useRef(initialSeconds);
  const autoPauseSentRef = useRef(false);
  initialRef.current = initialSeconds;

  useEffect(() => {
    // Sync to server's authoritative value on every status change (pause or resume)
    setRemaining(initialRef.current);
    if (!isOngoing) return;

    const tick = setInterval(() => {
      setRemaining((prev) => {
        if (prev <= 1) {
          clearInterval(tick);
          router.patch(route('ds.status.update', dsId), { status: 'finished_late' });
          return 0;
        }
        return prev - 1;
      });
    }, 1000);
    return () => clearInterval(tick);
  }, [isOngoing, dsId]);

  useEffect(() => {
    if (!isOngoing) {
      autoPauseSentRef.current = false;
    }
  }, [isOngoing]);

  useEffect(() => {
    if (!isOngoing) return;

    const reloadTimer = () => {
      router.reload({ only: ['ds'] });
    };

    const pauseInBackground = () => {
      if (autoPauseSentRef.current) return;
      autoPauseSentRef.current = true;
      router.patch(
        route('ds.status.update', dsId),
        { status: 'paused' },
        { preserveScroll: true, preserveState: true, only: ['ds'] }
      );
    };

    const handleVisibilityChange = () => {
      if (document.visibilityState === 'visible') {
        reloadTimer();
        return;
      }

      if (document.visibilityState === 'hidden') {
        pauseInBackground();
      }
    };

    window.addEventListener('pageshow', reloadTimer);
    document.addEventListener('visibilitychange', handleVisibilityChange);

    return () => {
      window.removeEventListener('pageshow', reloadTimer);
      document.removeEventListener('visibilitychange', handleVisibilityChange);
    };
  }, [isOngoing, dsId]);

  return { remaining };
}
