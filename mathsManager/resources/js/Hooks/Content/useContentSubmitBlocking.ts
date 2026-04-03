import { useState } from 'react';
import { PrivateExerciseFormData } from '@/types/models';
import { collectContentBlockingIssues, ContentBlockingIssue } from '@/Utils/contentValidation';

interface Params {
  data: PrivateExerciseFormData;
  errors: Partial<Record<keyof PrivateExerciseFormData, string>>;
  images: Record<string, string>;
  macros: Record<string, string>;
}

export function useContentSubmitBlocking() {
  const [blockingIssues, setBlockingIssues] = useState<ContentBlockingIssue[]>([]);
  const [isSubmitBlockedModalOpen, setIsSubmitBlockedModalOpen] = useState(false);

  function closeSubmitBlockedModal() {
    setIsSubmitBlockedModalOpen(false);
  }

  function guardBeforeSubmit({ data, errors, images, macros }: Params): boolean {
    const issues = collectContentBlockingIssues({
      data,
      errors,
      images,
      macros,
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
