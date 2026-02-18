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
      <span className="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-3">
        Professeur référent
      </span>
      {hasTeacher ? (
        <div className="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
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
              <span className="text-sm font-comfortaa-bold text-gray-900 dark:text-white flex items-center gap-1">
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
        <div className="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-100 dark:border-gray-700 flex items-center justify-center space-x-2 py-2">
          <GraduationCap className="w-4 h-4 text-gray-400" />
          <span className="text-sm text-gray-500 dark:text-gray-400">Aucun professeur assigné</span>
        </div>
      )}
    </div>
  );
}
