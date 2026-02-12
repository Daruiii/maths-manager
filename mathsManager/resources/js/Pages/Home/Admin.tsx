import { ClipboardList, Users, Search, Filter } from 'lucide-react';
import AdminStatCard from '@/Components/UI/AdminStatCard';
import { PageProps } from '@/types';

type AdminProps = Pick<PageProps, 'correctionRequests' | 'ds'>;

/**
 * Admin Dashboard View
 * Focused on pending actions and system overview.
 */
export default function Admin({ correctionRequests, ds }: AdminProps) {
  return (
    <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
      {/* Quick Metrics Grid */}
      <nav className="grid grid-cols-1 md:grid-cols-3 gap-6" aria-label="Quick stats">
        <AdminStatCard
          title="Derniers corrections"
          count={correctionRequests?.total || 0}
          icon={<ClipboardList className="h-5 w-5" />}
          color="border-admin-color"
          href="/corrections"
        />
        <AdminStatCard
          title="Adhésions en attente"
          count={0}
          icon={<Users className="h-5 w-5" />}
          color="border-success-color"
          href="/memberships"
        />
        <AdminStatCard
          title="Devoirs actifs"
          count={ds?.length || 0}
          icon={<Search className="h-5 w-5" />}
          color="border-orange-500"
          href="/ds"
        />
      </nav>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* Pending Requests Management */}
        <section className="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col">
          <header className="flex justify-between items-center mb-6">
            <h2 className="text-xl font-comfortaa-bold">Demandes de correction</h2>
            <button
              className="p-2 hover:bg-gray-100 rounded-full transition text-text-gray"
              title="Filtrer les demandes"
            >
              <Filter className="h-4 w-4" />
            </button>
          </header>

          <div className="space-y-4">
            {correctionRequests?.data && correctionRequests.data.length > 0 ? (
              correctionRequests.data.map((req) => (
                <div
                  key={req.id}
                  className="flex items-center justify-between p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition group border border-transparent hover:border-admin-color/20"
                >
                  <div className="flex items-center gap-3">
                    <div className="w-10 h-10 rounded-full bg-admin-color/10 flex items-center justify-center font-comfortaa-bold text-admin-color">
                      {req.user?.name?.charAt(0)}
                    </div>
                    <div>
                      <p className="text-sm font-comfortaa-bold">{req.user?.name}</p>
                      <p className="text-xs text-text-gray">
                        Reçu le {new Date(req.created_at).toLocaleDateString()}
                      </p>
                    </div>
                  </div>
                  <a
                    href={`/corrections/${req.id}`}
                    className="px-4 py-2 bg-white rounded-full text-xs font-comfortaa-bold hover:shadow-md transition text-text-color"
                  >
                    Traiter
                  </a>
                </div>
              ))
            ) : (
              <p className="text-center py-8 text-text-gray font-comfortaa italic">
                Aucune demande en cours 🎉
              </p>
            )}
          </div>
        </section>

        {/* Active Homework Monitoring */}
        <section className="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col">
          <h2 className="text-xl font-comfortaa-bold mb-6">Devoirs en cours</h2>
          <div className="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
            {ds && ds.length > 0 ? (
              ds.map((homework) => (
                <div
                  key={homework.id}
                  className="flex items-center justify-between p-4 border-b border-gray-50 last:border-0 group"
                >
                  <div>
                    <p className="text-sm font-comfortaa-bold text-text-color">{homework.name}</p>
                    <p
                      className={`text-[10px] uppercase tracking-wider font-bold ${
                        homework.status === 'ongoing' ? 'text-orange-500' : 'text-success-color'
                      }`}
                    >
                      {homework.status}
                    </p>
                  </div>
                </div>
              ))
            ) : (
              <p className="text-center py-8 text-text-gray font-comfortaa italic">
                Aucun devoir actif
              </p>
            )}
          </div>
        </section>
      </div>
    </div>
  );
}
