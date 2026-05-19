import type { CorrectionRequest } from '@/types/models';

export function studentName(correctionRequest: CorrectionRequest): string {
  const user = correctionRequest.user;
  if (!user) return 'Élève';
  return `${user.first_name} ${user.last_name}`;
}

export function assignmentTitle(correctionRequest: CorrectionRequest): string {
  if (correctionRequest.dm) return correctionRequest.dm.custom_title ?? 'DM';
  return correctionRequest.ds?.custom_title ?? 'DS';
}

export function assignmentType(correctionRequest: CorrectionRequest): 'DS' | 'DM' {
  return correctionRequest.dm ? 'DM' : 'DS';
}
