export const BUTTON_BASE_STYLES =
  'inline-flex items-center justify-center rounded-xl font-comfortaa-bold uppercase tracking-widest transition-all duration-75 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none';

export const BUTTON_VARIANTS = {
  primary: 'bg-tertiary-color text-white hover:brightness-110 active:brightness-95',
  secondary:
    'bg-secondary-color text-text-gray border-2 border-border-color hover:bg-surface-color active:brightness-95',
  danger: 'bg-error-color text-white hover:brightness-110 active:brightness-95',
  success: 'bg-success-color text-white hover:brightness-110 active:brightness-95',
  ghost: 'bg-transparent text-text-gray hover:text-text-color hover:bg-surface-color',
  teacher: 'bg-teacher-color text-white hover:brightness-110 active:brightness-95',
  student: 'bg-student-color text-white hover:brightness-110 active:brightness-95',
};

export const BUTTON_SIZES = {
  sm: 'px-4 py-2 text-xs',
  md: 'px-6 py-3 text-sm',
  lg: 'px-8 py-4 text-base',
};
