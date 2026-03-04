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
  calendly_invite_sent?: boolean;
  calendly_invite_sent_at?: string | null;
  avatar?: string;
  verified?: boolean;
  verified_teacher?: boolean;
  teacher_id?: number;
  group_id?: number | null;
  teacher_group_id?: number;
  provider?: string | null;

  // Teacher Specific Fields
  bio?: string;
  location?: string;
  teaching_level?: string;
  diploma?: string;
  phone?: string;
  created_at?: string;
  approved_at?: string | null;
}

export interface StudentGroup {
  id: number;
  teacher_id: number;
  name: string;
  students_count?: number;
}

export interface TeacherInvitation {
  id: number;
  teacher_id: number;
  group_id?: number | null;
  code: string;
  max_uses: number;
  current_uses: number;
  is_active: boolean;
  expires_at: string | null;
}

export interface Classe {
  id: number;
  name: string;
  level: string;
  display_order: number;
  hidden: boolean;
}
