import { useMemo } from 'react';
import { ChevronRight, ClipboardList, CheckCircle2, Clock, PenTool, Send, Calendar, GraduationCap, Layout, Zap } from 'lucide-react';
import StatCard from '@/Components/UI/StatCard';
import QuickLink from '@/Components/UI/QuickLink';

/**
 * Dashboard Props Interface
 * Using strict types as per CLAUDE.md
 */
interface StudentProps {
  totalDS?: number;
  notStartedDS?: number;
  inProgressDS?: number;
  sentDS?: number;
  correctedDS?: number;
  averageGrade?: string | number;
  scores?: string | number;
  goodAnswers?: number;
  badAnswers?: number;
}

/**
 * Student Dashboard Component
 * Clean rewrite following absolute import rules and component modularity.
 */
export default function Student(props: StudentProps) {
  const { 
    totalDS = 0, 
    notStartedDS = 0, 
    inProgressDS = 0, 
    sentDS = 0, 
    correctedDS = 0,
    averageGrade = "N/A",
    scores = "N/A"
  } = props;

  /**
   * Calculate grade status color based on average grade.
   */
  const gradeColorClass = useMemo(() => {
    const numGrade = typeof averageGrade === 'string' ? parseFloat(averageGrade) : averageGrade;
    if (isNaN(numGrade)) return 'text-text-gray';
    if (numGrade >= 14) return 'text-success-color';
    if (numGrade >= 10) return 'text-orange-500';
    return 'text-error-color';
  }, [averageGrade]);

  return (
    <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {/* Main DS Stats Section */}
        <section className="lg:col-span-2 bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col">
          <header className="flex justify-between items-center mb-8">
            <h2 className="text-xl font-comfortaa-bold flex items-center gap-2">
              <ClipboardList className="h-5 w-5 text-admin-color" />
              Devoirs ({totalDS})
            </h2>
            <a href="/ds/myDS" className="text-sm font-comfortaa-bold text-admin-color flex items-center gap-1 hover:underline">
              Voir tout <ChevronRight className="h-4 w-4" />
            </a>
          </header>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-8 flex-grow">
            <div className="grid grid-cols-2 gap-4">
              <StatCard label="À faire" value={notStartedDS} icon={<Clock className="h-4 w-4" />} color="bg-admin-color" />
              <StatCard label="En cours" value={inProgressDS} icon={<PenTool className="h-4 w-4" />} color="bg-orange-500" />
              <StatCard label="Envoyés" value={sentDS} icon={<Send className="h-4 w-4" />} color="bg-blue-500" />
              <StatCard label="Corrigés" value={correctedDS} icon={<CheckCircle2 className="h-4 w-4" />} color="bg-success-color" />
            </div>

            <article className="bg-gray-50 rounded-2xl p-6 flex flex-col items-center justify-center border border-gray-100 relative overflow-hidden group">
              <div className="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <CheckCircle2 className="h-32 w-32" />
              </div>
              <p className="text-4xl font-comfortaa-bold mb-2 flex items-baseline gap-1">
                <span className={gradeColorClass}>{averageGrade}</span>
                <span className="text-sm text-text-gray font-normal">/20</span>
              </p>
              <h3 className="text-sm text-text-gray font-comfortaa text-center uppercase tracking-wide">
                Moyenne des devoirs
              </h3>
            </article>
          </div>
        </section>

        {/* Quizz Overview Section */}
        <section className="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col items-center">
          <h2 className="text-xl font-comfortaa-bold mb-1 w-full">Quizz</h2>
          <p className="text-xs text-text-gray font-comfortaa mb-6 w-full">
            Moyenne (10 derniers) : <span className="font-bold text-text-color">{scores} / 10</span>
          </p>
          
          <div className="relative w-32 h-32 flex items-center justify-center rounded-full border-[10px] border-gray-100 mb-6 group">
            <div className="text-2xl font-comfortaa-bold text-success-color group-hover:scale-110 transition-transform">
              {props.goodAnswers ?? 0}
            </div>
            {/* Success visualization could be an SVG radial progress here */}
          </div>
          
          <a 
            href="/quizzes" 
            className="mt-auto w-full py-3 border-2 border-gray-100 rounded-2xl font-comfortaa-bold text-text-gray hover:bg-gray-50 transition text-center"
          >
            Mes quizz
          </a>
        </section>
      </div>

      {/* Modern Quick Access Grid */}
      <nav className="grid grid-cols-2 md:grid-cols-4 gap-4" aria-label="Accès rapide">
        <QuickLink icon={<GraduationCap className="h-5 w-5" />} label="Exercices" href="/exercices" />
        <QuickLink icon={<Zap className="h-5 w-5" />} label="Quizz" href="/quizzes" />
        <QuickLink icon={<Layout className="h-5 w-5" />} label="Fiches" href="/fiches" />
        <QuickLink icon={<Calendar className="h-5 w-5" />} label="Mon Planning" href="/planning" />
      </nav>
    </div>
  );
}
