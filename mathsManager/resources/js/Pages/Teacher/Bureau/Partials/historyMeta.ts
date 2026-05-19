import {
  Activity,
  BookOpen,
  CheckCircle2,
  FileText,
  Sparkles,
  UserPlus,
  Users,
} from 'lucide-react';
import { BureauActivityType } from '@/types/api';

export const HISTORY_TYPE_LABELS: Record<BureauActivityType, string> = {
  ds_assigned: 'DS assigné',
  td_assigned: 'TD assigné',
  dm_assigned: 'DM assigné',
  student_joined: 'Élève rattaché',
  invitation_configured: 'Lien invitation',
  correction_requested: 'Demande correction',
  correction_processed: 'Correction traitée',
};

export const HISTORY_TYPE_ICONS = {
  ds_assigned: BookOpen,
  td_assigned: FileText,
  dm_assigned: Sparkles,
  student_joined: UserPlus,
  invitation_configured: Users,
  correction_requested: Activity,
  correction_processed: CheckCircle2,
};
