import { route } from 'ziggy-js';
import { PenLine, BookOpen, FileEdit, Bell, ShieldCheck, Sparkles } from 'lucide-react';
import type { QuickAction } from '@/types';

interface UseQuickActionsOptions {
  correctionCount?: number;
  whitelistCount?: number;
}

export function useQuickActions({
  correctionCount = 0,
  whitelistCount = 0,
}: UseQuickActionsOptions = {}): QuickAction[] {
  return [
    {
      id: 'create-ds',
      label: 'Créer un DS',
      icon: PenLine,
      href: route('teacher.ds.create'),
    },
    {
      id: 'create-td',
      label: 'Créer un TD',
      icon: BookOpen,
      href: route('teacher.td.create'),
    },
    {
      id: 'create-dm',
      label: 'Créer un DM',
      icon: Sparkles,
      disabled: true,
      comingSoon: true,
      separatorBefore: true,
    },
    {
      id: 'create-exercise',
      label: 'Exercice privé',
      icon: FileEdit,
      disabled: true,
      comingSoon: true,
    },
    {
      id: 'corrections',
      label: 'Corrections en attente',
      icon: Bell,
      badge: correctionCount || undefined,
      disabled: true,
      comingSoon: true,
    },
    {
      id: 'whitelist',
      label: 'Demandes whitelist',
      icon: ShieldCheck,
      badge: whitelistCount || undefined,
      disabled: true,
      comingSoon: true,
    },
  ];
}
