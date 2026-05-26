export default function StudentAverageBlock({ averageGrade }: { averageGrade?: number | null }) {
  return (
    <div className="px-4 pt-4 pb-3">
      <p className="text-[10px] font-comfortaa-bold text-text-gray uppercase tracking-widest mb-3">
        Suivi
      </p>
      {averageGrade != null ? (
        <div>
          <div className="flex items-baseline gap-0.5">
            <span className="text-4xl font-cmu-serif text-text-color leading-none">
              {averageGrade.toFixed(1)}
            </span>
            <span className="text-xl font-cmu-serif text-text-gray">/20</span>
          </div>
          <p className="mm-stat-label">moyenne générale</p>
        </div>
      ) : (
        <p className="text-sm text-text-gray italic">Pas encore de note</p>
      )}
    </div>
  );
}
