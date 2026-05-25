import type { LucideIcon } from 'lucide-react';

interface Props {
  icon: LucideIcon;
  title: string;
  description: string;
}

export default function StudentResourceFeatureCard({ icon: Icon, title, description }: Props) {
  return (
    <article className="relative overflow-hidden rounded-2xl bg-secondary-color border border-border-color p-4 opacity-80">
      <div className="flex items-start gap-3">
        <div className="p-2 rounded-xl bg-student-color/10 text-student-color shrink-0">
          <Icon size={16} />
        </div>
        <div className="min-w-0">
          <div className="flex items-center gap-2 flex-wrap">
            <p className="text-sm font-comfortaa-bold text-text-color">{title}</p>
            <span className="mm-badge mm-badge-neutral text-[10px]">Bientôt</span>
          </div>
          <p className="mt-1 text-xs text-text-gray leading-relaxed">{description}</p>
        </div>
      </div>
    </article>
  );
}
