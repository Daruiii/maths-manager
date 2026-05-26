export interface HomePendingCorrectionItem {
  id: number;
  student_name: string;
  subject_title: string;
  subject_type: 'ds' | 'dm';
  batch_id?: number | null;
  batch_url?: string | null;
  created_at: string;
}

export interface HomeUnlockRequestItem {
  batch_id: number | null;
  title: string;
  count: number;
  batch_url: string | null;
  updated_at: string;
}

export interface HomeFeedbackItem {
  id: number;
  type: 'ds' | 'dm';
  title: string;
  status: 'pending' | 'corrected';
  grade: number | null;
  href: string;
  updated_at: string;
}

export interface HomeFeedbackSummary {
  corrected: number;
  pending: number;
}

export interface HomeActiveAssignment {
  id: number;
  title: string;
  status: string;
  due_date?: string | null;
}

export interface HomeProps {
  introContent?: Record<string, unknown>;
  whoamiContent?: Record<string, unknown>;
  pendingTeachersCount?: number;
  pendingCorrections?: { count: number; items: HomePendingCorrectionItem[] };
  unlockRequests?: { count: number; items: HomeUnlockRequestItem[] };
  activeStudentsCount?: number;
  assignedThisMonth?: number;
  activeBatches?: { ds: number; dm: number; td: number };
  activeAssignments?: {
    ds: HomeActiveAssignment[];
    dm: HomeActiveAssignment[];
    td: HomeActiveAssignment[];
  };
  averageGrade?: number | null;
  correctedCount?: number;
  feedbackSummary?: HomeFeedbackSummary;
  recentFeedbackItems?: HomeFeedbackItem[];
}
