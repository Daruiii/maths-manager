import LatexRenderer from '@/Components/Common/UI/LatexRenderer';
import LegacyKatexHtmlBlock from '@/Components/Common/UI/LegacyKatexHtmlBlock';
import type { AssignmentContentItem } from '@/Components/Features/Assignments/Partials/assignmentContentUtils';
import { imageMap } from '@/Components/Features/Assignments/Partials/assignmentContentUtils';

export default function AssignmentStatement({ item }: { item: AssignmentContentItem }) {
  if (item.statement?.trim()) {
    return <LegacyKatexHtmlBlock html={item.statement} />;
  }

  if (item.latex_statement?.trim()) {
    return <LatexRenderer latex={item.latex_statement} images={imageMap(item)} />;
  }

  return <p className="text-xs text-text-gray italic">Énoncé non disponible.</p>;
}
