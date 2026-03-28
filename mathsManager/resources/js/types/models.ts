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
  students?: User[];
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

// ─── DS Builder ───────────────────────────────────────────────────────────────

export interface MultipleChapter {
  id: number;
  title: string;
  theme: string;
  classe_id: number;
  classe?: Classe;
}

export interface Subchapter {
  id: number;
  title: string;
  chapter_id: number;
  chapter?: {
    id: number;
    title: string;
    class_id?: number;
    classe?: Classe;
  };
}

/** Problem global (long, cœur d'un DS) */
export interface PickableProblem {
  kind: 'problem';
  id: number;
  name: string;
  header: string | null;
  difficulty: number | null;
  time: number; // minutes
  harder_exercise: boolean;
  type: string | null;
  year: string | null;
  academy: string | null;
  multiple_chapter_id: number;
  multiple_chapter: MultipleChapter;
}

/** Exercise basique global (complément d'un DS, ou cœur d'un TD) */
export interface PickableExercise {
  kind: 'exercise';
  id: number;
  name: string;
  difficulty: number | null;
  subchapter_id: number;
  subchapter: {
    id: number;
    title: string;
    chapter: { id: number; title: string };
  };
}

export type PickableItem = PickableProblem | PickableExercise;

/** Item dans la preview du DS — inclut un uid unique pour le d&d */
export interface DSPreviewItem {
  uid: string; // `${kind}-${id}-${index}` pour gérer les doublons
  item: PickableItem;
}

export const DEFAULT_EXERCISE_MINUTES = 10;
