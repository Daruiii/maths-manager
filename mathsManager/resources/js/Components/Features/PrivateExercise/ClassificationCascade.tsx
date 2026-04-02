import { BookOpen, GraduationCap, Library } from 'lucide-react';
import { CatalogueClasse, CatalogueChapter, CatalogueSubchapter } from '@/types/api';

interface Props {
  classes: CatalogueClasse[];
  chapters: CatalogueChapter[];
  subchapters: CatalogueSubchapter[];
  classeId: string;
  chapterId: string;
  subchapterId: string;
  onClasseChange: (v: string) => void;
  onChapterChange: (v: string) => void;
  onSubchapterChange: (v: string) => void;
}

function CascadeSelect({
  label,
  icon: Icon,
  value,
  onChange,
  children,
}: {
  label: string;
  icon: React.ElementType;
  value: string;
  onChange: (v: string) => void;
  children: React.ReactNode;
}) {
  return (
    <label className="flex flex-col gap-1">
      <span className="flex items-center gap-1 text-xs font-medium text-text-gray">
        <Icon size={12} /> {label}
      </span>
      <select
        value={value}
        onChange={(e) => onChange(e.target.value)}
        className={`text-xs px-2 py-1.5 rounded-lg border-2 bg-secondary-color transition-colors cursor-pointer ${
          value ? 'border-teacher-color text-text-color' : 'border-border-color text-text-gray'
        }`}
      >
        {children}
      </select>
    </label>
  );
}

/**
 * Sélecteur de classification en 3 niveaux indépendants (classe → chapitre → sous-chapitre).
 * Chaque niveau est optionnel. La sélection d'un enfant auto-popule les parents manquants.
 */
export default function ClassificationCascade({
  classes,
  chapters,
  subchapters,
  classeId,
  chapterId,
  subchapterId,
  onClasseChange,
  onChapterChange,
  onSubchapterChange,
}: Props) {
  const visibleChapters = classeId
    ? chapters.filter((c) => String(c.class_id) === classeId)
    : chapters;

  const visibleSubchapters = chapterId
    ? subchapters.filter((s) => String(s.chapter_id) === chapterId)
    : subchapters;

  function handleClasseChange(v: string) {
    onClasseChange(v);
    if (v && chapterId) {
      const chapter = chapters.find((c) => String(c.id) === chapterId);
      if (chapter && String(chapter.class_id) !== v) {
        onChapterChange('');
        onSubchapterChange('');
      }
    }
  }

  function handleChapterChange(v: string) {
    onChapterChange(v);
    if (v && !classeId) {
      const chapter = chapters.find((c) => String(c.id) === v);
      if (chapter) onClasseChange(String(chapter.class_id));
    }
    if (v && subchapterId) {
      const sub = subchapters.find((s) => String(s.id) === subchapterId);
      if (sub && String(sub.chapter_id) !== v) onSubchapterChange('');
    }
  }

  function handleSubchapterChange(v: string) {
    onSubchapterChange(v);
    if (!v) return;
    const sub = subchapters.find((s) => String(s.id) === v);
    if (!sub) return;
    if (!chapterId) {
      onChapterChange(String(sub.chapter_id));
      if (!classeId) {
        const chapter = chapters.find((c) => c.id === sub.chapter_id);
        if (chapter) onClasseChange(String(chapter.class_id));
      }
    }
  }

  return (
    <div className="grid grid-cols-3 gap-3">
      <CascadeSelect
        label="Classe"
        icon={GraduationCap}
        value={classeId}
        onChange={handleClasseChange}
      >
        <option value="">Aucune</option>
        {classes.map((c) => (
          <option key={c.id} value={String(c.id)}>
            {c.name}
          </option>
        ))}
      </CascadeSelect>

      <CascadeSelect
        label="Chapitre"
        icon={BookOpen}
        value={chapterId}
        onChange={handleChapterChange}
      >
        <option value="">Aucun</option>
        {visibleChapters.map((c) => (
          <option key={c.id} value={String(c.id)}>
            {c.title}
          </option>
        ))}
      </CascadeSelect>

      <CascadeSelect
        label="Sous-chapitre"
        icon={Library}
        value={subchapterId}
        onChange={handleSubchapterChange}
      >
        <option value="">Aucun</option>
        {visibleSubchapters.map((s) => (
          <option key={s.id} value={String(s.id)}>
            {s.title}
          </option>
        ))}
      </CascadeSelect>
    </div>
  );
}
