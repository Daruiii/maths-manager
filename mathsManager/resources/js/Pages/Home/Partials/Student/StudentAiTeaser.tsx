export default function StudentAiTeaser() {
  return (
    <div className="relative overflow-hidden mm-card mm-card-style-corner mm-card-accent-student rounded-2xl px-4 py-3.5 space-y-1.5">
      <div className="absolute -right-3 -bottom-3 pointer-events-none select-none" aria-hidden>
        <span className="text-[56px] font-cmu-serif text-student-color opacity-[0.06] leading-none">
          ∑
        </span>
      </div>
      <div className="relative flex items-center gap-2">
        <span className="font-cmu-serif text-student-color text-sm leading-none">✦</span>
        <p className="text-xs font-comfortaa-bold text-text-color">Parcours IA personnalisés</p>
        <span className="ml-auto text-[9px] font-comfortaa-bold text-student-color uppercase tracking-widest border border-student-color/30 bg-student-color/5 px-1.5 py-0.5 rounded-md shrink-0">
          Bientôt
        </span>
      </div>
      <p className="relative text-[10px] text-text-gray leading-relaxed pr-10">
        Exercices adaptés à tes résultats DS, progression débloquée étape par étape.
      </p>
    </div>
  );
}
