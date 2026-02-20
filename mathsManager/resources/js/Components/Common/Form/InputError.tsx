import { HTMLAttributes } from 'react';

export default function InputError({
  message,
  className = '',
  ...props
}: HTMLAttributes<HTMLParagraphElement> & { message?: string }) {
  return message ? (
    <p {...props} className={'text-xs text-error-color font-comfortaa mt-1 ' + className}>
      {message}
    </p>
  ) : null;
}
