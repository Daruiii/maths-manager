import type { DmStatus, DsStatus, TdStatus } from '@/types/models';

export interface StudentResourceBrief {
  id: number;
  custom_title: string | null;
  custom_level: string | null;
  teacher: { first_name: string; last_name: string } | null;
}

export interface StudentDsResource extends StudentResourceBrief {
  status: DsStatus;
  grade?: number | null;
}

export interface StudentDmResource extends StudentResourceBrief {
  status: DmStatus;
  grade?: number | null;
}

export interface StudentTdResource extends StudentResourceBrief {
  status: TdStatus;
}
