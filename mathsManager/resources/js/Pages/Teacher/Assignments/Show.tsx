import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, Calendar, ChevronRight, Home, ReceiptText, Users } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import Button from '@/Components/Common/UI/Button';
import SectionLabel from '@/Components/Common/UI/SectionLabel';
import UserAvatar from '@/Components/Common/UI/UserAvatar';
import { CONTENT_TYPE_META } from '@/Constants/contentTypes';
import { BATCH_STATUS_META } from '@/Constants/statuses';
import type { BatchType } from '@/types/api';

interface AssignmentBatch {
  id: number;
  title: string;
  due_date: string | null;
  created_at: string;
  total: number;
  statuses: Record<string, number>;
}

interface AssignmentItem {
  id: number;
  title: string | null;
  status: string;
  student: { id: number; first_name: string; last_name: string; avatar: string | null } | null;
  show_url: string;
  correction_request_id: number | null;
}

interface Props {
  type: BatchType;
  batch: AssignmentBatch;
  items: AssignmentItem[];
}

function formatDate(date: string | null): string {
  if (!date) return 'Aucune échéance';
  return new Intl.DateTimeFormat('fr-FR', { dateStyle: 'medium' }).format(new Date(date));
}

export default function AssignmentShow({ type, batch, items }: Props) {
  const meta = CONTENT_TYPE_META[type];
  const title = batch.title || meta.label;
  const Icon = meta.icon;

  return (
    <AppLayout>
      <Head title={`Assignation créée — ${title}`} />

      <div className="max-w-3xl mx-auto px-4 py-8 space-y-5">
        <div className="flex items-center justify-between gap-3">
          <Link href={route('teacher.bureau.index')}>
            <Button variant="ghost" icon={ArrowLeft} size="sm">
              Bureau
            </Button>
          </Link>
          <Link href={route('home')}>
            <Button variant="ghost" icon={Home} size="sm">
              Accueil
            </Button>
          </Link>
        </div>

        <section className="relative overflow-hidden rounded-[2rem] border border-border-color bg-secondary-color p-6 sm:p-8 shadow-sm card-dot-grid">
          <div className="absolute top-0 left-8 h-full border-l-2 border-teacher-color/30" />
          <div className="relative pl-5 space-y-6">
            <div className="flex items-start justify-between gap-4">
              <div>
                <div className="flex items-center gap-2 text-teacher-color mb-2">
                  <ReceiptText size={18} />
                  <span className="text-xs font-comfortaa-bold uppercase tracking-widest">
                    Assignation créée
                  </span>
                </div>
                <h1 className="text-2xl sm:text-3xl font-comfortaa-bold text-text-color">
                  {title}
                </h1>
                <p className="text-sm text-text-gray mt-1">
                  {batch.total} élève{batch.total > 1 ? 's' : ''} · {meta.label}
                </p>
              </div>
              <div className="w-12 h-12 rounded-2xl bg-teacher-color/10 text-teacher-color flex items-center justify-center shrink-0">
                <Icon size={22} />
              </div>
            </div>

            <div className="grid grid-cols-2 sm:grid-cols-4 gap-2">
              <div className="rounded-2xl bg-surface-color border border-border-color p-3">
                <SectionLabel>Type</SectionLabel>
                <p className="mt-1 font-comfortaa-bold text-text-color">{meta.label}</p>
              </div>
              <div className="rounded-2xl bg-surface-color border border-border-color p-3">
                <Users size={14} className="text-teacher-color mb-1" />
                <p className="font-comfortaa-bold text-text-color">{batch.total}</p>
                <p className="text-xs text-text-gray">élèves</p>
              </div>
              <div className="rounded-2xl bg-surface-color border border-border-color p-3 col-span-2">
                <Calendar size={14} className="text-teacher-color mb-1" />
                <p className="font-comfortaa-bold text-text-color">{formatDate(batch.due_date)}</p>
                <p className="text-xs text-text-gray">échéance</p>
              </div>
            </div>

            <div className="flex flex-wrap gap-2">
              {Object.entries(batch.statuses).map(([status, count]) => {
                const statusMeta = BATCH_STATUS_META[status] ?? {
                  label: status,
                  classes: 'bg-surface-color text-text-gray',
                };
                return (
                  <span
                    key={status}
                    className={`inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs border border-border-color ${statusMeta.classes}`}
                  >
                    <span className="font-comfortaa-bold">{count}</span>
                    {statusMeta.label}
                  </span>
                );
              })}
            </div>

            <div className="flex flex-wrap gap-2">
              <Button variant="teacher" size="sm" disabled title="Preview prof bientôt disponible">
                Preview bientôt
              </Button>
              <Link href={route(`teacher.${type}.create`)}>
                <Button variant="ghost" size="sm">
                  Créer un autre {meta.label}
                </Button>
              </Link>
            </div>
          </div>
        </section>

        <section className="rounded-3xl border border-border-color bg-secondary-color p-4 space-y-3">
          <SectionLabel>Élèves assignés</SectionLabel>
          <ul className="space-y-2 max-h-[360px] overflow-y-auto pr-1">
            {items.map((item) => {
              const statusMeta = BATCH_STATUS_META[item.status] ?? {
                label: item.status,
                classes: 'bg-surface-color text-text-gray',
              };
              return (
                <li
                  key={item.id}
                  className="flex items-center gap-3 px-3 py-2.5 rounded-2xl bg-surface-color border border-border-color"
                >
                  <UserAvatar
                    src={item.student?.avatar ?? undefined}
                    alt={
                      item.student
                        ? `${item.student.first_name} ${item.student.last_name}`
                        : 'Élève'
                    }
                    size="sm"
                    className="shrink-0"
                  />
                  <div className="flex-1 min-w-0">
                    <p className="font-comfortaa-bold text-sm text-text-color truncate">
                      {item.student
                        ? `${item.student.first_name} ${item.student.last_name}`
                        : 'Élève'}
                    </p>
                    <p className="text-xs text-text-gray truncate">{item.title ?? title}</p>
                  </div>
                  <span
                    className={`text-[11px] px-2 py-0.5 rounded-full font-comfortaa-bold shrink-0 ${statusMeta.classes}`}
                  >
                    {statusMeta.label}
                  </span>
                  {item.correction_request_id ? (
                    <Link
                      href={route('teacher.corrections.show', item.correction_request_id)}
                      className="text-xs font-comfortaa-bold text-teacher-color hover:underline shrink-0"
                    >
                      Corriger
                    </Link>
                  ) : (
                    <a
                      href={item.show_url}
                      target="_blank"
                      rel="noreferrer"
                      className="text-text-gray hover:text-teacher-color transition-colors shrink-0"
                    >
                      <ChevronRight size={15} />
                    </a>
                  )}
                </li>
              );
            })}
          </ul>
        </section>
      </div>
    </AppLayout>
  );
}
