import { TD_DEFAULT_TITLE, TD_DEFAULT_LEVEL, TD_DEFAULT_INSTRUCTIONS } from '@/Constants/td';
import { useBuilderDraft } from '@/Hooks/useBuilderDraft';
import { TemplatePayload } from '@/types/models';

export { makeItemUid } from '@/Hooks/useBuilderDraft';

const TD_DEFAULTS = {
  title: TD_DEFAULT_TITLE,
  level: TD_DEFAULT_LEVEL,
  instructions: TD_DEFAULT_INSTRUCTIONS,
};

export function useTDBuilderDraft(initialTemplate?: TemplatePayload) {
  const {
    title: tdTitle,
    setTitle: setTdTitle,
    level: tdLevel,
    setLevel: setTdLevel,
    instructions: tdInstructions,
    setInstructions: setTdInstructions,
    ...rest
  } = useBuilderDraft('td', TD_DEFAULTS, initialTemplate);

  return { tdTitle, setTdTitle, tdLevel, setTdLevel, tdInstructions, setTdInstructions, ...rest };
}
