import { GraduationCap, Crown } from 'lucide-react';
import UserAvatar from '@/Components/Common/UI/UserAvatar';

interface TeacherCardProps {
  teacherName?: string;
  teacherAvatar?: string | null;
  teacherRole?: string | null;
}

export default function TeacherCard({ teacherName, teacherAvatar, teacherRole }: TeacherCardProps) {
  const hasTeacher =
    teacherName && teacherName !== 'Aucun' && teacherName !== 'Aucun professeur assigné';

  const isAdmin = teacherRole === 'admin';

  return (
    <div>
      <span className="text-xs text-text-gray uppercase tracking-wider block mb-3">
        Professeur référent
      </span>
      {hasTeacher ? (
        <div className="bg-surface-color rounded-xl p-4 border border-border-color">
          <div className="flex items-center gap-3">
            <UserAvatar
              src={
                teacherAvatar
                  ? teacherAvatar.startsWith('http')
                    ? teacherAvatar
                    : `/storage/images/${teacherAvatar}`
                  : undefined
              }
              alt={teacherName}
              size="lg"
              className="border-2 border-teacher-color shadow-sm"
            />
            <div className="flex flex-col">
              <span className="text-sm font-comfortaa-bold text-text-color flex items-center gap-1">
                {teacherName}
                {isAdmin && <Crown className="w-3.5 h-3.5 text-admin-color" />}
              </span>
              <span className="text-xs text-teacher-color flex items-center gap-1">
                <GraduationCap className="w-3 h-3" />
                Professeur
              </span>
            </div>
          </div>
        </div>
      ) : (
        <div className="bg-surface-color rounded-xl p-4 border border-border-color flex items-center justify-center space-x-2 py-2">
          <GraduationCap className="w-4 h-4 text-text-gray" />
          <span className="text-sm text-text-gray">Aucun professeur assigné</span>
        </div>
      )}
    </div>
  );
}
