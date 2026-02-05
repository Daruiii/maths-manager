/// <reference types="vite/client" />

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
  [key: string]: any;
}

export interface Classe {
  id: number;
  name: string;
  level: string;
  display_order: number;
  hidden: boolean;
}
