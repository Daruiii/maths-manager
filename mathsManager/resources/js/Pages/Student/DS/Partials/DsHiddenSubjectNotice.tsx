import { EyeOff } from 'lucide-react';
import TheoremCard from '@/Components/Common/UI/TheoremCard';

interface Props {
  children: React.ReactNode;
}

export default function DsHiddenSubjectNotice({ children }: Props) {
  return (
    <TheoremCard accent="student" dotted>
      <div className="flex items-start gap-2">
        <EyeOff size={15} className="text-text-gray mt-0.5 shrink-0" />
        <p className="text-sm text-text-gray leading-relaxed">{children}</p>
      </div>
    </TheoremCard>
  );
}
