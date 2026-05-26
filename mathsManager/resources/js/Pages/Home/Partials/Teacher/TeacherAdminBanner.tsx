import { Link } from '@inertiajs/react';
import { Bell, ChevronRight } from 'lucide-react';

export default function TeacherAdminBanner({ count = 0 }: { count?: number }) {
  if (count <= 0) return null;

  return (
    <Link
      href={route('admin.applications.index')}
      className="flex items-center justify-between gap-4 px-4 py-3 bg-admin-color/10 border border-admin-color/20 rounded-2xl hover:bg-admin-color/15 transition-colors animate-fadeInUp"
    >
      <div className="flex items-center gap-3">
        <Bell size={16} className="text-admin-color shrink-0" />
        <p className="text-sm font-comfortaa-bold text-admin-color">
          {count} candidature{count > 1 ? 's' : ''} professeur{count > 1 ? 's' : ''} en attente
        </p>
      </div>
      <ChevronRight size={14} className="text-admin-color shrink-0" />
    </Link>
  );
}
