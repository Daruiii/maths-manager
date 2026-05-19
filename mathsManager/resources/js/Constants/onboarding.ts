export const TEACHING_LEVELS = [
  { value: 'college', label: 'Collège' },
  { value: 'lycee', label: 'Lycée' },
  { value: 'prepa', label: 'Prépa' },
  { value: 'superieur', label: 'Supérieur' },
  { value: 'autre', label: 'Autre' },
];

export const DIPLOMAS = [
  { value: 'licence', label: 'Licence' },
  { value: 'master', label: 'Master' },
  { value: 'agregation', label: 'Agrégation' },
  { value: 'capes', label: 'CAPES' },
  { value: 'doctorat', label: 'Doctorat' },
  { value: 'autre', label: 'Autre' },
];

export const ROLE_THEME_CLASSES = {
  student: {
    gradientBgHover: 'from-student-color/20 bg-gradient-to-br',
    borderHover: 'group-hover:border-student-color/50',
    iconBg: 'bg-student-color/10',
    iconText: 'text-student-color',
    rotation: 'rotate-3 group-hover:-rotate-3',
    checkBg: 'bg-student-color/20',
    checkText: 'text-student-color',
    buttonBg: '!bg-student-color text-white',
    buttonHover: 'hover:!bg-student-color/90',
    buttonShadow:
      '!shadow-[0_4px_0_0_rgb(var(--student-color)_/_0.3)] hover:!shadow-[0_2px_0_0_rgb(var(--student-color)_/_0.3)]',
  },
  teacher: {
    gradientBgHover: 'from-teacher-color/20 bg-gradient-to-bl',
    borderHover: 'group-hover:border-teacher-color/50',
    iconBg: 'bg-teacher-color/10',
    iconText: 'text-teacher-color',
    rotation: '-rotate-3 group-hover:rotate-3',
    checkBg: 'bg-teacher-color/20',
    checkText: 'text-teacher-color',
    buttonBg: '!bg-teacher-color text-white',
    buttonHover: 'hover:!bg-teacher-color/90',
    buttonShadow:
      '!shadow-[0_4px_0_0_rgb(var(--teacher-color)_/_0.3)] hover:!shadow-[0_2px_0_0_rgb(var(--teacher-color)_/_0.3)]',
  },
};

export const ROLE_FEATURES = {
  student: [
    'Entraînements illimités sur mesure',
    'Suivi de progression ultra détaillé',
    'Révision des devoirs et corrections',
    "Système d'expérience et récompenses",
  ],
  teacher: [
    'Gestion multiclasse ultra-simplifiée',
    'Création de devoirs sur mesure',
    'Statistiques et suivi intelligent',
    'Export PDF des fiches en un clic',
  ],
};

export const TEACHER_ONBOARDING_STEPS = [
  'Remplissez ce formulaire',
  'Notre équipe valide votre accès',
  'Profitez de tous les outils pour enseigner',
];
