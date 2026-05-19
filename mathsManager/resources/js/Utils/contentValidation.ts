import { LatexField, PrivateExerciseFormData } from '@/types/models';
import { collectContentMacroIssues } from '@/Utils/contentMacroValidation';
import { findMissingGraphReferences } from '@/Utils/latexInsertion';

export interface ContentBlockingIssue {
  key: string;
  message: string;
}

interface ContentBlockingValidationParams {
  data: PrivateExerciseFormData;
  errors: Partial<Record<keyof PrivateExerciseFormData, string>>;
  images: Record<string, string>;
  macros: Record<string, string>;
}

const LATEX_FIELD_LABELS: Record<LatexField, string> = {
  latex_statement: 'Énoncé',
  latex_solution: 'Solution',
  latex_clue: 'Indice',
};

const FORM_FIELD_LABELS: Partial<Record<keyof PrivateExerciseFormData, string>> = {
  name: 'Nom',
  latex_statement: 'Énoncé',
  latex_solution: 'Solution',
  latex_clue: 'Indice',
  notes: 'Notes',
  difficulty: 'Difficulté',
  time: 'Durée',
  classe_id: 'Classe',
  chapter_id: 'Chapitre',
  subchapter_id: 'Sous-chapitre',
  type: 'Type',
  tag_ids: 'Tags',
};

export function collectContentBlockingIssues({
  data,
  errors,
  images,
  macros,
}: ContentBlockingValidationParams): ContentBlockingIssue[] {
  const issues: ContentBlockingIssue[] = [];

  if (!data.name.trim()) {
    issues.push({
      key: 'required-name',
      message: 'Le champ « Nom » est obligatoire.',
    });
  }

  if (!data.latex_statement.trim()) {
    issues.push({
      key: 'required-latex-statement',
      message: 'Le champ « Énoncé » est obligatoire.',
    });
  }

  (Object.entries(errors) as [keyof PrivateExerciseFormData, string | undefined][]).forEach(
    ([field, message]) => {
      if (!message) return;
      issues.push({
        key: `server-${String(field)}`,
        message: `${FORM_FIELD_LABELS[field] ?? field} : ${message}`,
      });
    }
  );

  (Object.entries(LATEX_FIELD_LABELS) as [LatexField, string][]).forEach(([field, label]) => {
    const missingReferences = findMissingGraphReferences(data[field], images);
    for (const missing of missingReferences) {
      issues.push({
        key: `missing-graph-${field}-${missing.id}-${missing.idStart}`,
        message: `${label} : image introuvable pour \\graph{${missing.id}}.`,
      });
    }

    const macroIssues = collectContentMacroIssues(data[field], macros);
    for (const macroIssue of macroIssues) {
      if (!macroIssue.blocking) continue;
      issues.push({
        key: `macro-${field}-${macroIssue.key}`,
        message: `${label} : ${macroIssue.message}`,
      });
    }
  });

  return issues;
}
