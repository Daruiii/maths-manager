import { useEffect, useRef, useState } from 'react';
import { Check, MoreHorizontal, Plus, Trash2, X } from 'lucide-react';
import axios from 'axios';
import { TeacherTag } from '@/types/models';

// ─── Constantes ───────────────────────────────────────────────────────────────

const PRESET_COLORS = [
  '#6366f1',
  '#8b5cf6',
  '#ec4899',
  '#ef4444',
  '#f59e0b',
  '#10b981',
  '#3b82f6',
  '#14b8a6',
];

// ─── Sous-composants ──────────────────────────────────────────────────────────

function TagPill({
  tag,
  selected,
  onClick,
}: {
  tag: TeacherTag;
  selected: boolean;
  onClick: () => void;
}) {
  const bg = tag.color ?? '#6b7280';
  return (
    <button
      type="button"
      onClick={onClick}
      className="w-full flex items-center justify-between px-2 py-1.5 rounded-lg hover:bg-surface-color/60 transition-colors group"
    >
      <span className="flex items-center gap-2 text-xs text-text-color truncate">
        <span className="w-3 h-3 rounded-sm flex-shrink-0" style={{ backgroundColor: bg }} />
        {tag.name}
      </span>
      {selected && <Check size={12} className="text-teacher-color flex-shrink-0" />}
    </button>
  );
}

function TagEditor({
  tag,
  onUpdate,
  onDelete,
  onClose,
}: {
  tag: TeacherTag;
  onUpdate: (tag: TeacherTag) => void;
  onDelete: (id: number) => void;
  onClose: () => void;
}) {
  const [confirmDelete, setConfirmDelete] = useState(false);

  async function pick(color: string | null) {
    try {
      const { data } = await axios.patch(route('teacher.tags.update', tag.id), { color });
      onUpdate(data);
    } catch {
      // silencieux
    }
    onClose();
  }

  async function handleDelete() {
    try {
      await axios.delete(route('teacher.tags.destroy', tag.id));
      onDelete(tag.id);
    } catch {
      // silencieux
    }
    onClose();
  }

  return (
    <div className="px-2 pb-2 space-y-2">
      <p className="text-xxs text-text-gray px-1">Couleur</p>
      <div className="flex flex-wrap gap-1.5">
        <button
          type="button"
          onClick={() => pick(null)}
          title="Sans couleur"
          className={`w-5 h-5 rounded border-2 bg-secondary-color ${!tag.color ? 'border-text-color' : 'border-border-color'}`}
        />
        {PRESET_COLORS.map((c) => (
          <button
            key={c}
            type="button"
            onClick={() => pick(c)}
            title={c}
            className={`w-5 h-5 rounded border-2 transition-colors ${tag.color === c ? 'border-text-color' : 'border-transparent'}`}
            style={{ backgroundColor: c }}
          />
        ))}
      </div>

      {confirmDelete ? (
        <div className="flex items-center gap-1.5">
          <span className="text-xxs text-text-gray flex-1">Supprimer ?</span>
          <button
            type="button"
            onClick={handleDelete}
            className="px-2 py-0.5 text-xxs bg-error-color text-white rounded transition-opacity hover:opacity-80"
          >
            Oui
          </button>
          <button
            type="button"
            onClick={() => setConfirmDelete(false)}
            className="px-2 py-0.5 text-xxs border border-border-color text-text-gray rounded hover:text-text-color transition-colors"
          >
            Non
          </button>
        </div>
      ) : (
        <button
          type="button"
          onClick={() => setConfirmDelete(true)}
          className="flex items-center gap-1.5 w-full px-1 py-1 text-xxs text-error-color hover:bg-error-color/10 rounded transition-colors"
        >
          <Trash2 size={11} /> Supprimer le tag
        </button>
      )}
    </div>
  );
}

// ─── Composant principal ──────────────────────────────────────────────────────

interface Props {
  allTags: TeacherTag[];
  selectedIds: number[];
  onChange: (ids: number[]) => void;
  onCreateTag: (name: string, color?: string) => Promise<TeacherTag | null>;
  onUpdateTag?: (tag: TeacherTag) => void;
  onDeleteTag?: (id: number) => void;
}

export default function TagSelector({
  allTags,
  selectedIds,
  onChange,
  onCreateTag,
  onUpdateTag,
  onDeleteTag,
}: Props) {
  const [isOpen, setIsOpen] = useState(false);
  const [search, setSearch] = useState('');
  const [editingTagId, setEditingTagId] = useState<number | null>(null);
  const [isCreating, setIsCreating] = useState(false);
  const containerRef = useRef<HTMLDivElement>(null);
  const inputRef = useRef<HTMLInputElement>(null);

  useEffect(() => {
    if (!isOpen) return;
    const handler = (e: MouseEvent) => {
      if (containerRef.current && !containerRef.current.contains(e.target as Node)) {
        setIsOpen(false);
        setSearch('');
        setEditingTagId(null);
      }
    };
    document.addEventListener('mousedown', handler);
    return () => document.removeEventListener('mousedown', handler);
  }, [isOpen]);

  useEffect(() => {
    if (isOpen) setTimeout(() => inputRef.current?.focus(), 50);
  }, [isOpen]);

  const lowerSearch = search.toLowerCase().trim();
  const filtered = allTags.filter((t) => t.name.toLowerCase().includes(lowerSearch));
  const exactMatch = allTags.some((t) => t.name.toLowerCase() === lowerSearch);
  const canCreate = lowerSearch.length > 0 && !exactMatch;

  const selectedTags = allTags.filter((t) => selectedIds.includes(t.id));

  function toggle(id: number) {
    onChange(selectedIds.includes(id) ? selectedIds.filter((x) => x !== id) : [...selectedIds, id]);
  }

  async function handleCreate() {
    if (!lowerSearch || isCreating) return;
    setIsCreating(true);
    const tag = await onCreateTag(search.trim(), PRESET_COLORS[0]);
    if (tag) onChange([...selectedIds, tag.id]);
    setSearch('');
    setIsCreating(false);
  }

  function handleTagUpdated(updated: TeacherTag) {
    setEditingTagId(null);
    onUpdateTag?.(updated);
  }

  function handleTagDeleted(id: number) {
    onChange(selectedIds.filter((x) => x !== id));
    onDeleteTag?.(id);
  }

  return (
    <div ref={containerRef} className="relative">
      {/* Trigger */}
      <button
        type="button"
        onClick={() => setIsOpen((v) => !v)}
        className="w-full min-h-[34px] flex flex-wrap items-center gap-1.5 px-2 py-1.5 bg-surface-color border border-border-color rounded-lg text-left hover:border-teacher-color/50 transition-colors"
      >
        {selectedTags.length === 0 ? (
          <span className="text-xs text-text-gray/50">Ajouter des tags…</span>
        ) : (
          selectedTags.map((tag) => (
            <span
              key={tag.id}
              className="flex items-center gap-1 px-2 py-0.5 rounded-sm text-white text-xxs font-medium"
              style={{ backgroundColor: tag.color ?? '#6b7280' }}
            >
              {tag.name}
              <X
                size={9}
                className="cursor-pointer hover:opacity-70"
                onClick={(e) => {
                  e.stopPropagation();
                  toggle(tag.id);
                }}
              />
            </span>
          ))
        )}
      </button>

      {/* Dropdown */}
      {isOpen && (
        <div className="absolute z-30 mt-1 w-full bg-primary-color border border-border-color rounded-xl shadow-xl overflow-hidden">
          {/* Search */}
          <div className="px-2 pt-2 pb-1">
            <input
              ref={inputRef}
              type="text"
              value={search}
              onChange={(e) => {
                setSearch(e.target.value);
                setEditingTagId(null);
              }}
              placeholder="Rechercher…"
              className="w-full px-2 py-1.5 text-xs bg-secondary-color border border-border-color rounded-lg text-text-color placeholder:text-text-gray/50 outline-none focus:border-teacher-color transition-colors"
            />
          </div>

          {/* List */}
          <div className="px-2 pb-1 max-h-52 overflow-y-auto custom-scrollbar space-y-0.5">
            {filtered.map((tag) => (
              <div key={tag.id}>
                <div className="flex items-center gap-1 group">
                  <div className="flex-1 min-w-0">
                    <TagPill
                      tag={tag}
                      selected={selectedIds.includes(tag.id)}
                      onClick={() => toggle(tag.id)}
                    />
                  </div>
                  <button
                    type="button"
                    onClick={() => setEditingTagId(editingTagId === tag.id ? null : tag.id)}
                    className="p-1 text-text-gray hover:text-text-color opacity-0 group-hover:opacity-100 hover:opacity-100 transition-opacity rounded"
                  >
                    <MoreHorizontal size={12} />
                  </button>
                </div>
                {editingTagId === tag.id && (
                  <TagEditor
                    tag={tag}
                    onUpdate={handleTagUpdated}
                    onDelete={handleTagDeleted}
                    onClose={() => setEditingTagId(null)}
                  />
                )}
              </div>
            ))}

            {filtered.length === 0 && !canCreate && (
              <p className="text-xxs text-text-gray text-center py-3">Aucun tag trouvé</p>
            )}
          </div>

          {/* Create */}
          {canCreate && (
            <div className="border-t border-border-color px-2 py-1.5">
              <button
                type="button"
                onClick={handleCreate}
                disabled={isCreating}
                className="w-full flex items-center gap-2 px-2 py-1.5 text-xs text-text-gray hover:text-teacher-color hover:bg-surface-color/60 rounded-lg transition-colors disabled:opacity-50"
              >
                <Plus size={12} />
                {isCreating ? (
                  'Création…'
                ) : (
                  <>
                    Créer{' '}
                    <span className="font-comfortaa-bold text-text-color ml-1">
                      "{search.trim()}"
                    </span>
                  </>
                )}
              </button>
            </div>
          )}
        </div>
      )}
    </div>
  );
}
