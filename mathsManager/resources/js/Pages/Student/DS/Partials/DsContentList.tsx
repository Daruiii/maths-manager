import AssignmentContentList from '@/Components/Features/Assignments/AssignmentContentList';
import type { Ds } from '@/types/models';

interface Props {
  ds: Ds;
  instructions: string;
  showSolutions?: boolean;
}

export default function DsContentList({ ds, instructions, showSolutions = false }: Props) {
  return (
    <AssignmentContentList
      problems={ds.problems}
      exercises={ds.exercises}
      privateExercises={ds.private_exercises}
      variant="academic"
      title={ds.custom_title ?? 'Devoir Surveillé'}
      level={ds.custom_level}
      instructions={instructions}
      showSolutions={showSolutions}
    />
  );
}
