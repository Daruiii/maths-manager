import type { Ds } from '@/types/models';

export interface DsStatusContentProps {
  ds: Ds;
  remainingFormatted: string;
  instructions: string;
  urgent: boolean;
  sessionToken: string | null;
  message: string;
  submitting: boolean;
  uploadError: string | null;
  onStart: () => void;
  onPause: () => void;
  onResume: () => void;
  onFinish: () => void;
  onSubmitCopy: (e: React.SyntheticEvent) => void;
  onTokenChange: (token: string | null) => void;
  onMessageChange: (msg: string) => void;
}
