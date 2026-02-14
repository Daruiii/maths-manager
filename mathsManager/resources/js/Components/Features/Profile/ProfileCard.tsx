import { Mail, GraduationCap, BookOpen, Crown } from 'lucide-react';
import { useAuth } from '@/Hooks/useAuth';
import type { ProfileStatistics } from '@/types';
import TeacherCard from '@/Components/Features/Profile/TeacherCard';
import UserAvatar from '@/Components/Common/UI/UserAvatar';

export default function ProfileCard({
  className = '',
  statistics,
}: {
  className?: string;
  statistics?: ProfileStatistics;
}) {
  const { user, isStudent, isTeacher, isAdmin } = useAuth();

  // Role configuration
  const getRoleConfig = () => {
    if (isAdmin) {
      return {
        borderColor: 'border-admin-color',
        gradient: 'from-[var(--gradient-admin-from)] to-[var(--gradient-admin-to)]',
        icon: <Crown className="w-5 h-5 text-yellow-600" />,
        label: 'Administrateur',
        textClass: 'text-yellow-600',
      };
    }
    if (isTeacher) {
      return {
        borderColor: 'border-teacher-color',
        gradient: 'from-[var(--gradient-teacher-from)] to-[var(--gradient-teacher-to)]',
        icon: <GraduationCap className="w-5 h-5 text-teacher-color" />,
        label: 'Professeur',
        textClass: 'text-teacher-color',
      };
    }
    if (isStudent) {
      return {
        borderColor: 'border-student-color',
        gradient: 'from-[var(--gradient-student-from)] to-[var(--gradient-student-to)]',
        icon: <BookOpen className="w-5 h-5 text-student-color" />,
        label: 'Élève',
        textClass: 'text-student-color',
      };
    }
    return {
      borderColor: 'border-gray-200',
      gradient: 'from-gray-400 to-gray-500',
      icon: null,
      label: 'Utilisateur',
      textClass: 'text-gray-600',
    };
  };

  const roleConfig = getRoleConfig();

  return (
    <div
      className={`bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 flex flex-col items-center text-center ${className}`}
    >
      <div className="relative group">
        <div
          className={`absolute -inset-1 bg-gradient-to-r ${roleConfig.gradient} rounded-full opacity-75 group-hover:opacity-100 transition duration-1000 group-hover:duration-200 animate-tilt blur-sm`}
        ></div>
        <UserAvatar
          user={user}
          size="2xl"
          className={`relative !border-4 !border-white dark:!border-gray-800 shadow-lg ${roleConfig.borderColor}`}
        />
        {roleConfig.icon && (
          <div className="absolute bottom-1 right-1 bg-white dark:bg-gray-800 p-2 rounded-full shadow-md">
            {roleConfig.icon}
          </div>
        )}
      </div>

      <h2 className="mt-4 text-xl font-comfortaa-bold text-gray-900 dark:text-white">
        {user?.name}
      </h2>

      <div className="mt-2 flex items-center text-gray-500 dark:text-gray-400 text-sm">
        <Mail className="w-4 h-4 mr-2" />
        {user?.email}
      </div>

      <div className="mt-6 w-full border-t border-gray-100 dark:border-gray-700 pt-6">
        {isStudent && (
          <TeacherCard
            teacherName={statistics?.teacher_name}
            teacherAvatar={statistics?.teacher_avatar}
          />
        )}

        {isTeacher && (
          <div className="grid grid-cols-2 gap-4 text-center">
            <div>
              <span className="block text-2xl font-bold text-gray-900 dark:text-white">
                {statistics?.students_count || 0}
              </span>
              <span className="text-xs text-gray-500 uppercase tracking-wider">Élèves</span>
            </div>
            <div>
              <span className="block text-2xl font-bold text-gray-900 dark:text-white">
                {statistics?.corrections_count || 0}
              </span>
              <span className="text-xs text-gray-500 uppercase tracking-wider">Corrigés</span>
            </div>
          </div>
        )}

        {isAdmin && (
          <div className="text-center">
            <span className="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
              Super Admin
            </span>
          </div>
        )}
      </div>
    </div>
  );
}
