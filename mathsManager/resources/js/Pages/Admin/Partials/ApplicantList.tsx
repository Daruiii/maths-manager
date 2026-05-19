import { User } from '@/types/models'; // Use exact path to models
import ApplicantCard from '@/Pages/Admin/Partials/ApplicantCard';
import SearchBar from '@/Components/Common/UI/SearchBar';
import { FilterStatus } from '@/Hooks/Admin/useTeacherApplications';
import Filter, { FilterOption } from '@/Components/Common/UI/Filter';

interface Props {
  applications: User[];
  selectedUserId: number | null;
  onSelect: (id: number) => void;
  filter: FilterStatus;
  setFilter: (filter: FilterStatus) => void;
  searchQuery: string;
  setSearchQuery: (query: string) => void;
}

export default function ApplicantList({
  applications,
  selectedUserId,
  onSelect,
  filter,
  setFilter,
  searchQuery,
  setSearchQuery,
}: Props) {
  const filterOptions: FilterOption[] = [
    { value: 'all', label: 'Toutes les candidatures' },
    { value: 'to_invite', label: 'À inviter' },
    { value: 'invited', label: 'Invitations envoyées' },
  ];

  return (
    <div className="flex flex-col h-full gap-4 w-full">
      <div className="flex items-center justify-between">
        <h3 className="text-xl font-bold">Candidats</h3>
        <span className="text-sm font-bold bg-teacher-color/10 text-teacher-color px-3 py-1 rounded-full">
          {applications.length} en attente
        </span>
      </div>

      {/* Barre de recherche & filtres */}
      <div className="relative">
        <SearchBar
          placeholder="Rechercher un candidat..."
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
          onClear={() => setSearchQuery('')}
          focusRingClass="focus:border-teacher-color focus:ring-teacher-color"
          filter={
            <Filter
              options={filterOptions}
              value={filter}
              onChange={(val) => setFilter(val as FilterStatus)}
              isActive={filter !== 'all'}
              activeClassName="bg-teacher-color border-teacher-color text-white"
            />
          }
        />
      </div>

      {/* Liste scrollable */}
      <div className="space-y-3 lg:max-h-[calc(100vh-280px)] lg:overflow-y-auto pr-2 sm:pr-4 custom-scrollbar flex-1">
        {applications.length === 0 ? (
          <div className="text-center py-8 text-text-gray text-sm">
            Aucun candidat trouvé pour ce filtre.
          </div>
        ) : (
          applications.map((user) => (
            <ApplicantCard
              key={user.id}
              user={user}
              isSelected={selectedUserId === user.id}
              onClick={() => onSelect(user.id)}
            />
          ))
        )}
      </div>
    </div>
  );
}
