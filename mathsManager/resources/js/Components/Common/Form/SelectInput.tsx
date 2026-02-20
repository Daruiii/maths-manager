import { forwardRef, SelectHTMLAttributes, useImperativeHandle, useRef } from 'react';

export default forwardRef(function SelectInput(
  { className = '', children, ...props }: SelectHTMLAttributes<HTMLSelectElement>,
  ref
) {
  const localRef = useRef<HTMLSelectElement>(null);

  useImperativeHandle(ref, () => ({
    focus: () => localRef.current?.focus(),
  }));

  return (
    <select
      {...props}
      className={
        'border-2 border-border-color bg-surface-color text-text-color focus:border-tertiary-color focus:ring-0 rounded-2xl shadow-sm transition-all duration-200 font-comfortaa ' +
        className
      }
      ref={localRef}
    >
      {children}
    </select>
  );
});
