import { Timer } from 'lucide-react';
import { PrivateExerciseFormData } from '@/types/models';
import TextInput from '@/Components/Common/Form/TextInput';
import InputLabel from '@/Components/Common/Form/InputLabel';
import InputError from '@/Components/Common/Form/InputError';
import DifficultyPicker from '@/Components/Common/Form/DifficultyPicker';

interface Props {
  data: PrivateExerciseFormData;
  errors: Partial<Record<keyof PrivateExerciseFormData, string>>;
  set: <K extends keyof PrivateExerciseFormData>(key: K, value: PrivateExerciseFormData[K]) => void;
  typeToggle: React.ReactNode;
}

export default function PrivateExerciseSettingsColumn({ data, errors, set, typeToggle }: Props) {
  function handleTimeChange(rawValue: string) {
    const sanitized = rawValue.replace(/[^0-9]/g, '');
    set('time', sanitized);
  }

  function handleTimeBlur() {
    if (!data.time.trim()) return;

    const parsed = Number(data.time);
    if (Number.isNaN(parsed)) {
      set('time', '');
      return;
    }

    const clamped = Math.min(300, Math.max(1, parsed));
    set('time', String(clamped));
  }

  return (
    <>
      <div className="p-3 bg-teacher-color/5 border border-teacher-color/20 rounded-2xl space-y-3">
        <div>
          <InputLabel value="Nom" required />
          <TextInput
            value={data.name}
            onChange={(e) => set('name', e.target.value)}
            placeholder="Ex : Suites arithmétiques — TD1"
            className={`w-full px-3 py-2 text-sm ${errors.name ? 'border-error-color' : ''}`}
          />
          <InputError message={errors.name} />
        </div>
        <div>
          <InputLabel value="Type" required />
          {typeToggle}
        </div>
      </div>

      <div className="p-3 bg-surface-color border border-border-color rounded-2xl space-y-3">
        <div>
          <InputLabel value="Difficulté" />
          <DifficultyPicker value={data.difficulty} onChange={(v) => set('difficulty', v)} />
        </div>
        <div>
          <InputLabel>
            <span className="flex items-center gap-1">
              <Timer size={11} /> Durée (min)
            </span>
          </InputLabel>
          <TextInput
            type="text"
            inputMode="numeric"
            value={data.time}
            onChange={(e) => handleTimeChange(e.target.value)}
            onBlur={handleTimeBlur}
            placeholder="20"
            className="w-full px-3 py-2 text-sm"
          />
        </div>
      </div>
    </>
  );
}
