import type { BatchType } from '@/types/api';

export const STATUS_PRIORITY: Record<string, number> = {
  correction_requested: 0,
  sent: 0,
  finished: 1,
  finished_late: 1,
  ongoing: 2,
  paused: 2,
  not_started: 3,
  corrected: 4,
  correction_unlocked: 4,
};

export interface BatchGroup {
  id: number;
  name: string;
  count: number;
}

export interface AssignmentBatch {
  id: number;
  title: string;
  due_date: string | null;
  created_at: string;
  total: number;
  statuses: Record<string, number>;
  groups: BatchGroup[];
}

export interface AssignmentItem {
  id: number;
  title: string | null;
  status: string;
  student: {
    id: number;
    first_name: string;
    last_name: string;
    avatar: string | null;
    group: { id: number; name: string } | null;
  } | null;
  show_url: string;
  correction_request_id: number | null;
  correction_status: string | null;
}

export interface AssignmentGroup {
  key: string;
  name: string | null;
  items: AssignmentItem[];
}

export interface AssignmentShowProps {
  type: BatchType;
  batch: AssignmentBatch;
  items: AssignmentItem[];
}
