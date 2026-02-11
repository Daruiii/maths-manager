import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';

export const useAuth = () => {
    const { props } = usePage<PageProps>();
    const user = props.auth.user;

    const isAdmin = user?.role === 'admin';
    const isTeacher = user?.role === 'teacher';
    const isStudent = user?.role === 'student';
    const isStaff = isAdmin || isTeacher;
    const isGuest = !user;

    return {
        user,
        isAdmin,
        isTeacher,
        isStudent,
        isStaff,
        isGuest,
    };
};
