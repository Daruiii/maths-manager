import type { BatchBrief, BatchType } from '@/types/api';

export interface TeacherGroupOption {
  id: number;
  name: string;
}

export type ViewMode = 'active' | 'archived';

export interface BatchColumn {
  type: BatchType;
  label: string;
  short: string;
}

export type BatchLists = Record<BatchType, BatchBrief[]>;

export const BATCH_COLUMNS: BatchColumn[] = [
  { type: 'ds', label: 'Devoirs Surveillés', short: 'DS' },
  { type: 'dm', label: 'Devoirs Maison', short: 'DM' },
  { type: 'td', label: 'Travaux Dirigés', short: 'TD' },
];

export const BATCH_TYPES = BATCH_COLUMNS.map((column) => column.type);
