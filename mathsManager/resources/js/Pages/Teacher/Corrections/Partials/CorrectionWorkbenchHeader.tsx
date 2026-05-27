import { CheckCircle, Clock3 } from 'lucide-react';
import TypeBadge from '@/Components/Common/UI/TypeBadge';
import UserAvatar from '@/Components/Common/Avatar/UserAvatar';
import {
  assignmentTitle,
  assignmentType,
  studentName,
} from '@/Components/Features/Corrections/correctionRequestLabels';
import type { CorrectionRequest } from '@/types/models';

function formatDate(date: string): string {
  return new Intl.DateTimeFormat('fr-FR', { day: 'numeric', month: 'long' }).format(new Date(date));
}

export default function CorrectionWorkbenchHeader({
  correctionRequest,
}: {
  correctionRequest: CorrectionRequest;
}) {
  const type = assignmentType(correctionRequest).toLowerCase() as 'ds' | 'dm';
  const isCorrected = correctionRequest.status === 'corrected';
  const name = studentName(correctionRequest);

  return (
    <div className="mm-card mm-card-style-raised px-5 py-4 sm:px-6">
      <div className="flex items-start gap-4">
        <UserAvatar
          user={correctionRequest.user}
          alt={name}
          size="lg"
          className="border-teacher-color/25 shadow-warm-xs"
        />
        <div className="min-w-0 flex-1">
          <div className="flex flex-wrap items-start justify-between gap-2">
            <div className="min-w-0">
              <h1 className="text-xl font-comfortaa-bold text-text-color sm:text-2xl">{name}</h1>
              <div className="mt-1 flex flex-wrap items-center gap-x-2 gap-y-1">
                <TypeBadge type={type} size="sm" />
                <span className="text-sm text-text-gray">{assignmentTitle(correctionRequest)}</span>
                <span className="text-text-gray/40">·</span>
                <span className="text-xs text-text-gray">
                  Reçue le {formatDate(correctionRequest.created_at)}
                </span>
              </div>
            </div>
            <span
              className={`mm-badge shrink-0 ${isCorrected ? 'mm-badge-success' : 'mm-badge-warning'}`}
            >
              {isCorrected ? <CheckCircle size={11} /> : <Clock3 size={11} />}
              {isCorrected ? 'Corrigé' : 'À corriger'}
            </span>
          </div>
        </div>
      </div>
    </div>
  );
}
