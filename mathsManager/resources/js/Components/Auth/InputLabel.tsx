import { LabelHTMLAttributes } from 'react';

export default function InputLabel({
  value,
  className = '',
  children,
  ...props
}: LabelHTMLAttributes<HTMLLabelElement> & { value?: string }) {
  return (
    <label
      {...props}
      className={
        `block font-comfortaa-bold text-sm text-text-gray uppercase tracking-wider ` + className
      }
    >
      {value ? value : children}
    </label>
  );
}
