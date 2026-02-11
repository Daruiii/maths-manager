/// <reference types="vite/client" />
import { Config, route as routeFn } from 'ziggy-js';

declare global {
    var route: typeof routeFn;
}

export interface User {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'teacher' | 'student';
  avatar?: string;
  verified?: boolean;
  verified_teacher?: boolean;
  teacher_id?: number;
  teacher_group_id?: number;
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
  exercisesSheetNotStarted?: number;
  // Home Page Specific Props
  introContent?: any;
  whoamiContent?: any;
  averageGrade?: string | number;
  totalDS?: number;
  notStartedDS?: number;
  inProgressDS?: number;
  sentDS?: number;
  correctedDS?: number;
  goodAnswers?: number;
  badAnswers?: number;
  scores?: string | number;
  correctionRequests?: any;
  ds?: any;
  [key: string]: any;
}

export interface Classe {
  id: number;
  name: string;
  level: string;
  display_order: number;
  hidden: boolean;
}
