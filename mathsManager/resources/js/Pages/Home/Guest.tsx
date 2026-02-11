import { Mail, Layout } from 'lucide-react';
import FeatureCard from '@/Components/UI/FeatureCard';

interface GuestProps {
  introContent?: any;
  whoamiContent?: any;
}

/**
 * Guest Homepage View
 * Clean rewrite with modular feature cards and semantic sections.
 */
export default function Guest({ introContent, whoamiContent }: GuestProps) {
  return (
    <div className="space-y-20 animate-in fade-in slide-in-from-bottom-6 duration-700">
      
      {/* Hero / Intro Section */}
      <section 
        className="bg-white rounded-3xl p-8 md:p-16 shadow-sm border border-gray-100 flex flex-col lg:flex-row gap-16 items-center overflow-hidden"
        aria-labelledby="hero-title"
      >
        <div className="flex-1 space-y-8">
          <h1 id="hero-title" className="text-4xl md:text-5xl lg:text-6xl font-comfortaa-bold text-admin-color leading-[1.15]">
            {introContent?.title || 'Bienvenue sur Maths Manager'}
          </h1>
          <div 
            className="text-text-gray leading-relaxed text-lg md:text-xl font-comfortaa-light max-w-2xl"
            dangerouslySetInnerHTML={{ __html: introContent?.content || 'Plateforme de gestion de maths...' }}
          />
          <nav className="pt-6 flex flex-wrap gap-5" aria-label="Guest actions">
            <a 
              href="/login" 
              className="btn btn-primary !px-10 !py-4 text-lg"
            >
              C'est parti !
            </a>
            <a 
              href="mailto:maxime@mathsmanager.fr" 
              className="btn btn-secondary !px-10 !py-4 text-lg gap-3"
            >
              <Mail className="h-5 w-5" />
              Contacter Maxime
            </a>
          </nav>
        </div>
        
        {/* Visual / Video Area */}
        <figure className="w-full lg:w-1/2 aspect-video bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center relative overflow-hidden group shadow-inner">
            <div className="absolute inset-0 bg-gradient-to-tr from-admin-color/5 to-transparent"></div>
            <div className="p-4 bg-white/80 rounded-full shadow-sm mb-2 group-hover:scale-110 transition-transform">
                <Layout className="h-8 w-8 text-admin-color" />
            </div>
            <figcaption className="text-text-gray/50 font-comfortaa italic text-sm">
              Vidéo de présentation
            </figcaption>
        </figure>
      </section>

      {/* About & Features Grid */}
      <section className="flex flex-col md:flex-row gap-12 items-start">
        {/* Bio Card */}
        <aside className="md:w-1/3 bg-white rounded-3xl p-10 shadow-sm border border-gray-100 flex flex-col items-center text-center space-y-6">
          <h2 className="text-2xl font-comfortaa-bold text-admin-color">
            {whoamiContent?.title || 'Qui suis-je ?'}
          </h2>
          
          {whoamiContent?.image && (
            <div className="relative group">
              <div className="absolute inset-0 border-2 border-admin-color rounded-full scale-110 opacity-10 group-hover:scale-125 group-hover:opacity-20 transition-all duration-500"></div>
              <img 
                src={whoamiContent.image} 
                alt="Portrait de Maxime" 
                className="w-40 h-40 rounded-full object-cover border-4 border-white shadow-lg group-hover:rotate-3 transition-transform duration-500"
              />
            </div>
          )}
          
          <div 
            className="text-base text-text-gray leading-relaxed font-comfortaa"
            dangerouslySetInnerHTML={{ __html: whoamiContent?.content || 'Professeur passionné...' }}
          />
        </aside>

        {/* Features List */}
        <div className="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-8">
           <FeatureCard 
             title="Exercices" 
             desc="Plus de 1000 exercices classés par chapitres et difficulté d'apprentissage." 
             icon="📐"
           />
           <FeatureCard 
             title="Quizz" 
             desc="Testez vos connaissances en temps réel avec des scores et corrections immédiats." 
             icon="⚡"
           />
           <FeatureCard 
             title="Fiches" 
             desc="Des résumés de cours clairs, synthétiques et visuels pour aller à l'essentiel." 
             icon="📄"
           />
           <FeatureCard 
             title="Suivi" 
             desc="Visualisez votre progression, vos points forts et vos axes d'amélioration." 
             icon="📈"
           />
        </div>
      </section>
    </div>
  );
}
