/**
 * UI-specific Types
 * Types used purely on the frontend (not database entities)
 */

/**
 * Statistics displayed on the Profile page
 */
export interface ProfileStatistics {
  teacher_name?: string;
  teacher_avatar?: string;
  teacher_role?: string;
  students_count?: number;
  corrections_count?: number;
}
