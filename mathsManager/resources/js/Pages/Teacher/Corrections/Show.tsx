import { Head } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import { studentName } from '@/Components/Features/Corrections/correctionRequestLabels';
import CorrectionRequestSummary from '@/Pages/Teacher/Corrections/Partials/CorrectionRequestSummary';
import CorrectionSentPanel from '@/Pages/Teacher/Corrections/Partials/CorrectionSentPanel';
import EditCorrectionForm from '@/Pages/Teacher/Corrections/Partials/EditCorrectionForm';
import SendCorrectionForm from '@/Pages/Teacher/Corrections/Partials/SendCorrectionForm';
import type { CorrectionRequest } from '@/types/models';

export default function CorrectionShow({
  correctionRequest,
}: {
  correctionRequest: CorrectionRequest;
}) {
  const [isEditing, setIsEditing] = useState(false);

  const isCorrected = correctionRequest.status === 'corrected';
  const title = `Correction — ${studentName(correctionRequest)}`;

  return (
    <AppLayout>
      <Head title={title} />
      <div className="max-w-6xl mx-auto px-4 py-6 space-y-6">
        <PageHeader
          title={title}
          breadcrumbs={[
            { label: 'Mon Bureau', href: route('teacher.bureau.index') },
            { label: 'Corrections', href: route('teacher.corrections.index') },
            { label: studentName(correctionRequest) },
          ]}
        />

        <div className="grid lg:grid-cols-[0.95fr_1.05fr] gap-5">
          <CorrectionRequestSummary correctionRequest={correctionRequest} />

          <div className="space-y-4">
            {isCorrected ? (
              isEditing ? (
                <EditCorrectionForm
                  correctionRequest={correctionRequest}
                  onCancel={() => setIsEditing(false)}
                  onSaved={() => setIsEditing(false)}
                />
              ) : (
                <CorrectionSentPanel
                  correctionRequest={correctionRequest}
                  onEdit={() => setIsEditing(true)}
                />
              )
            ) : (
              <SendCorrectionForm correctionRequest={correctionRequest} />
            )}
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
