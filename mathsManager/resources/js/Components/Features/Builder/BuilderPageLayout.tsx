import { ReactNode } from 'react';
import { BookOpen, Eye, ListOrdered } from 'lucide-react';
import PageHeader from '@/Components/Common/UI/PageHeader';
import BuilderActions from '@/Components/Features/Builder/BuilderActions';
import { MobileTab } from '@/Hooks/useBuilderHandlers';

const MOBILE_TABS = [
  { id: 'picker' as const, label: 'Exercices', Icon: BookOpen },
  { id: 'preview' as const, label: 'Aperçu', Icon: Eye },
  { id: 'sommaire' as const, label: 'Sommaire', Icon: ListOrdered },
];

interface Props {
  title: string;
  subtitle: string;
  breadcrumbLabel: string;
  entityLabel: string;
  itemCount: number;
  onReset: () => void;
  mobileTab: MobileTab;
  onTabChange: (tab: MobileTab) => void;
  pickerSlot: ReactNode;
  contentSlot: ReactNode;
  previewSlot: ReactNode;
}

export default function BuilderPageLayout({
  title,
  subtitle,
  breadcrumbLabel,
  entityLabel,
  itemCount,
  onReset,
  mobileTab,
  onTabChange,
  pickerSlot,
  contentSlot,
  previewSlot,
}: Props) {
  return (
    <div className="flex flex-col h-[calc(100vh-72px)]">
      <div className="flex-shrink-0 px-4 pt-4 pb-2 max-w-screen-xl mx-auto w-full">
        <PageHeader
          title={title}
          subtitle={subtitle}
          breadcrumbs={[{ label: breadcrumbLabel }]}
          action={
            <BuilderActions itemCount={itemCount} onReset={onReset} entityLabel={entityLabel} />
          }
        />
      </div>

      <div className="lg:hidden flex-shrink-0 flex border-b border-border-color mx-4">
        {MOBILE_TABS.map(({ id, label, Icon }) => (
          <button
            key={id}
            type="button"
            onClick={() => onTabChange(id)}
            className={`flex-1 py-2.5 text-sm font-medium flex items-center justify-center gap-1.5 border-b-2 transition-colors ${
              mobileTab === id
                ? 'border-teacher-color text-teacher-color'
                : 'border-transparent text-text-gray hover:text-text-color'
            }`}
          >
            <Icon size={14} />
            {label}
            {id !== 'picker' && itemCount > 0 && (
              <span className="px-1.5 py-0.5 rounded-full bg-teacher-color text-white text-xxs font-bold">
                {itemCount}
              </span>
            )}
          </button>
        ))}
      </div>

      <div className="flex-1 max-w-screen-xl mx-auto w-full flex overflow-hidden">
        <div
          className={`w-full lg:w-[30%] border-r border-border-color flex flex-col overflow-hidden ${
            mobileTab !== 'picker' ? 'hidden lg:flex' : 'flex'
          }`}
        >
          {pickerSlot}
        </div>
        <div
          className={`w-full lg:w-[54%] border-r border-border-color flex flex-col overflow-hidden ${
            mobileTab !== 'preview' ? 'hidden lg:flex' : 'flex'
          }`}
        >
          {contentSlot}
        </div>
        <div
          className={`w-full lg:w-[16%] flex-col overflow-hidden ${
            mobileTab !== 'sommaire' ? 'hidden lg:flex' : 'flex'
          }`}
        >
          {previewSlot}
        </div>
      </div>
    </div>
  );
}
