import type { BatchType } from '@/types/api';

// ─── Status display meta (pills) ──────────────────────────────────────────────

interface StatusMeta {
  label: string;
  classes: string;
}

export const BATCH_STATUS_META: Record<string, StatusMeta> = {
  // DS + DM
  not_started: { label: 'Non commencé', classes: 'bg-surface-color text-text-gray' },
  ongoing: { label: 'En cours', classes: 'bg-tertiary-color/10 text-tertiary-color' },
  paused: { label: 'En pause', classes: 'bg-surface-color text-text-gray' },
  finished: { label: 'Terminé', classes: 'bg-tertiary-color/10 text-tertiary-color' },
  finished_late: {
    label: 'Terminé (en retard)',
    classes: 'bg-warning-color/10 text-warning-color',
  },
  sent: { label: 'Copie envoyée', classes: 'bg-warning-color/10 text-warning-color' },
  corrected: { label: 'Corrigé', classes: 'bg-success-color/10 text-success-color' },
  // TD
  correction_requested: {
    label: 'Déblocage demandé',
    classes: 'bg-warning-color/10 text-warning-color',
  },
  correction_unlocked: {
    label: 'Correction débloquée',
    classes: 'bg-success-color/10 text-success-color',
  },
};

// ─── Teacher-side label overrides (action-oriented) ──────────────────────────
// These replace the student-facing labels in teacher views.

export const TEACHER_STATUS_LABEL: Record<string, string> = {
  sent: 'À corriger',
  correction_requested: 'À débloquer',
  finished: 'Copie non envoyée',
  finished_late: 'Copie non envoyée',
};

export function getTeacherStatusLabel(status: string, type?: BatchType): string {
  if (type === 'dm' && (status === 'finished' || status === 'finished_late')) return 'À corriger';
  return TEACHER_STATUS_LABEL[status] ?? BATCH_STATUS_META[status]?.label ?? status;
}

// ─── Priority order for status filter cards (max 4 shown) ────────────────────
// Most teacher-actionable statuses first.

export const STATUS_DISPLAY_PRIORITY = [
  'sent',
  'correction_requested',
  'corrected',
  'correction_unlocked',
  'finished_late',
  'finished',
  'ongoing',
  'paused',
  'not_started',
];

// ─── Pending action labels by type ────────────────────────────────────────────

export const BATCH_PENDING_LABEL: Record<BatchType, string> = {
  ds: 'copie à corriger',
  dm: 'devoir à corriger',
  td: 'déblocage demandé',
};
