import type { ReactNode } from 'react';

export default function SectionLabel({ children }: { children: ReactNode }) {
  return (
    <p className="text-xs font-comfortaa-bold text-text-gray uppercase tracking-wide">{children}</p>
  );
}
