export function privateUrl(path: string): string {
  const [context, identifier, ...rest] = path.split('/');
  return route('private.file.serve', { context, identifier, filename: rest.join('/') });
}

export const UPLOAD_ACCENT = {
  student: { text: 'text-student-color', hoverBorder: 'hover:border-student-color/40' },
  teacher: { text: 'text-teacher-color', hoverBorder: 'hover:border-teacher-color/40' },
} satisfies Record<'student' | 'teacher', { text: string; hoverBorder: string }>;
