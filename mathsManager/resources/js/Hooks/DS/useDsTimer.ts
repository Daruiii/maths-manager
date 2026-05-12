import { useState, useEffect } from 'react';
import { router } from '@inertiajs/react';

export function useDsTimer(dsId: number, initialSeconds: number, isOngoing: boolean) {
  const [remaining, setRemaining] = useState(initialSeconds);

  useEffect(() => {
    setRemaining(initialSeconds);
  }, [initialSeconds]);

  useEffect(() => {
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

  return { remaining };
}
