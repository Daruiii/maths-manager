import { Head } from '@inertiajs/react';
import { useState } from 'react';
import { BookOpen, Images, PenLine } from 'lucide-react';
import AppLayout from '@/Layouts/AppLayout';
import PageHeader from '@/Components/Common/UI/PageHeader';
import FloatingActionDock from '@/Components/Common/UI/FloatingActionDock';
import { studentName } from '@/Components/Features/Corrections/correctionRequestLabels';
import CorrectionWorkbenchHeader from '@/Pages/Teacher/Corrections/Partials/CorrectionWorkbenchHeader';
import CorrectionSubjectPanel from '@/Pages/Teacher/Corrections/Partials/CorrectionSubjectPanel';
import CorrectionCopyPanel from '@/Pages/Teacher/Corrections/Partials/CorrectionCopyPanel';
import CorrectionGradeDrawer from '@/Pages/Teacher/Corrections/Partials/CorrectionGradeDrawer';
import type { CorrectionRequest } from '@/types/models';

export default function CorrectionShow({
  correctionRequest,
}: {
  correctionRequest: CorrectionRequest;
}) {
  const [isDrawerOpen, setIsDrawerOpen] = useState(false);
  const [isEditing, setIsEditing] = useState(false);
  const [activeTab, setActiveTab] = useState<'sujet' | 'copie'>('sujet');

  const isCorrected = correctionRequest.status === 'corrected';
  const name = studentName(correctionRequest);
  const title = `Correction — ${name}`;

  const tabs = [
    { key: 'sujet' as const, label: 'Sujet', icon: BookOpen },
    { key: 'copie' as const, label: 'Copie élève', icon: Images },
  ];

  return (
    <AppLayout>
      <Head title={title} />
      <div className="max-w-7xl mx-auto px-4 py-6 space-y-5">
        <PageHeader
          title={title}
          breadcrumbs={[
            { label: 'Mon Bureau', href: route('teacher.bureau.index') },
            { label: 'Corrections', href: route('teacher.corrections.index') },
            { label: name },
          ]}
        />

        <CorrectionWorkbenchHeader correctionRequest={correctionRequest} />

        {/* Mobile segmented control */}
        <div className="flex lg:hidden rounded-xl overflow-hidden border border-border-color bg-surface-color">
          {tabs.map(({ key, label, icon: Icon }) => (
            <button
              key={key}
              type="button"
              onClick={() => setActiveTab(key)}
              className={`flex flex-1 items-center justify-center gap-1.5 py-2.5 text-xs font-comfortaa-bold transition-colors ${
                activeTab === key
                  ? 'bg-secondary-color text-text-color'
                  : 'text-text-gray hover:text-text-color'
              }`}
            >
              <Icon size={13} />
              {label}
            </button>
          ))}
        </div>

        {/* 2-col desktop / tabs mobile */}
        <div className="grid gap-5 lg:grid-cols-[minmax(0,1.25fr)_minmax(320px,0.75fr)]">
          <div className={activeTab === 'sujet' ? 'block' : 'hidden lg:block'}>
            <CorrectionSubjectPanel correctionRequest={correctionRequest} />
          </div>
          <div
            className={`${activeTab === 'copie' ? 'block' : 'hidden lg:block'} lg:sticky lg:top-24 lg:max-h-[calc(100vh-7rem)] lg:self-start lg:overflow-y-auto`}
          >
            <CorrectionCopyPanel correctionRequest={correctionRequest} />
          </div>
        </div>

        <FloatingActionDock
          accent="teacher"
          label={isCorrected ? 'Voir / modifier' : 'Noter cette copie'}
          mobileLabel={isCorrected ? 'Voir' : 'Noter'}
          description={
            isCorrected
              ? 'Correction envoyée · visible par l’élève.'
              : 'Prêt à finaliser ? Ouvre le panneau de correction.'
          }
          icon={PenLine}
          onClick={() => setIsDrawerOpen(true)}
        />
      </div>

      <CorrectionGradeDrawer
        correctionRequest={correctionRequest}
        isOpen={isDrawerOpen}
        onClose={() => setIsDrawerOpen(false)}
        isEditing={isEditing}
        onEdit={() => setIsEditing(true)}
        onSaved={() => setIsEditing(false)}
        onCancelEdit={() => setIsEditing(false)}
      />
    </AppLayout>
  );
}
