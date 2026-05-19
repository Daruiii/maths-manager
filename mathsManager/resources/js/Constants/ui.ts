export const BUTTON_BASE_STYLES =
  'inline-flex items-center justify-center rounded-xl font-comfortaa-bold uppercase tracking-wider transition-all duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus-visible:ring-2 focus-visible:ring-tertiary-color/50 focus-visible:ring-offset-2 focus-visible:ring-offset-primary-color';

export const BUTTON_VARIANTS = {
  primary: 'bg-tertiary-color text-white hover:brightness-110 active:brightness-95 shadow-warm-xs',
  secondary:
    'bg-secondary-color text-text-gray border-2 border-border-color hover:bg-surface-color hover:text-text-color active:brightness-95',
  danger: 'bg-error-color text-white hover:brightness-110 active:brightness-95 shadow-warm-xs',
  success: 'bg-success-color text-white hover:brightness-110 active:brightness-95 shadow-warm-xs',
  ghost:
    'bg-transparent text-text-gray hover:text-text-color hover:bg-surface-color active:bg-surface-color/80',
  teacher: 'bg-teacher-color text-white hover:brightness-110 active:brightness-95 shadow-warm-xs',
  student: 'bg-student-color text-white hover:brightness-110 active:brightness-95 shadow-warm-xs',
};

export const BUTTON_SIZES = {
  sm: 'px-4 py-2 text-xs',
  md: 'px-6 py-2.5 text-sm',
  lg: 'px-8 py-3.5 text-base',
};
