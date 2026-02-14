/**
 * Database Models
 * Types mirroring Laravel Eloquent models
 */

export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at?: string | null;
  role: 'admin' | 'teacher' | 'student';
  avatar?: string;
  verified?: boolean;
  verified_teacher?: boolean;
  teacher_id?: number;
  teacher_group_id?: number;
}

export interface Classe {
  id: number;
  name: string;
  level: string;
  display_order: number;
  hidden: boolean;
}
