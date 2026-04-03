export default function AiPanel() {
  return (
    <div
      className="flex h-full cursor-not-allowed select-none flex-col gap-3 rounded-xl border border-dashed border-border-color bg-surface-color/50 p-4 opacity-70"
      aria-disabled="true"
    >
      <div>
        <p className="text-sm font-comfortaa-bold text-text-color">Panel IA (bientôt)</p>
        <p className="mt-1 text-xxs text-text-gray/80">
          Glissez un PNG/PDF ou ajoutez un fichier pour convertir son contenu en LaTeX.
        </p>
      </div>

      <div className="flex min-h-[140px] flex-1 cursor-not-allowed items-center justify-center rounded-xl border-2 border-dashed border-border-color bg-surface-color px-4 text-center">
        <p className="text-xs text-text-gray">Zone de dépôt PNG/PDF (désactivée pour le moment).</p>
      </div>

      <div className="flex items-center justify-between gap-2">
        <button
          type="button"
          disabled
          className="cursor-not-allowed rounded-lg border border-border-color px-3 py-1.5 text-xxs text-text-gray/60"
        >
          Ajouter un fichier
        </button>
        <span className="text-xxs italic text-text-gray/60">Bêta interne — bientôt dispo</span>
      </div>

      {/* TODO: IA flow */}
      {/* 1) Upload sécurisé (png/jpg/pdf) + file size guard */}
      {/* 2) OCR/vision + génération LaTeX */}
      {/* 3) Actions: insérer au curseur / remplacer le champ actif */}
      {/* TODO: macros personnalisées par prof (catalogue + raccourcis) */}
    </div>
  );
}
