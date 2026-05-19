/**
 * Database Models
 * Types mirroring Laravel Eloquent models
 */

export interface AppNotification {
  id: string;
  type: string | null;
  data: {
    type: string;
    subject_type: 'ds' | 'dm' | 'td';
    message: string;
    title: string;
    link?: string;
    subject_id?: number;
    correction_id?: number;
    ds_id?: number | null;
    dm_id?: number | null;
    student_name?: string;
    grade?: string | null;
    td_id?: number;
    batch_id?: number;
  };
  read_at: string | null;
  created_at: string;
}

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
  latex_macros?: Record<string, string> | null;
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
  difficulty: number | null;
  time: number; // minutes
  harder_exercise: boolean;
  type: string | null;
  year: string | null;
  academy: string | null;
  multiple_chapter_id: number;
  multiple_chapter: MultipleChapter;
  latex_statement?: string | null;
  statement?: string | null; // HTML déjà converti (images absolues résolues) — prioritaire
  image_paths?: Record<string, string> | null; // pour les nouveaux problèmes
}

/** Exercise basique global (complément d'un DS, ou cœur d'un TD) */
export interface PickableExercise {
  kind: 'exercise';
  id: number;
  name: string;
  difficulty: number | null;
  order: number | null;
  subchapter_id: number;
  subchapter: {
    id: number;
    title: string;
    chapter: { id: number; title: string };
  };
  latex_statement?: string | null;
  image_paths?: Record<string, string> | null;
}

export interface TeacherTag {
  id: number;
  teacher_id: number;
  name: string;
  color?: string | null;
}

/** Exercice privé du prof — modèle complet */
export interface PrivateExercise {
  id: number;
  teacher_id: number;
  type: 'basic' | 'problem';
  name: string;
  notes?: string | null;
  classe_id?: number | null;
  chapter_id?: number | null;
  subchapter_id?: number | null;
  statement?: string | null;
  latex_statement?: string | null;
  solution?: string | null;
  latex_solution?: string | null;
  clue?: string | null;
  latex_clue?: string | null;
  difficulty?: number | null;
  time?: number | null;
  image_paths?: Record<string, string> | null;
  tags?: TeacherTag[];
  created_at?: string;
  updated_at?: string;
}

/** Exercice privé du prof (non global) */
export interface PickablePrivateExercise {
  kind: 'private';
  id: number;
  name: string;
  type: 'basic' | 'problem';
  difficulty: number | null;
  time: number | null;
  latex_statement?: string | null;
  image_paths?: Record<string, string> | null;
}

export type PickableItem = PickableProblem | PickableExercise | PickablePrivateExercise;

// ─── Assignments / Corrections ───────────────────────────────────────────────

export type DmStatus = 'not_started' | 'ongoing' | 'finished' | 'corrected';

export interface AssignmentListItem {
  id: number;
  title?: string | null;
  name?: string | null;
  statement?: string | null;
  latex_statement?: string | null;
  image_paths?: Record<string, string> | string | null;
  latex_solution?: string | null;
}

export type CorrectionRequestStatus = 'pending' | 'corrected' | 'refused';

export interface CorrectionRequest {
  id: number;
  status: CorrectionRequestStatus;
  pictures: string[];
  correction_pictures: string[] | null;
  correction_message: string | null;
  grade: number | null;
  message: string | null;
  created_at: string;
  updated_at: string;
  user?: Pick<User, 'id' | 'first_name' | 'last_name'>;
  dm?: { id: number; custom_title: string | null } | null;
  ds?: { id: number; custom_title?: string | null } | null;
}

export interface Dm {
  id: number;
  status: DmStatus;
  custom_title: string | null;
  custom_level: string | null;
  custom_instructions: string | null;
  teacher: Pick<User, 'id' | 'first_name' | 'last_name'> | null;
  problems: AssignmentListItem[];
  exercises: AssignmentListItem[];
  private_exercises: AssignmentListItem[];
  correction_request: CorrectionRequest | null;
}

export type DsStatus =
  | 'not_started'
  | 'ongoing'
  | 'paused'
  | 'finished'
  | 'finished_late'
  | 'sent'
  | 'corrected';

export interface Ds {
  id: number;
  status: DsStatus;
  custom_title: string | null;
  custom_level: string | null;
  custom_instructions: string | null;
  time_minutes: number;
  timer_seconds: number;
  type_bac: boolean;
  harder_exercises: boolean;
  teacher: Pick<User, 'id' | 'first_name' | 'last_name'> | null;
  problems: AssignmentListItem[];
  exercises: AssignmentListItem[];
  private_exercises: AssignmentListItem[];
  correction_request: CorrectionRequest | null;
}

export type TdStatus = 'not_started' | 'ongoing' | 'correction_requested' | 'correction_unlocked';

export interface Td {
  id: number;
  status: TdStatus;
  custom_title: string | null;
  custom_level: string | null;
  custom_instructions: string | null;
  correction_unlocked: boolean;
  teacher: Pick<User, 'id' | 'first_name' | 'last_name'> | null;
  exercises: AssignmentListItem[];
  private_exercises: AssignmentListItem[];
}

/** Item dans la preview du DS — inclut un uid unique pour le d&d */
export interface DSPreviewItem {
  uid: string; // `${kind}-${id}-${index}` pour gérer les doublons
  item: PickableItem;
}

export const DEFAULT_EXERCISE_MINUTES = 10;

// ─── PrivateExercise form ─────────────────────────────────────────────────────

export interface PrivateExerciseFormData {
  type: 'basic' | 'problem';
  name: string;
  notes: string;
  latex_statement: string;
  latex_solution: string;
  latex_clue: string;
  difficulty: string;
  time: string;
  classe_id: string;
  chapter_id: string;
  subchapter_id: string;
  tag_ids: number[];
}

export type LatexField = 'latex_statement' | 'latex_solution' | 'latex_clue';

// ─── Builder Templates ────────────────────────────────────────────────────────

export type BuilderType = 'ds' | 'td' | 'dm';

/** Payload stocké dans un template sauvegardé */
export interface TemplatePayload {
  items: DSPreviewItem[];
  title?: string;
  level?: string;
  instructions?: string;
}

export interface BuilderTemplate {
  id: number;
  teacher_id: number;
  type: BuilderType;
  name: string;
  student_group_id: number | null;
  student_group?: { id: number; name: string } | null;
  payload: TemplatePayload;
  created_at: string;
}

/** Template chargé dans le builder (payload + meta d'identification pour l'update) */
export interface LoadedTemplate extends TemplatePayload {
  id: number;
  name: string;
  student_group_id: number | null;
}
