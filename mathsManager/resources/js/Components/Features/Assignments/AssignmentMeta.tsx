import type { User } from '@/types/models';

interface Props {
  teacher: Pick<User, 'id' | 'first_name' | 'last_name'> | null;
  level?: string | null;
}

export default function AssignmentMeta({ teacher, level }: Props) {
  return (
    <>
      {teacher && (
        <p className="text-sm text-text-gray">
          Professeur :{' '}
          <span className="font-comfortaa-bold text-text-color">
            {teacher.first_name} {teacher.last_name}
          </span>
        </p>
      )}
      {level && (
        <span className="inline-flex text-xs px-2.5 py-0.5 rounded-full bg-student-color/10 text-student-color font-comfortaa-bold">
          {level}
        </span>
      )}
    </>
  );
}
