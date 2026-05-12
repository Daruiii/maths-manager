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
  finished: { label: 'Terminé', classes: 'bg-teacher-color/10 text-teacher-color' },
  finished_late: {
    label: 'Terminé (en retard)',
    classes: 'bg-warning-color/10 text-warning-color',
  },
  sent: { label: 'Copie envoyée', classes: 'bg-teacher-color/10 text-teacher-color' },
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

// ─── Pending action labels by type ────────────────────────────────────────────

export const BATCH_PENDING_LABEL: Record<BatchType, string> = {
  ds: 'copie à corriger',
  dm: 'devoir à corriger',
  td: 'déblocage demandé',
};
