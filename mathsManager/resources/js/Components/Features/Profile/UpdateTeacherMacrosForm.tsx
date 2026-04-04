import { useState } from 'react';
import axios from 'axios';
import { router, usePage } from '@inertiajs/react';
import { Plus, Trash2 } from 'lucide-react';
import type { PageProps } from '@/types';
import InputLabel from '@/Components/Common/Form/InputLabel';
import TextInput from '@/Components/Common/Form/TextInput';
import InputError from '@/Components/Common/Form/InputError';
import Button from '@/Components/Common/UI/Button';

interface MacroRow {
  key: string;
  definition: string;
}

interface Props {
  onSuccess?: () => void;
}

export default function UpdateTeacherMacrosForm({ onSuccess }: Props) {
  const { auth } = usePage<PageProps>().props;
  const initialMacros = auth.user?.latex_macros ?? null;

  const [rows, setRows] = useState<MacroRow[]>(() => {
    if (!initialMacros) return [];
    return Object.entries(initialMacros).map(([key, definition]) => ({ key, definition }));
  });
  const [processing, setProcessing] = useState(false);
  const [rowErrors, setRowErrors] = useState<Record<string, string>>({});

  function addRow() {
    setRows((prev) => [...prev, { key: '', definition: '' }]);
  }

  function removeRow(index: number) {
    setRows((prev) => prev.filter((_, i) => i !== index));
    setRowErrors((prev) => {
      const next = { ...prev };
      delete next[`row_${index}_key`];
      return next;
    });
  }

  function updateRow(index: number, field: 'key' | 'definition', value: string) {
    setRows((prev) => prev.map((row, i) => (i === index ? { ...row, [field]: value } : row)));
  }

  function handleSubmit() {
    const localErrors: Record<string, string> = {};
    const macros: Record<string, string> = {};

    rows.forEach((row, i) => {
      if (!row.key && !row.definition) return;
      if (!row.key.match(/^\\[a-zA-Z]+$/)) {
        localErrors[`row_${i}_key`] = 'Format invalide. Ex\u00a0: \\maCommande';
        return;
      }
      macros[row.key] = row.definition;
    });

    if (Object.keys(localErrors).length > 0) {
      setRowErrors(localErrors);
      return;
    }

    setRowErrors({});
    setProcessing(true);

    if (onSuccess) {
      // Contexte modale : axios pour éviter la navigation Inertia vers profile.edit
      axios
        .patch(route('profile.macros.update'), { macros })
        .then(() => {
          router.reload({ only: ['auth'] });
          onSuccess();
        })
        .finally(() => setProcessing(false));
    } else {
      router.patch(
        route('profile.macros.update'),
        { macros },
        {
          preserveScroll: true,
          onFinish: () => setProcessing(false),
        }
      );
    }
  }

  return (
    <section>
      <p className="text-sm text-text-gray mb-6">
        Définissez vos macros LaTeX personnelles. Elles seront disponibles uniquement dans vos
        exercices privés.
      </p>

      <div className="space-y-3">
        {rows.length > 0 && (
          <div className="flex gap-2 items-center mb-1">
            <div className="w-44 shrink-0">
              <InputLabel value="Commande" className="mb-0" />
            </div>
            <div className="flex-1">
              <InputLabel value="Définition" className="mb-0" />
            </div>
            <div className="w-9 shrink-0" />
          </div>
        )}

        {rows.length === 0 && (
          <p className="text-sm text-text-gray italic py-2">Aucune macro définie.</p>
        )}

        {rows.map((row, i) => (
          <div key={i} className="flex gap-2 items-start">
            <div className="w-44 shrink-0">
              <TextInput
                value={row.key}
                onChange={(e) => updateRow(i, 'key', e.target.value)}
                placeholder="ex: \monMacro"
                className="w-full font-mono text-sm"
              />
              <InputError message={rowErrors[`row_${i}_key`]} />
            </div>
            <div className="flex-1">
              <TextInput
                value={row.definition}
                onChange={(e) => updateRow(i, 'definition', e.target.value)}
                placeholder="ex: \mathbb{R} ou \dfrac{#1}{#2}"
                className="w-full font-mono text-sm"
              />
            </div>
            <div>
              <Button
                type="button"
                variant="ghost"
                size="sm"
                icon={Trash2}
                onClick={() => removeRow(i)}
                className="text-error-color hover:bg-error-color/10"
                aria-label="Supprimer cette macro"
              />
            </div>
          </div>
        ))}

        <div className="flex items-center justify-between pt-2">
          <Button type="button" variant="ghost" size="sm" icon={Plus} onClick={addRow}>
            Ajouter une macro
          </Button>
          <Button type="button" variant="teacher" isLoading={processing} onClick={handleSubmit}>
            Enregistrer
          </Button>
        </div>
      </div>
    </section>
  );
}
