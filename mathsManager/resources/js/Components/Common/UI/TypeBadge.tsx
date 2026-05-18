import { CONTENT_TYPE_STYLES } from '@/Constants/contentTypes';

type ContentType = 'ds' | 'dm' | 'td';

interface Props {
  type: ContentType;
  size?: 'sm' | 'md';
}

export default function TypeBadge({ type, size = 'sm' }: Props) {
  const styles = CONTENT_TYPE_STYLES[type];
  if (size === 'md') {
    return (
      <span
        className={`w-8 h-8 rounded-xl flex items-center justify-center text-xs font-comfortaa-bold uppercase shrink-0 ${styles.badge}`}
      >
        {type.toUpperCase()}
      </span>
    );
  }
  return (
    <span
      className={`text-[10px] font-comfortaa-bold px-2 py-0.5 rounded-full uppercase shrink-0 ${styles.badge}`}
    >
      {type.toUpperCase()}
    </span>
  );
}
