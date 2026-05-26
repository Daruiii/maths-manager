/// <reference types="vite/client" />
import { route as routeFn } from 'ziggy-js';

declare global {
  var route: typeof routeFn;
}

// Re-export all types from organized files
export * from './models';
export * from './ui';
export * from './home';

// Import for PageProps usage
import type { User, Classe, AppNotification } from './models';

export interface PageProps {
  auth: {
    user: User | null;
  };
  flash?: {
    success?: string;
    error?: string;
    warning?: string;
    info?: string;
    confetti?: boolean;
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
