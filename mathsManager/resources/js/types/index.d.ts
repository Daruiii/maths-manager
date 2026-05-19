/// <reference types="vite/client" />
import { route as routeFn } from 'ziggy-js';

declare global {
  var route: typeof routeFn;
}

// Re-export all types from organized files
export * from './models';
export * from './ui';

// Import for PageProps usage
import type { User, Classe, AppNotification } from './models';

export interface HomePendingCorrectionItem {
  id: number;
  student_name: string;
  subject_title: string;
  subject_type: 'ds' | 'dm';
  created_at: string;
}

export interface HomeUnlockRequestItem {
  id: number;
  student_name: string;
  title: string;
  updated_at: string;
}

export interface HomeActiveAssignment {
  id: number;
  title: string;
  status: string;
  due_date?: string | null;
}

// Props passed to the Home Page directly from HomeController
export interface HomeProps {
  // Guest
  introContent?: Record<string, unknown>;
  whoamiContent?: Record<string, unknown>;
  // Admin
  pendingTeachersCount?: number;
  // Teacher
  pendingCorrections?: { count: number; items: HomePendingCorrectionItem[] };
  unlockRequests?: { count: number; items: HomeUnlockRequestItem[] };
  // Student
  activeAssignments?: {
    ds: HomeActiveAssignment[];
    dm: HomeActiveAssignment[];
    td: HomeActiveAssignment[];
  };
  averageGrade?: number | null;
  correctedCount?: number;
}

export interface PageProps {
  auth: {
    user: User | null;
  };
  flash?: {
    success?: string;
    error?: string;
    warning?: string;
    info?: string;
  };
  appName?: string;
  appEnv?: string;
  classes?: Classe[];
  dsNotStarted?: number;
  tdNotStarted?: number;
  notifications?: {
    unread_count: number;
    recent: AppNotification[];
  } | null;
  [key: string]: unknown;
}
