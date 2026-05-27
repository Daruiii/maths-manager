import { User } from '@/types';
import UserAvatar from '@/Components/Common/Avatar/UserAvatar';

interface Props {
  user: User;
  isSelected: boolean;
  onClick: () => void;
}

export default function ApplicantCard({ user, isSelected, onClick }: Props) {
  return (
    <button
      onClick={onClick}
      className={`w-full flex items-center gap-4 text-left p-4 rounded-xl border-2 transition-all duration-200 ${
        isSelected
          ? 'border-teacher-color bg-teacher-color/10 ring-2 ring-teacher-color/20'
          : 'border-border-color bg-secondary-color hover:border-teacher-color/50 hover:shadow-md'
      }`}
    >
      <UserAvatar
        user={user}
        size="sm"
        className={`flex-shrink-0 ${isSelected ? 'ring-2 ring-teacher-color ring-offset-2 ring-offset-primary-color' : ''}`}
      />
      <div className="overflow-hidden flex-1">
        <h4
          className={`font-bold truncate ${isSelected ? 'text-teacher-color' : 'text-text-color'}`}
        >
          {user.first_name} {user.last_name}
        </h4>
        <p className="text-sm text-text-gray truncate">
          Reçu le {new Date(user.created_at || '').toLocaleDateString('fr-FR')}
        </p>
      </div>
    </button>
  );
}
