import { useMemo } from 'react';
import { MultipleChapter, Subchapter } from '@/types/models';
import { FilterSelectOption } from '@/Components/Common/UI/FilterControls';

interface UseExercisePickerOptionsParams {
  multipleChapters: MultipleChapter[];
  subchapters: Subchapter[];
  problemClassId: string;
  exerciseClassId: string;
}

export function useExercisePickerOptions({
  multipleChapters,
  subchapters,
  problemClassId,
  exerciseClassId,
}: UseExercisePickerOptionsParams) {
  const classesForExercises = useMemo(() => {
    const map = new Map<number, string>();
    subchapters.forEach((sub) => {
      const classId = sub.chapter?.class_id;
      const className = sub.chapter?.classe?.name;
      if (classId && className) map.set(classId, className);
    });
    return Array.from(map.entries()).map(([id, name]) => ({ id, name }));
  }, [subchapters]);

  const classNameById = useMemo(
    () => new Map(classesForExercises.map((classe) => [classe.id, classe.name])),
    [classesForExercises]
  );

  const classesForProblems = useMemo(() => {
    const map = new Map<number, string>();
    multipleChapters.forEach((ch) => {
      const classId = ch.classe_id;
      const className = ch.classe?.name ?? classNameById.get(classId);
      if (classId && className) {
        map.set(classId, className);
      } else if (classId && !map.has(classId)) {
        map.set(classId, `Classe ${classId}`);
      }
    });
    classesForExercises.forEach((classe) => map.set(classe.id, classe.name));
    return Array.from(map.entries()).map(([id, name]) => ({ id, name }));
  }, [multipleChapters, classesForExercises, classNameById]);

  const chaptersByClasse = useMemo(
    () =>
      multipleChapters.reduce<Record<string, MultipleChapter[]>>((acc, ch) => {
        const key =
          ch.classe?.name ??
          classNameById.get(ch.classe_id) ??
          (ch.classe_id ? `Classe ${ch.classe_id}` : 'Sans classe');
        if (!acc[key]) acc[key] = [];
        acc[key].push(ch);
        return acc;
      }, {}),
    [multipleChapters, classNameById]
  );

  const subchaptersByChapter = useMemo(
    () =>
      subchapters.reduce<Record<string, Subchapter[]>>((acc, sub) => {
        const key = sub.chapter?.title ?? 'Autre';
        if (!acc[key]) acc[key] = [];
        acc[key].push(sub);
        return acc;
      }, {}),
    [subchapters]
  );

  const problemClassMap = useMemo(
    () => new Map(classesForProblems.map((classe) => [String(classe.id), classe.name])),
    [classesForProblems]
  );
  const exerciseClassMap = useMemo(
    () => new Map(classesForExercises.map((classe) => [String(classe.id), classe.name])),
    [classesForExercises]
  );
  const chapterMap = useMemo(
    () => new Map(multipleChapters.map((ch) => [String(ch.id), ch.title])),
    [multipleChapters]
  );
  const subchapterMap = useMemo(
    () => new Map(subchapters.map((sub) => [String(sub.id), sub.title])),
    [subchapters]
  );

  const chapterOptions = useMemo(() => {
    const options: FilterSelectOption[] = [{ value: '', label: 'Tous chapitres' }];
    Object.entries(chaptersByClasse).forEach(([classe, chapters]) => {
      const filteredChapters = problemClassId
        ? chapters.filter((ch) => String(ch.classe_id) === problemClassId)
        : chapters;

      if (filteredChapters.length === 0) return;
      options.push({ value: `__group__${classe}`, label: classe });
      filteredChapters.forEach((ch) => {
        options.push({ value: String(ch.id), label: `— ${ch.title}` });
      });
    });

    return options;
  }, [chaptersByClasse, problemClassId]);

  const subchapterOptions = useMemo(() => {
    const options: FilterSelectOption[] = [{ value: '', label: 'Tous sous-chapitres' }];
    Object.entries(subchaptersByChapter).forEach(([chapter, subs]) => {
      const filteredSubs = exerciseClassId
        ? subs.filter((sub) => String(sub.chapter?.class_id) === exerciseClassId)
        : subs;

      if (filteredSubs.length === 0) return;
      options.push({
        value: `__group__${chapter}`,
        label: `── ${chapter} ──`,
      });
      filteredSubs.forEach((sub) => {
        options.push({ value: String(sub.id), label: `— ${sub.title}` });
      });
    });

    return options;
  }, [subchaptersByChapter, exerciseClassId]);

  return {
    classesForProblems,
    classesForExercises,
    problemClassMap,
    exerciseClassMap,
    chapterMap,
    subchapterMap,
    chapterOptions,
    subchapterOptions,
  };
}
