import { DS_DEFAULT_TITLE, DS_DEFAULT_LEVEL, DS_DEFAULT_INSTRUCTIONS } from '@/Constants/ds';
import { useBuilderDraft } from '@/Hooks/useBuilderDraft';
import { TemplatePayload } from '@/types/models';

export { makeItemUid } from '@/Hooks/useBuilderDraft';

const DS_DEFAULTS = {
  title: DS_DEFAULT_TITLE,
  level: DS_DEFAULT_LEVEL,
  instructions: DS_DEFAULT_INSTRUCTIONS,
};

export function useDSBuilderDraft(initialTemplate?: TemplatePayload) {
  const {
    title: dsTitle,
    setTitle: setDsTitle,
    level: dsLevel,
    setLevel: setDsLevel,
    instructions: dsInstructions,
    setInstructions: setDsInstructions,
    ...rest
  } = useBuilderDraft('ds', DS_DEFAULTS, initialTemplate);

  return { dsTitle, setDsTitle, dsLevel, setDsLevel, dsInstructions, setDsInstructions, ...rest };
}
