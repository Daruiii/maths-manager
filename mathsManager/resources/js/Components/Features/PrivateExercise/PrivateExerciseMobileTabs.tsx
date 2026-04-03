import { FileCode2, Layers, SlidersHorizontal } from 'lucide-react';

export type PrivateExerciseMobileTab = 'settings' | 'latex' | 'meta';

interface Props {
  mobileTab: PrivateExerciseMobileTab;
  onChange: (tab: PrivateExerciseMobileTab) => void;
}

const MOBILE_TABS: Array<{
  key: PrivateExerciseMobileTab;
  label: string;
  icon: typeof SlidersHorizontal;
}> = [
  { key: 'settings', label: 'Paramètres', icon: SlidersHorizontal },
  { key: 'latex', label: 'LaTeX', icon: FileCode2 },
  { key: 'meta', label: 'Meta', icon: Layers },
];

export default function PrivateExerciseMobileTabs({ mobileTab, onChange }: Props) {
  return (
    <div className="flex items-center gap-2 rounded-xl border border-border-color bg-surface-color p-1 xl:hidden">
      {MOBILE_TABS.map(({ key, label, icon: Icon }) => (
        <button
          key={key}
          type="button"
          onClick={() => onChange(key)}
          className={`inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg px-2 py-1.5 text-xxs transition-colors ${
            mobileTab === key
              ? 'bg-teacher-color/15 text-teacher-color font-comfortaa-bold'
              : 'text-text-gray hover:text-text-color'
          }`}
        >
          <Icon size={12} /> {label}
        </button>
      ))}
    </div>
  );
}
