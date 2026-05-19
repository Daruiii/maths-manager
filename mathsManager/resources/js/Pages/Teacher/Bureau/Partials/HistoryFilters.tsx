import SearchBar from '@/Components/Common/UI/SearchBar';
import { BureauHistoryFilters } from '@/types/api';
import { HISTORY_TYPE_LABELS } from '@/Pages/Teacher/Bureau/Partials/historyMeta';

interface Props {
  filters: BureauHistoryFilters;
  onFilterChange: (patch: Partial<BureauHistoryFilters>) => void;
}

export default function HistoryFilters({ filters, onFilterChange }: Props) {
  return (
    <div className="bg-surface-color border border-border-color rounded-2xl p-4 space-y-3">
      <SearchBar
        placeholder="Rechercher un événement, un élève, un type..."
        value={filters.search}
        onChange={(e) => onFilterChange({ search: e.target.value })}
        onClear={() => onFilterChange({ search: '' })}
        focusRingClass="focus:border-teacher-color focus:ring-teacher-color"
      />

      <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <label className="text-xs text-text-gray space-y-1">
          <span className="block">Périmètre</span>
          <select
            value={filters.scope}
            onChange={(e) =>
              onFilterChange({ scope: e.target.value as BureauHistoryFilters['scope'] })
            }
            className="w-full h-10 px-3 bg-secondary-color border border-border-color rounded-xl text-sm"
          >
            <option value="all">Tout</option>
            <option value="assignments">Assignations</option>
            <option value="students">Élèves & invitations</option>
            <option value="corrections">Corrections</option>
          </select>
        </label>

        <label className="text-xs text-text-gray space-y-1">
          <span className="block">Type</span>
          <select
            value={filters.type}
            onChange={(e) =>
              onFilterChange({ type: e.target.value as BureauHistoryFilters['type'] })
            }
            className="w-full h-10 px-3 bg-secondary-color border border-border-color rounded-xl text-sm"
          >
            <option value="all">Tous les types</option>
            {Object.entries(HISTORY_TYPE_LABELS).map(([value, label]) => (
              <option key={value} value={value}>
                {label}
              </option>
            ))}
          </select>
        </label>

        <label className="text-xs text-text-gray space-y-1">
          <span className="block">Tri</span>
          <select
            value={filters.sort}
            onChange={(e) =>
              onFilterChange({ sort: e.target.value as BureauHistoryFilters['sort'] })
            }
            className="w-full h-10 px-3 bg-secondary-color border border-border-color rounded-xl text-sm"
          >
            <option value="desc">Plus récent d'abord</option>
            <option value="asc">Plus ancien d'abord</option>
          </select>
        </label>
      </div>
    </div>
  );
}
