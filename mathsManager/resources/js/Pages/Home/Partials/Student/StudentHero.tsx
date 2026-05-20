import { useRef, useState } from 'react';
import { createPortal } from 'react-dom';
import { Link } from '@inertiajs/react';
import { ChevronDown, ChevronRight } from 'lucide-react';
import type { FlatItem } from '@/Pages/Home/Partials/AssignmentItem';
import StudentCtaDropdown from '@/Pages/Home/Partials/Student/StudentCtaDropdown';

interface Props {
  firstName: string;
  heroMessage: string;
  total: number;
  ctaHref: string;
  ctaLabel: string;
  dropdownItems: FlatItem[];
  toDoCount: number;
  ongoingCount: number;
  correctedCount: number;
}

export default function StudentHero({
  firstName,
  heroMessage,
  total,
  ctaHref,
  ctaLabel,
  dropdownItems,
  toDoCount,
  ongoingCount,
  correctedCount,
}: Props) {
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const [dropdownPos, setDropdownPos] = useState<{ top: number; left: number } | null>(null);
  const dropdownTimer = useRef<ReturnType<typeof setTimeout> | null>(null);
  const ctaRef = useRef<HTMLDivElement>(null);
  const showDropdown = dropdownItems.length > 1;

  const openDropdown = () => {
    if (ctaRef.current) {
      const rect = ctaRef.current.getBoundingClientRect();
      setDropdownPos({ top: rect.bottom + 4, left: rect.left });
    }
    if (dropdownTimer.current) clearTimeout(dropdownTimer.current);
    setDropdownOpen(true);
  };

  const closeDropdown = () => {
    dropdownTimer.current = setTimeout(() => setDropdownOpen(false), 150);
  };

  return (
    <>
      <div className="relative mm-card mm-card-style-halo mm-card-accent-student rounded-3xl px-8 py-6 animate-fadeIn overflow-hidden">
        <div
          className="absolute inset-0 overflow-hidden rounded-3xl pointer-events-none select-none"
          aria-hidden
        >
          <div className="absolute inset-0 flex items-end justify-end pr-7">
            <span className="text-[140px] font-cmu-serif text-text-color opacity-[0.04] leading-none">
              ∫
            </span>
          </div>
        </div>

        <div className="relative flex items-center gap-8">
          <div className="flex-1 min-w-0 space-y-3">
            <p className="text-[11px] font-comfortaa-bold text-student-color uppercase tracking-widest">
              Bonjour {firstName} 👋
            </p>
            <h1 className="text-2xl sm:text-3xl font-comfortaa-bold text-text-color">
              {heroMessage}
            </h1>
            {total > 0 && (
              <div
                ref={ctaRef}
                className="inline-block"
                onMouseEnter={openDropdown}
                onMouseLeave={closeDropdown}
              >
                <Link
                  href={ctaHref}
                  className="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-student-color text-white text-sm font-comfortaa-bold shadow-warm-xs hover:opacity-90 transition-opacity"
                >
                  {ctaLabel} {showDropdown ? <ChevronDown size={14} /> : <ChevronRight size={14} />}
                </Link>
              </div>
            )}
          </div>

          {total > 0 && (
            <div className="hidden sm:flex flex-col items-end gap-3 shrink-0 pr-5">
              <HeroStat value={toDoCount} label="à faire" />
              <HeroStat value={ongoingCount} label="en cours" />
              {correctedCount > 0 && (
                <HeroStat
                  value={correctedCount}
                  label={`corrigé${correctedCount > 1 ? 's' : ''}`}
                />
              )}
            </div>
          )}
        </div>
      </div>

      {dropdownOpen &&
        showDropdown &&
        dropdownPos &&
        createPortal(
          <StudentCtaDropdown
            items={dropdownItems}
            position={dropdownPos}
            onMouseEnter={openDropdown}
            onMouseLeave={closeDropdown}
          />,
          document.body
        )}
    </>
  );
}

function HeroStat({ value, label }: { value: number; label: string }) {
  return (
    <div className="text-right">
      <p className="text-2xl font-cmu-serif text-text-color leading-none">{value}</p>
      <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest mt-0.5">
        {label}
      </p>
    </div>
  );
}
