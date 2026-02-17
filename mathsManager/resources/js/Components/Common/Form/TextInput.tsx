import { forwardRef, useEffect, useImperativeHandle, useRef, InputHTMLAttributes } from 'react';

/**
 * Text input field with auto-focus support and consistent styling
 *
 * @component
 * @example
 * ```tsx
 * <TextInput
 *   type="email"
 *   name="email"
 *   value={email}
 *   isFocused={true}
 *   onChange={(e) => setEmail(e.target.value)}
 * />
 * ```
 */
export default forwardRef(function TextInput(
  {
    type = 'text',
    className = '',
    isFocused = false,
    ...props
  }: InputHTMLAttributes<HTMLInputElement> & { isFocused?: boolean },
  ref
) {
  const localRef = useRef<HTMLInputElement>(null);

  useImperativeHandle(ref, () => ({
    focus: () => localRef.current?.focus(),
  }));

  useEffect(() => {
    if (isFocused) {
      localRef.current?.focus();
    }
  }, [isFocused]);

  return (
    <input
      {...props}
      type={type}
      className={
        'border-2 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white focus:border-tertiary-color focus:ring-0 rounded-2xl shadow-sm transition-all duration-200 font-comfortaa placeholder-gray-400 ' +
        className
      }
      ref={localRef}
    />
  );
});
