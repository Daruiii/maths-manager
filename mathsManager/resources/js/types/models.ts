/**
 * Database Models
 * Types mirroring Laravel Eloquent models
 */

export interface User {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  email_verified_at?: string | null;
  role: 'admin' | 'teacher' | 'student';
  status: 'active' | 'pending_approval' | 'rejected' | 'banned' | 'inactive';
  avatar?: string;
  verified?: boolean;
  verified_teacher?: boolean;
  teacher_id?: number;
  teacher_group_id?: number;
  provider?: string | null;

  // Teacher Specific Fields
  bio?: string;
  location?: string;
  teaching_level?: string;
  diploma?: string;
  phone?: string;
  created_at?: string;
}

export interface Classe {
  id: number;
  name: string;
  level: string;
  display_order: number;
  hidden: boolean;
}
