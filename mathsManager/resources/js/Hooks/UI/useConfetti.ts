import { useEffect, useRef } from 'react';
import confetti from 'canvas-confetti';

export function useConfetti(completed: number, total: number) {
  const prev = useRef(completed);

  useEffect(() => {
    if (total > 0 && prev.current < total && completed === total) {
      confetti({
        particleCount: 120,
        spread: 80,
        origin: { y: 0.55 },
        colors: ['#6d28d9', '#f59e0b', '#10b981', '#3b82f6', '#ec4899'],
        disableForReducedMotion: true,
      });
    }
    prev.current = completed;
  }, [completed, total]);
}
