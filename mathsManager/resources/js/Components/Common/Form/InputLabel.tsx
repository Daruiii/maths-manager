import { LabelHTMLAttributes } from 'react';

export default function InputLabel({
  value,
  required,
  className = '',
  children,
  ...props
}: LabelHTMLAttributes<HTMLLabelElement> & { value?: string; required?: boolean }) {
  return (
    <label
      {...props}
      className={`block font-comfortaa-bold text-sm text-text-color mb-2 ` + className}
    >
      {value ?? children}
      {required && <span className="ml-1 text-error-color">*</span>}
    </label>
  );
}
