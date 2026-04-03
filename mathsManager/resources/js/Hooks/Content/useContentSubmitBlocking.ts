import { useState } from 'react';
import { PrivateExerciseFormData } from '@/types/models';
import {
  collectPrivateExerciseBlockingIssues,
  PrivateExerciseBlockingIssue,
} from '@/Utils/privateExerciseValidation';

interface Params {
  data: PrivateExerciseFormData;
  errors: Partial<Record<keyof PrivateExerciseFormData, string>>;
  images: Record<string, string>;
}

export function useContentSubmitBlocking() {
  const [blockingIssues, setBlockingIssues] = useState<PrivateExerciseBlockingIssue[]>([]);
  const [isSubmitBlockedModalOpen, setIsSubmitBlockedModalOpen] = useState(false);

  function closeSubmitBlockedModal() {
    setIsSubmitBlockedModalOpen(false);
  }

  function guardBeforeSubmit({ data, errors, images }: Params): boolean {
    const issues = collectPrivateExerciseBlockingIssues({
      data,
      errors,
      images,
    });

    if (issues.length > 0) {
      setBlockingIssues(issues);
      setIsSubmitBlockedModalOpen(true);
      return false;
    }

    return true;
  }

  return {
    blockingIssues,
    isSubmitBlockedModalOpen,
    closeSubmitBlockedModal,
    guardBeforeSubmit,
  };
}
