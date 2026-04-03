import { BookOpen, GraduationCap, Library } from 'lucide-react';
import { CatalogueClasse, CatalogueChapter, CatalogueSubchapter } from '@/types/api';
import Select from '@/Components/Common/Form/Select';

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
  options,
  searchable = false,
}: {
  label: string;
  icon: React.ElementType;
  value: string;
  onChange: (v: string) => void;
  options: { value: string; label: string }[];
  searchable?: boolean;
}) {
  return (
    <div className="flex items-center gap-2">
      <span className="flex items-center gap-1 text-xs text-text-gray whitespace-nowrap w-28 shrink-0">
        <Icon size={12} /> {label}
      </span>
      <Select
        value={value}
        onChange={onChange}
        options={options}
        searchable={searchable}
        size="sm"
        className="flex-1"
      />
    </div>
  );
}

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

  const classeOptions = [
    { value: '', label: 'Aucune' },
    ...classes.map((c) => ({ value: String(c.id), label: c.name })),
  ];

  const chapterOptions = [
    { value: '', label: 'Aucun' },
    ...visibleChapters.map((c) => ({ value: String(c.id), label: c.title })),
  ];

  const subchapterOptions = [
    { value: '', label: 'Aucun' },
    ...visibleSubchapters.map((s) => ({ value: String(s.id), label: s.title })),
  ];

  return (
    <div className="grid grid-cols-1 gap-2">
      <CascadeSelect
        label="Classe"
        icon={GraduationCap}
        value={classeId}
        onChange={handleClasseChange}
        options={classeOptions}
      />
      <CascadeSelect
        label="Chapitre"
        icon={BookOpen}
        value={chapterId}
        onChange={handleChapterChange}
        options={chapterOptions}
        searchable
      />
      <CascadeSelect
        label="Sous-chapitre"
        icon={Library}
        value={subchapterId}
        onChange={handleSubchapterChange}
        options={subchapterOptions}
        searchable
      />
    </div>
  );
}
