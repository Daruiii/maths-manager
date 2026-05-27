import { Link } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';
import { Star } from 'lucide-react';
import MathAvatar from '@/Components/Common/Avatar/MathAvatar';

const REVIEWS = [
  {
    name: 'Sandra',
    quote: "Les exercices m'ont aidé toute l'année. J'entre en prépa en septembre.",
  },
  { name: 'Ruben', quote: "Grâce à lui j'ai eu mon Bac avec mention." },
  { name: 'Julie', quote: "Des progrès remarqués par ma prof, en partant d'un niveau − que 0." },
];

const GAP = 12;
const INTERVAL = 4500;
// Clone the first 2 cards at the end for seamless looping
const EXTENDED = [...REVIEWS, ...REVIEWS.slice(0, 2)];

export default function GuestReviews() {
  const containerRef = useRef<HTMLDivElement>(null);
  const trackRef = useRef<HTMLDivElement>(null);
  const justSnapped = useRef(false);
  const [idx, setIdx] = useState(0);

  useEffect(() => {
    const t = setInterval(() => setIdx((i) => i + 1), INTERVAL);
    return () => clearInterval(t);
  }, []);

  useEffect(() => {
    if (!trackRef.current || !containerRef.current) return;
    const cardW = (containerRef.current.offsetWidth - GAP) / 2;

    if (justSnapped.current) {
      justSnapped.current = false;
      return;
    }

    const offset = idx * (cardW + GAP);
    trackRef.current.style.transition = 'transform 550ms ease-in-out';
    trackRef.current.style.transform = `translateX(-${offset}px)`;

    if (idx >= REVIEWS.length) {
      const snap = setTimeout(() => {
        if (!trackRef.current || !containerRef.current) return;
        const wrapped = idx % REVIEWS.length;
        const wOffset = wrapped * (cardW + GAP);
        trackRef.current.style.transition = 'none';
        trackRef.current.style.transform = `translateX(-${wOffset}px)`;
        justSnapped.current = true;
        setIdx(wrapped);
      }, 550);
      return () => clearTimeout(snap);
    }
  }, [idx]);

  return (
    <section className="space-y-3">
      <div className="flex items-center justify-between">
        <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest">
          Ce que disent les élèves
        </p>
        <Link
          href={route('about')}
          className="text-[10px] font-comfortaa-bold text-tertiary-color hover:underline"
        >
          En savoir plus
        </Link>
      </div>

      <div ref={containerRef} className="overflow-hidden">
        <div ref={trackRef} className="flex" style={{ gap: `${GAP}px` }}>
          {EXTENDED.map((r, i) => (
            <div
              key={i}
              style={{ minWidth: `calc(50% - ${GAP / 2}px)`, width: `calc(50% - ${GAP / 2}px)` }}
              className="shrink-0 bg-secondary-color border border-border-color rounded-2xl p-4 space-y-3"
            >
              <div className="flex items-center gap-2.5">
                <MathAvatar name={r.name} />
                <div>
                  <p className="text-[11px] font-comfortaa-bold text-text-color">{r.name}</p>
                  <div className="flex gap-0.5 mt-0.5">
                    {Array.from({ length: 5 }).map((_, j) => (
                      <Star key={j} size={9} className="fill-warning-color text-warning-color" />
                    ))}
                  </div>
                </div>
              </div>
              <p className="text-xs text-text-gray leading-relaxed">&ldquo;{r.quote}&rdquo;</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
