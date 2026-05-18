import { CONTENT_TYPE_STYLES } from '@/Constants/contentTypes';

type ContentType = 'ds' | 'dm' | 'td';

export default function TypeBadge({ type }: { type: ContentType }) {
  const styles = CONTENT_TYPE_STYLES[type];
  return (
    <span
      className={`text-[10px] font-comfortaa-bold px-2 py-0.5 rounded-full uppercase shrink-0 ${styles.badge}`}
    >
      {type.toUpperCase()}
    </span>
  );
}
