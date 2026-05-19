import { Download, Pencil, Trash2, Users } from 'lucide-react';
import { LucideIcon } from 'lucide-react';
import Button from '@/Components/Common/UI/Button';
import { BuilderTemplate } from '@/types/models';

interface Props {
  tpl: BuilderTemplate;
  Icon: LucideIcon;
  onLoad: () => void;
  onRename: () => void;
  onDelete: () => void;
}

function formatDate(iso: string) {
  return new Date(iso).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  });
}

export default function TemplateRow({ tpl, Icon, onLoad, onRename, onDelete }: Props) {
  return (
    <div className="flex items-center gap-3 p-3 bg-surface-color border border-border-color rounded-xl transition-all hover:border-teacher-color/40 hover:shadow-sm">
      <div className="p-1.5 bg-teacher-color/10 rounded-lg flex-shrink-0">
        <Icon size={15} className="text-teacher-color" />
      </div>

      <div className="flex-1 min-w-0">
        <p className="text-sm font-comfortaa-bold text-text-color truncate">{tpl.name}</p>
        <div className="flex items-center gap-2 mt-0.5 text-xs text-text-gray">
          <span>
            {tpl.payload.items.length} exercice{tpl.payload.items.length !== 1 ? 's' : ''}
          </span>
          {tpl.student_group && (
            <>
              <span>·</span>
              <span className="flex items-center gap-0.5">
                <Users size={10} />
                {tpl.student_group.name}
              </span>
            </>
          )}
          <span>·</span>
          <span>{formatDate(tpl.created_at)}</span>
        </div>
      </div>

      <div className="flex items-center gap-1 flex-shrink-0">
        <Button variant="ghost" size="sm" icon={Download} onClick={onLoad}>
          Charger
        </Button>
        <button
          type="button"
          onClick={onRename}
          className="p-1.5 rounded-lg text-text-gray hover:text-teacher-color hover:bg-teacher-color/10 transition-colors"
          title="Renommer"
        >
          <Pencil size={14} />
        </button>
        <button
          type="button"
          onClick={onDelete}
          className="p-1.5 rounded-lg text-text-gray hover:text-error-color hover:bg-error-color/10 transition-colors"
          title="Supprimer"
        >
          <Trash2 size={14} />
        </button>
      </div>
    </div>
  );
}
