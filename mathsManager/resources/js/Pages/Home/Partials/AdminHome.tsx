import { Link } from '@inertiajs/react';
import { Bell } from 'lucide-react';

interface AdminHomeProps {
  pendingTeachersCount?: number;
}

export default function AdminHome({ pendingTeachersCount = 0 }: AdminHomeProps) {
  return (
    <div className="space-y-12">
      {pendingTeachersCount > 0 && (
        <div className="bg-warning-color/10 border-2 border-warning-color rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
          <div className="flex items-center gap-4 text-warning-color">
            <Bell size={32} />
            <div>
              <h3 className="font-bold text-lg">Candidatures professeurs en attente</h3>
              <p>Vous avez {pendingTeachersCount} candidature(s) de professeur à examiner.</p>
            </div>
          </div>
          <Link
            href={route('admin.applications.index')}
            className="w-full sm:w-auto text-center px-6 py-3 bg-warning-color text-white font-bold rounded-xl hover:bg-warning-color/90 transition-colors"
          >
            Examiner
          </Link>
        </div>
      )}
      {/* Future Admin Dashboard components (Corrections requests, DS states) */}
    </div>
  );
}
