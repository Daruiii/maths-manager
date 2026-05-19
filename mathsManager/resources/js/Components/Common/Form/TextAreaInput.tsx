import { forwardRef, TextareaHTMLAttributes, useEffect, useImperativeHandle, useRef } from 'react';

export default forwardRef(function TextAreaInput(
  {
    className = '',
    isFocused = false,
    ...props
  }: TextareaHTMLAttributes<HTMLTextAreaElement> & { isFocused?: boolean },
  ref
) {
  const localRef = useRef<HTMLTextAreaElement>(null);

  useImperativeHandle(ref, () => ({
    focus: () => localRef.current?.focus(),
  }));

  useEffect(() => {
    if (isFocused) {
      localRef.current?.focus();
    }
  }, [isFocused]);

  return (
    <textarea
      {...props}
      className={
        'border-2 border-border-color bg-surface-color text-text-color focus:border-tertiary-color focus:ring-0 rounded-2xl shadow-sm transition-all duration-200 font-comfortaa placeholder-text-gray ' +
        className
      }
      ref={localRef}
    />
  );
});
