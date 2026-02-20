import { Mail, GraduationCap, BookOpen, Crown } from 'lucide-react';
import { useAuth } from '@/Hooks/useAuth';
import type { ProfileStatistics } from '@/types';
import TeacherCard from '@/Components/Features/Profile/TeacherCard';
import UserAvatar from '@/Components/Common/UI/UserAvatar';
import Card from '@/Components/Common/UI/Card';

export default function ProfileCard({
  className = '',
  statistics,
}: {
  className?: string;
  statistics?: ProfileStatistics;
}) {
  const { user, isStudent, isTeacher, isAdmin } = useAuth();

  const getRoleConfig = () => {
    if (isAdmin) {
      return {
        borderColor: 'border-admin-color',
        gradient: 'from-[var(--gradient-admin-from)] to-[var(--gradient-admin-to)]',
        icon: <Crown className="w-5 h-5" />,
        label: 'Administrateur',
        textClass: 'text-admin-color',
      };
    }
    if (isTeacher) {
      return {
        borderColor: 'border-teacher-color',
        gradient: 'from-[var(--gradient-teacher-from)] to-[var(--gradient-teacher-to)]',
        icon: <GraduationCap className="w-5 h-5" />,
        label: 'Professeur',
        textClass: 'text-teacher-color',
      };
    }
    if (isStudent) {
      return {
        borderColor: 'border-student-color',
        gradient: 'from-[var(--gradient-student-from)] to-[var(--gradient-student-to)]',
        icon: <BookOpen className="w-5 h-5" />,
        label: 'Élève',
        textClass: 'text-student-color',
      };
    }
    return {
      borderColor: 'border-border-color',
      gradient: 'from-text-gray to-text-color',
      icon: null,
      label: 'Utilisateur',
      textClass: 'text-text-gray',
    };
  };

  const roleConfig = getRoleConfig();

  const getVariant = (): 'default' | 'teacher' | 'student' | 'danger' | 'admin' => {
    if (isAdmin) return 'admin';
    if (isTeacher) return 'teacher';
    if (isStudent) return 'student';
    return 'default';
  };

  return (
    <div className={className}>
      <Card title={roleConfig.label} variant={getVariant()} icon={roleConfig.icon || undefined}>
        <div className="flex flex-col items-center text-center">
          <div className="relative group mt-2">
            <div
              className={`absolute -inset-1 bg-gradient-to-r ${roleConfig.gradient} rounded-full opacity-75 group-hover:opacity-100 transition duration-1000 group-hover:duration-200 animate-tilt blur-sm`}
            ></div>
            <UserAvatar
              user={user}
              size="2xl"
              className={`relative !border-4 !border-secondary-color shadow-lg ${roleConfig.borderColor}`}
            />
          </div>

          <h2 className="mt-4 text-xl font-comfortaa-bold text-text-color">
            {user?.first_name} {user?.last_name}
          </h2>

          <div className="mt-2 flex items-center text-text-gray text-sm">
            <Mail className="w-4 h-4 mr-2" />
            {user?.email}
          </div>

          <div className="mt-6 w-full border-t border-border-color pt-6">
            {isStudent && (
              <TeacherCard
                teacherName={statistics?.teacher_name}
                teacherAvatar={statistics?.teacher_avatar}
                teacherRole={statistics?.teacher_role}
              />
            )}

            {isTeacher && (
              <div className="grid grid-cols-2 gap-4 text-center">
                <div>
                  <span className="block text-2xl font-bold text-text-color">
                    {statistics?.students_count || 0}
                  </span>
                  <span className="text-xs text-text-gray uppercase tracking-wider">Élèves</span>
                </div>
                <div>
                  <span className="block text-2xl font-bold text-text-color">
                    {statistics?.corrections_count || 0}
                  </span>
                  <span className="text-xs text-text-gray uppercase tracking-wider">Corrigés</span>
                </div>
              </div>
            )}
          </div>
        </div>
      </Card>
    </div>
  );
}
