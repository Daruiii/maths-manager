const TYPE_LABEL: Record<string, string> = { ds: 'DS', dm: 'DM', td: 'TD' };
const TYPE_COLOR: Record<string, string> = {
  ds: 'text-ds-color',
  dm: 'text-dm-color',
  td: 'text-td-color',
};
const TYPE_BG: Record<string, string> = {
  ds: 'bg-ds-color/10',
  dm: 'bg-dm-color/10',
  td: 'bg-td-color/10',
};

export default function StudentTypeBadge({ type }: { type: string }) {
  return (
    <span
      className={`text-[9px] font-comfortaa-bold uppercase px-1.5 py-0.5 rounded shrink-0 ${TYPE_COLOR[type] ?? 'text-text-gray'} ${TYPE_BG[type] ?? ''}`}
    >
      {TYPE_LABEL[type] ?? type.toUpperCase()}
    </span>
  );
}
