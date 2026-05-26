import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';
import StudentAverageBlock from '@/Pages/Home/Partials/Student/StudentAverageBlock';
import StudentFeedbackList from '@/Pages/Home/Partials/Student/StudentFeedbackList';
import StudentNextDueRow from '@/Pages/Home/Partials/Student/StudentNextDueRow';
import type { NextDue } from '@/Pages/Home/Partials/Student/studentSidebarTypes';
import type { HomeFeedbackItem, HomeFeedbackSummary } from '@/types';

interface Props {
  averageGrade?: number | null;
  nextDue?: NextDue | null;
  feedbackSummary?: HomeFeedbackSummary;
  recentFeedbackItems?: HomeFeedbackItem[];
}

export default function StudentSidebar({
  averageGrade,
  nextDue,
  feedbackSummary,
  recentFeedbackItems,
}: Props) {
  return (
    <aside className="lg:sticky lg:top-6">
      <div className="bg-secondary-color border border-border-color rounded-2xl overflow-hidden">
        <StudentAverageBlock averageGrade={averageGrade} />

        {nextDue && <StudentNextDueRow nextDue={nextDue} />}

        <StudentFeedbackList summary={feedbackSummary} items={recentFeedbackItems} />

        <div className="border-t border-border-color px-4 py-3">
          <Link
            href={route('student.assignments.index')}
            className="flex items-center gap-1 text-xs font-comfortaa-bold text-student-color hover:underline"
          >
            Voir tous mes devoirs <ChevronRight size={12} />
          </Link>
        </div>
      </div>
    </aside>
  );
}
