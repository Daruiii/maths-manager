interface Props {
  value: string;
  onChange: (v: string) => void;
  readOnly?: boolean;
}

/**
 * Sélecteur de difficulté 1-5.
 * En readOnly, affiche juste les points colorés sans interaction.
 * Réutilisable sur tous les types d'exercices.
 */
export default function DifficultyPicker({ value, onChange, readOnly = false }: Props) {
  const numValue = Number(value);

  if (readOnly) {
    return (
      <span className="flex items-center gap-0.5">
        {Array.from({ length: 5 }, (_, i) => (
          <span
            key={i}
            className={`w-2 h-2 rounded-full ${i < numValue ? 'bg-teacher-color' : 'bg-border-color'}`}
          />
        ))}
      </span>
    );
  }

  return (
    <div className="flex items-center gap-1.5">
      {[1, 2, 3, 4, 5].map((n) => (
        <button
          key={n}
          type="button"
          onClick={() => onChange(value === String(n) ? '' : String(n))}
          title={`Difficulté ${n}`}
          className={`w-7 h-7 rounded-full border text-xs font-comfortaa-bold transition-colors ${
            numValue >= n
              ? 'bg-teacher-color border-teacher-color text-white'
              : 'border-border-color text-text-gray hover:border-teacher-color/50'
          }`}
        >
          {n}
        </button>
      ))}
      {value && (
        <button
          type="button"
          onClick={() => onChange('')}
          className="text-xxs text-text-gray hover:text-error-color transition-colors ml-1"
        >
          ✕
        </button>
      )}
    </div>
  );
}
