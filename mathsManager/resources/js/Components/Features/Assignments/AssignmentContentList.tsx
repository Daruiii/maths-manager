import { useState } from 'react';
import AcademicAssignmentContent from '@/Components/Features/Assignments/Partials/AcademicAssignmentContent';
import TrainingAssignmentContent from '@/Components/Features/Assignments/Partials/TrainingAssignmentContent';
import { buildAssignmentContentItems } from '@/Components/Features/Assignments/Partials/assignmentContentUtils';
import type { AssignmentListItem } from '@/types/models';

interface Props {
  problems: AssignmentListItem[];
  exercises: AssignmentListItem[];
  privateExercises: AssignmentListItem[];
  accent?: 'student' | 'teacher';
  showSolutions?: boolean;
  variant?: 'academic' | 'training';
  title?: string | null;
  level?: string | null;
  instructions?: string | null;
}

export default function AssignmentContentList({
  problems,
  exercises,
  privateExercises,
  accent = 'student',
  showSolutions = false,
  variant = 'training',
  title,
  level,
  instructions,
}: Props) {
  const items = buildAssignmentContentItems(problems, exercises, privateExercises);
  const [openSolutions, setOpenSolutions] = useState<Set<string>>(new Set());

  if (items.length === 0) return null;

  function toggleSolution(key: string) {
    setOpenSolutions((prev) => {
      const next = new Set(prev);
      if (next.has(key)) {
        next.delete(key);
      } else {
        next.add(key);
      }
      return next;
    });
  }

  function openSolutionExclusive(key: string) {
    setOpenSolutions(new Set([key]));
  }

  if (variant === 'academic') {
    return (
      <AcademicAssignmentContent
        items={items}
        showSolutions={showSolutions}
        openSolutions={openSolutions}
        onToggleSolution={toggleSolution}
        onOpenSolutionExclusive={openSolutionExclusive}
        title={title}
        level={level}
        instructions={instructions}
      />
    );
  }

  return (
    <TrainingAssignmentContent
      items={items}
      accent={accent}
      showSolutions={showSolutions}
      openSolutions={openSolutions}
      onToggleSolution={toggleSolution}
    />
  );
}
