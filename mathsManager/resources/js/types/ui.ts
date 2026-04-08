/**
 * UI-specific Types
 * Types used purely on the frontend (not database entities).
 */

import type { LucideIcon } from 'lucide-react';

// ─── Profile ──────────────────────────────────────────────────────────────────

export interface ProfileStatistics {
  teacher_name?: string;
  teacher_avatar?: string;
  teacher_role?: string;
  students_count?: number;
  corrections_count?: number;
}

// ─── DS Builder — Sort ────────────────────────────────────────────────────────

export interface SortOption {
  by: string;
  label: string;
  defaultDir: 'asc' | 'desc';
  ascLabel?: string;
  descLabel?: string;
}

export interface ProblemSort {
  by: 'name' | 'difficulty' | 'year' | 'time' | '';
  dir: 'asc' | 'desc';
}

export interface ExerciseSort {
  by: 'name' | 'difficulty' | 'order' | '';
  dir: 'asc' | 'desc';
}

export interface PrivateSort {
  by: 'name' | 'difficulty' | 'time' | 'created_at' | '';
  dir: 'asc' | 'desc';
}

// ─── Quick Action Hub ─────────────────────────────────────────────────────────

export interface QuickAction {
  id: string;
  label: string;
  icon: LucideIcon;
  href?: string;
  badge?: number;
  disabled?: boolean;
  comingSoon?: boolean;
  separatorBefore?: boolean; // renders a divider above this item
}
