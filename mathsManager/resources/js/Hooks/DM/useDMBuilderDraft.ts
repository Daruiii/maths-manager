import { DM_DEFAULT_TITLE, DM_DEFAULT_LEVEL, DM_DEFAULT_INSTRUCTIONS } from '@/Constants/dm';
import { useBuilderDraft } from '@/Hooks/useBuilderDraft';

export { makeItemUid } from '@/Hooks/useBuilderDraft';

const DM_DEFAULTS = {
  title: DM_DEFAULT_TITLE,
  level: DM_DEFAULT_LEVEL,
  instructions: DM_DEFAULT_INSTRUCTIONS,
};

export function useDMBuilderDraft() {
  const {
    title: dmTitle,
    setTitle: setDmTitle,
    level: dmLevel,
    setLevel: setDmLevel,
    instructions: dmInstructions,
    setInstructions: setDmInstructions,
    ...rest
  } = useBuilderDraft('dm', DM_DEFAULTS);

  return { dmTitle, setDmTitle, dmLevel, setDmLevel, dmInstructions, setDmInstructions, ...rest };
}
