import { useMemo, useState } from 'react';
import { Head, router } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { BookOpen, FileText, Sparkles, ArrowUpDown, ListFilter } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import Button from '@/Components/Common/UI/Button';
import EmptyState from '@/Components/Common/UI/EmptyState';
import SearchBar from '@/Components/Common/UI/SearchBar';
import SaveTemplateModal from '@/Components/Features/Builder/SaveTemplateModal';
import ConfirmationModal from '@/Components/Common/UI/ConfirmationModal';
import TemplateRow from '@/Pages/Teacher/Bureau/Partials/TemplateRow';
import { BuilderTemplate, BuilderType, StudentGroup } from '@/types/models';
import { CONTENT_TYPE_META } from '@/Constants/contentTypes';

interface Props {
  dsTemplates: BuilderTemplate[];
  tdTemplates: BuilderTemplate[];
  dmTemplates: BuilderTemplate[];
  groups: StudentGroup[];
}

const TABS: { id: BuilderType; Icon: typeof BookOpen }[] = [
  { id: 'ds', Icon: BookOpen },
  { id: 'td', Icon: FileText },
  { id: 'dm', Icon: Sparkles },
];

const CREATE_ROUTE: Record<BuilderType, string> = {
  ds: 'teacher.ds.create',
  td: 'teacher.td.create',
  dm: 'teacher.dm.create',
};

function iconControlClass(active: boolean) {
  return `h-10 w-10 inline-flex items-center justify-center rounded-xl border transition-colors ${
    active
      ? 'border-teacher-color/50 bg-teacher-color/10 text-teacher-color'
      : 'border-border-color bg-secondary-color text-text-gray hover:text-text-color hover:border-teacher-color/40'
  }`;
}

export default function Templates({ dsTemplates, tdTemplates, dmTemplates, groups }: Props) {
  const [activeTab, setActiveTab] = useState<BuilderType>('ds');
  const [search, setSearch] = useState('');
  const [groupFilter, setGroupFilter] = useState('');
  const [sortOrder, setSortOrder] = useState('recent');
  const [renamingTemplate, setRenamingTemplate] = useState<BuilderTemplate | null>(null);
  const [deletingTemplate, setDeletingTemplate] = useState<BuilderTemplate | null>(null);

  const allByTab = useMemo(
    () => ({ ds: dsTemplates, td: tdTemplates, dm: dmTemplates }),
    [dsTemplates, tdTemplates, dmTemplates]
  );

  const filtered = useMemo(
    () =>
      allByTab[activeTab]
        .filter((tpl) => {
          if (search && !tpl.name.toLowerCase().includes(search.toLowerCase())) return false;
          if (groupFilter === 'none') return tpl.student_group_id === null;
          if (groupFilter) return tpl.student_group_id === Number(groupFilter);
          return true;
        })
        .sort((a, b) => {
          const diff = new Date(b.created_at).getTime() - new Date(a.created_at).getTime();
          return sortOrder === 'recent' ? diff : -diff;
        }),
    [allByTab, activeTab, search, groupFilter, sortOrder]
  );

  const currentTemplates = allByTab[activeTab];
  const ActiveIcon = TABS.find((t) => t.id === activeTab)!.Icon;
  const meta = CONTENT_TYPE_META[activeTab];

  return (
    <AppLayout>
      <Head title="Mes modèles" />
      <div className="max-w-4xl mx-auto px-4 py-6 space-y-6">
        <PageHeader
          title="Mes modèles"
          subtitle="Retrouvez et gérez tous vos modèles sauvegardés."
          breadcrumbs={[
            { label: 'Mon Bureau', href: route('teacher.bureau.index') },
            { label: 'Mes modèles' },
          ]}
          action={
            <Button
              variant="primary"
              size="sm"
              onClick={() => router.get(route(CREATE_ROUTE[activeTab]))}
            >
              {meta.createLabel}
            </Button>
          }
        />

        {/* Onglets */}
        <div className="flex border-b border-border-color">
          {TABS.map(({ id, Icon }) => {
            const count = allByTab[id].length;
            return (
              <button
                key={id}
                type="button"
                onClick={() => setActiveTab(id)}
                className={`flex-1 py-2.5 text-sm font-medium flex items-center justify-center gap-1.5 border-b-2 transition-colors ${
                  activeTab === id
                    ? 'border-teacher-color text-teacher-color'
                    : 'border-transparent text-text-gray hover:text-text-color'
                }`}
              >
                <Icon size={14} />
                {CONTENT_TYPE_META[id].label}
                {count > 0 && (
                  <span
                    className={`w-5 h-5 flex items-center justify-center rounded-full text-[10px] font-bold ${activeTab === id ? 'bg-teacher-color text-white' : 'bg-secondary-color text-text-gray'}`}
                  >
                    {count}
                  </span>
                )}
              </button>
            );
          })}
        </div>

        {/* Filtres */}
        {currentTemplates.length > 0 && (
          <SearchBar
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            onClear={() => setSearch('')}
            placeholder="Rechercher un modèle…"
            focusRingClass="focus:border-teacher-color"
            filter={
              groups.length > 0 ? (
                <div className="relative h-10 w-10" title="Filtrer par groupe">
                  <div className={iconControlClass(groupFilter !== '')}>
                    <ListFilter size={15} />
                  </div>
                  <select
                    aria-label="Filtrer par groupe"
                    value={groupFilter}
                    onChange={(e) => setGroupFilter(e.target.value)}
                    className="absolute inset-0 opacity-0 cursor-pointer"
                  >
                    <option value="">Tous les groupes</option>
                    <option value="none">Sans groupe</option>
                    {groups.map((g) => (
                      <option key={g.id} value={g.id}>
                        {g.name}
                      </option>
                    ))}
                  </select>
                </div>
              ) : undefined
            }
            sort={
              <div className="relative h-10 w-10" title="Trier">
                <div className={iconControlClass(sortOrder !== 'recent')}>
                  <ArrowUpDown size={15} />
                </div>
                <select
                  aria-label="Trier les modèles"
                  value={sortOrder}
                  onChange={(e) => setSortOrder(e.target.value)}
                  className="absolute inset-0 opacity-0 cursor-pointer"
                >
                  <option value="recent">Récent</option>
                  <option value="old">Ancien</option>
                </select>
              </div>
            }
          />
        )}

        {/* Liste */}
        {currentTemplates.length === 0 ? (
          <EmptyState
            icon={ActiveIcon}
            description={`Aucun modèle de ${meta.label} sauvegardé. Créez-en un depuis le builder puis cliquez sur "Sauvegarder".`}
            accentColor="teacher"
          />
        ) : filtered.length === 0 ? (
          <p className="text-sm text-text-gray text-center py-8">
            Aucun résultat pour ces filtres.
          </p>
        ) : (
          <div className="space-y-2">
            {filtered.map((tpl) => (
              <TemplateRow
                key={tpl.id}
                tpl={tpl}
                Icon={ActiveIcon}
                onLoad={() => router.get(route(CREATE_ROUTE[activeTab], { template: tpl.id }))}
                onRename={() => setRenamingTemplate(tpl)}
                onDelete={() => setDeletingTemplate(tpl)}
              />
            ))}
          </div>
        )}
      </div>

      <ConfirmationModal
        isOpen={!!deletingTemplate}
        onClose={() => setDeletingTemplate(null)}
        onConfirm={() =>
          router.delete(route('teacher.templates.destroy', { template: deletingTemplate!.id }), {
            preserveScroll: true,
            onFinish: () => setDeletingTemplate(null),
          })
        }
        title="Supprimer le modèle"
        description={`« ${deletingTemplate?.name} » sera définitivement supprimé.`}
        confirmText="Supprimer"
        type="danger"
      />

      <SaveTemplateModal
        isOpen={!!renamingTemplate}
        onClose={() => setRenamingTemplate(null)}
        type={activeTab}
        groups={groups}
        editingTemplate={
          renamingTemplate
            ? {
                id: renamingTemplate.id,
                name: renamingTemplate.name,
                student_group_id: renamingTemplate.student_group_id,
              }
            : undefined
        }
      />
    </AppLayout>
  );
}
