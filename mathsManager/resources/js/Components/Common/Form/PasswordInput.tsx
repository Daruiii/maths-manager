import {
  forwardRef,
  useEffect,
  useImperativeHandle,
  useRef,
  useState,
  InputHTMLAttributes,
} from 'react';
import { Eye, EyeOff } from 'lucide-react';

/**
 * Password input field with an integrated toggle to show/hide the password.
 * Maintains consistent styling with TextInput.
 */
export default forwardRef(function PasswordInput(
  {
    className = '',
    isFocused = false,
    ...props
  }: InputHTMLAttributes<HTMLInputElement> & { isFocused?: boolean },
  ref
) {
  const localRef = useRef<HTMLInputElement>(null);
  const [showPassword, setShowPassword] = useState(false);

  useImperativeHandle(ref, () => ({
    focus: () => localRef.current?.focus(),
  }));

  useEffect(() => {
    if (isFocused) {
      localRef.current?.focus();
    }
  }, [isFocused]);

  return (
    <div className={`relative ${className}`}>
      <input
        {...props}
        type={showPassword ? 'text' : 'password'}
        className="border-2 w-full border-border-color bg-surface-color text-text-color focus:border-tertiary-color focus:ring-0 rounded-2xl shadow-sm transition-all duration-200 font-comfortaa placeholder-text-gray pr-10"
        ref={localRef}
      />
      <button
        type="button"
        onClick={() => setShowPassword(!showPassword)}
        className="absolute inset-y-0 right-0 flex items-center pr-4 text-text-gray hover:text-tertiary-color transition-colors"
        tabIndex={-1}
      >
        {showPassword ? <EyeOff size={20} /> : <Eye size={20} />}
      </button>
    </div>
  );
});
