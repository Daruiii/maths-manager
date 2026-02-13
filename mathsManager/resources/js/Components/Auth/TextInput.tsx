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
        'border-gray-300 focus:border-admin-color focus:ring-admin-color rounded-xl shadow-sm transition-all duration-200 font-comfortaa ' +
        className
      }
      ref={localRef}
    />
  );
});
