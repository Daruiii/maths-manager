export const BUTTON_BASE_STYLES =
  'inline-flex items-center justify-center rounded-xl font-comfortaa-bold uppercase tracking-widest transition-all duration-75 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed transform active:translate-y-1 active:shadow-none focus:outline-none';

export const BUTTON_VARIANTS = {
  primary:
    'bg-tertiary-color text-white shadow-[0_4px_0_0_rgba(0,0,0,0.2)] hover:brightness-110 active:brightness-100',
  secondary:
    'bg-secondary-color text-text-gray border-2 border-border-color shadow-[0_4px_0_0_rgba(0,0,0,0.1)] hover:bg-surface-color',
  danger:
    'bg-error-color text-white shadow-[0_4px_0_0_rgba(0,0,0,0.2)] hover:brightness-110 active:brightness-100',
  ghost:
    'bg-transparent text-text-gray hover:text-text-color hover:bg-surface-color shadow-none active:translate-y-0',
};

export const BUTTON_SIZES = {
  sm: 'px-4 py-2 text-xs',
  md: 'px-6 py-3 text-sm',
  lg: 'px-8 py-4 text-base',
};
