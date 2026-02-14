/// <reference types="vite/client" />
import { route as routeFn } from 'ziggy-js';

declare global {
  var route: typeof routeFn;
}

// Re-export all types from organized files
export * from './models';
export * from './ui';

// Import for PageProps usage
import type { User, Classe } from './models';

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
  exercisesSheetNotStarted?: number;
  // Home Page Specific Props
  introContent?: {
    title?: string;
    content?: string;
    image?: string;
  };
  whoamiContent?: {
    title?: string;
    content?: string;
    image?: string;
  };
  averageGrade?: string | number;
  totalDS?: number;
  notStartedDS?: number;
  inProgressDS?: number;
  sentDS?: number;
  correctedDS?: number;
  goodAnswers?: number;
  badAnswers?: number;
  scores?: string | number;
  correctionRequests?: {
    total: number;
    data: Array<{
      id: number;
      user?: { name: string };
      created_at: string;
    }>;
  };
  ds?: Array<{
    id: number;
    name: string;
    status: string;
  }>;
  [key: string]: unknown;
}
