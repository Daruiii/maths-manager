import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';

/**
 * Custom hook to access user authentication state and role-based permissions
 *
 * @returns {Object} Authentication state
 * @returns {User | null} user - Current authenticated user or null
 * @returns {boolean} isAdmin - True if user has admin role
 * @returns {boolean} isTeacher - True if user has teacher role
 * @returns {boolean} isStudent - True if user has student role
 * @returns {boolean} isStaff - True if user is admin or teacher
 * @returns {boolean} isGuest - True if no user is authenticated
 *
 * @example
 * ```tsx
 * const { user, isAdmin, isStudent } = useAuth();
 *
 * if (isAdmin) {
 *   return <AdminDashboard />;
 * }
 * ```
 */
export const useAuth = () => {
  const { props } = usePage<PageProps>();
  const user = props.auth.user;

  const isAdmin = user?.role === 'admin';
  const isTeacher = user?.role === 'teacher';
  const isStudent = user?.role === 'student';
  const canActAsTeacher = isTeacher || isAdmin;
  const isStaff = isAdmin || isTeacher;
  const isGuest = !user;
  const hasNoRole = !!user && !user.role;

  return {
    user,
    isAdmin,
    isTeacher,
    isStudent,
    canActAsTeacher,
    isStaff,
    isGuest,
    hasNoRole,
  };
};
