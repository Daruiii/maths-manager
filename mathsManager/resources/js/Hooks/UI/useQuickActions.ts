import { route } from 'ziggy-js';
import type { QuickAction } from '@/types';
import { CONTENT_ITEM_META, CONTENT_TYPE_META } from '@/Constants/contentTypes';

export function useQuickActions(): QuickAction[] {
  return [
    {
      id: 'create-ds',
      label: CONTENT_TYPE_META.ds.createLabel,
      icon: CONTENT_TYPE_META.ds.icon,
      href: route('teacher.ds.create'),
    },
    {
      id: 'create-td',
      label: CONTENT_TYPE_META.td.createLabel,
      icon: CONTENT_TYPE_META.td.icon,
      href: route('teacher.td.create'),
    },
    {
      id: 'create-dm',
      label: CONTENT_TYPE_META.dm.createLabel,
      icon: CONTENT_TYPE_META.dm.icon,
      href: route('teacher.dm.create'),
      separatorBefore: false,
    },
    {
      id: 'create-exercise',
      label: 'Créer un exercice',
      icon: CONTENT_ITEM_META.private.icon,
      href: route('teacher.exercices.create'),
    },
  ];
}
