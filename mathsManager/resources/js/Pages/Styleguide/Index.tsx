import { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import Card from '@/Components/Common/UI/Card';
import Button from '@/Components/Common/UI/Button';
import UserCard from '@/Components/Features/User/UserCard';
import PageHeader from '@/Components/Common/UI/PageHeader';
import SlidePanel from '@/Components/Common/UI/SlidePanel';
import CheckboxCard, { CheckboxIndicator } from '@/Components/Common/UI/CheckboxCard';
import { useAuth } from '@/Hooks/Auth/useAuth';
import {
  GraduationCap,
  Users,
  ShieldCheck,
  Palette,
  LayoutGrid,
  Type,
  MousePointer,
  Code2,
  Layers,
} from 'lucide-react';

type Tab = 'colors' | 'cards' | 'buttons' | 'typography' | 'usercards' | 'css' | 'composants';

const TABS: { id: Tab; label: string; icon: React.ReactNode }[] = [
  { id: 'colors', label: 'Couleurs', icon: <Palette className="w-4 h-4" /> },
  { id: 'cards', label: 'Cards', icon: <LayoutGrid className="w-4 h-4" /> },
  { id: 'buttons', label: 'Boutons', icon: <MousePointer className="w-4 h-4" /> },
  { id: 'typography', label: 'Typographie', icon: <Type className="w-4 h-4" /> },
  { id: 'usercards', label: 'UserCards', icon: <Users className="w-4 h-4" /> },
  { id: 'css', label: 'CSS Classes', icon: <Code2 className="w-4 h-4" /> },
  { id: 'composants', label: 'Composants', icon: <Layers className="w-4 h-4" /> },
];

// Badge nom de classe CSS — affiché sous chaque démo
function Cls({ name }: { name: string }) {
  return (
    <code className="inline-block text-xs font-mono bg-surface-color border border-border-color text-tertiary-color px-2 py-0.5 rounded-md">
      .{name}
    </code>
  );
}

// ─── Section: Couleurs ────────────────────────────────────────────────────────
function ColorsTab() {
  const tokens = [
    {
      name: 'primary-color',
      label: 'Fond principal',
      bg: 'bg-primary-color',
      text: 'text-text-color',
    },
    {
      name: 'secondary-color',
      label: 'Fond cartes',
      bg: 'bg-secondary-color',
      text: 'text-text-color',
    },
    {
      name: 'surface-color',
      label: 'Survols / zones',
      bg: 'bg-surface-color',
      text: 'text-text-color',
    },
    { name: 'border-color', label: 'Bordures', bg: 'bg-border-color', text: 'text-text-color' },
    {
      name: 'text-color',
      label: 'Texte principal',
      bg: 'bg-text-color',
      text: 'text-primary-color',
    },
    {
      name: 'text-gray',
      label: 'Texte secondaire',
      bg: 'bg-text-gray',
      text: 'text-primary-color',
    },
    { name: 'tertiary-color', label: 'Liens / focus', bg: 'bg-tertiary-color', text: 'text-white' },
    { name: 'student-color', label: 'Élève (teal)', bg: 'bg-student-color', text: 'text-white' },
    { name: 'teacher-color', label: 'Prof (violet)', bg: 'bg-teacher-color', text: 'text-white' },
    { name: 'admin-color', label: 'Admin (amber)', bg: 'bg-admin-color', text: 'text-white' },
    { name: 'error-color', label: 'Erreur', bg: 'bg-error-color', text: 'text-white' },
    { name: 'success-color', label: 'Succès', bg: 'bg-success-color', text: 'text-white' },
    { name: 'warning-color', label: 'Avertissement', bg: 'bg-warning-color', text: 'text-white' },
  ];

  return (
    <div className="space-y-6">
      <p className="text-sm text-text-gray font-comfortaa">
        Tous les tokens sémantiques du design system. Utiliser exclusivement ces classes — jamais{' '}
        <code className="text-error-color bg-surface-color px-1 rounded">gray-*</code> ou hex
        hardcodé.
      </p>
      <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
        {tokens.map((t) => (
          <div key={t.name} className="rounded-xl overflow-hidden border border-border-color">
            <div className={`${t.bg} h-16`} />
            <div className="bg-secondary-color p-3">
              <p className="text-xs font-mono font-bold text-text-color">{t.name}</p>
              <p className="text-xs text-text-gray mt-0.5">{t.label}</p>
            </div>
          </div>
        ))}
      </div>

      <div className="space-y-2">
        <p className="text-xs text-text-gray uppercase tracking-widest font-mono">Opacités</p>
        <div className="flex gap-2 flex-wrap">
          {[10, 20, 30, 50, 70, 100].map((op) => (
            <div
              key={op}
              className={`bg-tertiary-color/${op} border border-border-color rounded-lg px-4 py-2`}
            >
              <span className="text-xs font-mono text-text-color">/{op}</span>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

// ─── Section: Cards ───────────────────────────────────────────────────────────
function CardsTab() {
  return (
    <div className="space-y-8">
      <div className="space-y-3">
        <p className="text-xs text-text-gray uppercase tracking-widest font-mono">
          {'<Card />'} — variants
        </p>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          {(['default', 'teacher', 'student', 'admin', 'danger'] as const).map((v) => (
            <Card
              key={v}
              title={`variant="${v}"`}
              icon={<GraduationCap className="w-5 h-5" />}
              variant={v}
            >
              <p className="text-sm text-text-gray">Contenu de la card.</p>
            </Card>
          ))}
        </div>
      </div>
    </div>
  );
}

// ─── Section: Boutons ─────────────────────────────────────────────────────────
function ButtonsTab() {
  const variants = [
    'primary',
    'secondary',
    'danger',
    'success',
    'ghost',
    'teacher',
    'student',
  ] as const;
  const sizes = ['sm', 'md', 'lg'] as const;

  return (
    <div className="space-y-8">
      <div className="space-y-3">
        <p className="text-xs text-text-gray uppercase tracking-widest font-mono">Variants</p>
        <div className="flex flex-wrap gap-3">
          {variants.map((v) => (
            <Button key={v} variant={v}>
              {v}
            </Button>
          ))}
        </div>
      </div>

      <div className="space-y-3">
        <p className="text-xs text-text-gray uppercase tracking-widest font-mono">Tailles</p>
        <div className="flex items-center flex-wrap gap-3">
          {sizes.map((s) => (
            <Button key={s} size={s}>
              size="{s}"
            </Button>
          ))}
        </div>
      </div>

      <div className="space-y-3">
        <p className="text-xs text-text-gray uppercase tracking-widest font-mono">États</p>
        <div className="flex flex-wrap gap-3">
          <Button isLoading>Chargement</Button>
          <Button disabled>Désactivé</Button>
          <Button icon={GraduationCap}>Avec icône</Button>
          <Button icon={ShieldCheck} variant="teacher">
            Teacher
          </Button>
        </div>
      </div>
    </div>
  );
}

// ─── Section: Typographie ─────────────────────────────────────────────────────
function TypographyTab() {
  return (
    <div className="space-y-6">
      <div className="space-y-4">
        <p className="text-xs text-text-gray uppercase tracking-widest font-mono">Comfortaa</p>
        <div className="space-y-2">
          <p className="font-comfortaa text-2xl text-text-color">font-comfortaa — Texte courant</p>
          <p className="font-comfortaa-bold text-2xl text-text-color">
            font-comfortaa-bold — Titres
          </p>
        </div>
      </div>

      <div className="space-y-3">
        <p className="text-xs text-text-gray uppercase tracking-widest font-mono">Hiérarchie</p>
        <div className="space-y-2 card-theorem border-l-tertiary-color p-6">
          <p className="text-3xl font-comfortaa-bold text-text-color">Titre H1 — 3xl bold</p>
          <p className="text-2xl font-comfortaa-bold text-text-color">Titre H2 — 2xl bold</p>
          <p className="text-xl font-comfortaa-bold text-text-color">Titre H3 — xl bold</p>
          <p className="text-base font-comfortaa text-text-color">Corps — base normal</p>
          <p className="text-sm font-comfortaa text-text-gray">Secondaire — sm text-gray</p>
          <p className="text-xs font-mono text-text-gray uppercase tracking-widest">
            Label — xs mono uppercase
          </p>
        </div>
      </div>

      <div className="space-y-3">
        <p className="text-xs text-text-gray uppercase tracking-widest font-mono">
          Couleurs par rôle
        </p>
        <div className="flex flex-wrap gap-4">
          <span className="font-comfortaa-bold text-student-color">Élève (teal)</span>
          <span className="font-comfortaa-bold text-teacher-color">Professeur (violet)</span>
          <span className="font-comfortaa-bold text-admin-color">Admin (amber)</span>
          <span className="font-comfortaa-bold text-error-color">Erreur</span>
          <span className="font-comfortaa-bold text-success-color">Succès</span>
          <span className="font-comfortaa-bold text-warning-color">Avertissement</span>
        </div>
      </div>
    </div>
  );
}

// ─── Section: UserCards ───────────────────────────────────────────────────────
function UserCardsTab({ user }: { user: NonNullable<ReturnType<typeof useAuth>['user']> }) {
  return (
    <div className="space-y-8">
      <div className="space-y-3">
        <p className="text-xs text-text-gray uppercase tracking-widest font-mono">
          accentColor × variant
        </p>
        <div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
          {(['student', 'teacher', 'admin'] as const).map((color) => (
            <div key={color} className="space-y-3">
              <p className="text-xs font-mono text-text-gray text-center">accentColor="{color}"</p>
              {(['default', 'dot-grid', 'lines'] as const).map((v) => (
                <div key={v} className="space-y-1">
                  <p className="text-xs text-text-gray/60 text-center">variant="{v}"</p>
                  <UserCard user={user} accentColor={color} variant={v} />
                </div>
              ))}
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

// ─── Section: CSS Classes ─────────────────────────────────────────────────────
function CssClassesTab() {
  const classes = [
    {
      name: 'card-theorem',
      description: 'Encadré style "théorème" académique. Base de toutes les cards custom.',
      combines: [
        'border-l-student-color',
        'border-l-teacher-color',
        'border-l-admin-color',
        'card-dot-grid',
        'card-lines',
      ],
      demo: (
        <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
          {(['student', 'teacher', 'admin'] as const).map((role) => (
            <div
              key={role}
              className={`card-theorem border-l-${role}-color hover:bg-${role}-color/5 p-4`}
            >
              <p className="text-sm font-comfortaa-bold text-text-color capitalize">{role}</p>
              <p className="text-xs text-text-gray mt-1">Texte de contenu</p>
            </div>
          ))}
        </div>
      ),
    },
    {
      name: 'card-dot-grid',
      description: 'Fond à points. Toujours combiné avec .card-theorem.',
      combines: ['card-theorem', 'border-l-*-color'],
      demo: (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div className="card-theorem border-l-teacher-color p-4">
            <p className="text-xs text-text-gray">Sans</p>
          </div>
          <div className="card-theorem card-dot-grid border-l-teacher-color p-4">
            <p className="text-xs text-text-gray">Avec .card-dot-grid</p>
          </div>
        </div>
      ),
    },
    {
      name: 'card-lines',
      description: 'Fond à lignes diagonales subtiles. Toujours combiné avec .card-theorem.',
      combines: ['card-theorem', 'border-l-*-color'],
      demo: (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div className="card-theorem border-l-admin-color p-4">
            <p className="text-xs text-text-gray">Sans</p>
          </div>
          <div className="card-theorem card-lines border-l-admin-color p-4">
            <p className="text-xs text-text-gray">Avec .card-lines</p>
          </div>
        </div>
      ),
    },
    {
      name: 'nav-link',
      description:
        "Lien de navigation avec indicateur actif en underline. Ajouter .active pour l'afficher.",
      combines: ['active'],
      demo: (
        <div className="flex gap-6 border-b border-border-color pb-1">
          <span className="nav-link active">Actif</span>
          <span className="nav-link">Inactif</span>
          <span className="nav-link">Autre</span>
        </div>
      ),
    },
    {
      name: 'custom-scrollbar',
      description: 'Scrollbar fine et discrète. Appliquer sur un conteneur avec overflow.',
      combines: ['overflow-y-auto', 'overflow-x-auto'],
      demo: (
        <div className="custom-scrollbar overflow-y-auto h-24 border border-border-color rounded-xl p-3 space-y-2">
          {Array.from({ length: 8 }).map((_, i) => (
            <p key={i} className="text-xs text-text-gray">
              Ligne {i + 1} — scroll pour voir la scrollbar
            </p>
          ))}
        </div>
      ),
    },
  ];

  return (
    <div className="space-y-10">
      <p className="text-sm text-text-gray font-comfortaa">
        Classes custom définies dans{' '}
        <code className="text-xs font-mono bg-surface-color border border-border-color text-tertiary-color px-2 py-0.5 rounded-md">
          resources/css/mathsmanager.css
        </code>
        . Réutilisables partout dans l'app.
      </p>

      {classes.map((cls) => (
        <div key={cls.name} className="space-y-4 pb-8 border-b border-border-color last:border-0">
          {/* Nom + description */}
          <div className="space-y-1">
            <Cls name={cls.name} />
            <p className="text-sm text-text-color mt-2">{cls.description}</p>
          </div>

          {/* Se combine avec */}
          <div className="space-y-1.5">
            <p className="text-xs text-text-gray uppercase tracking-widest font-mono">
              Se combine avec
            </p>
            <div className="flex gap-1.5 flex-wrap">
              {cls.combines.map((c) => (
                <code
                  key={c}
                  className="text-xs font-mono bg-surface-color border border-border-color text-text-gray px-2 py-0.5 rounded-md"
                >
                  {c.startsWith('.') ? c : `.${c}`}
                </code>
              ))}
            </div>
          </div>

          {/* Rendu */}
          <div className="space-y-1.5">
            <p className="text-xs text-text-gray uppercase tracking-widest font-mono">Rendu</p>
            {cls.demo}
          </div>
        </div>
      ))}
    </div>
  );
}

// ─── Section: Composants ──────────────────────────────────────────────────────
function ComposantsTab() {
  const [panelOpen, setPanelOpen] = useState(false);
  const [selected, setSelected] = useState<Record<string, boolean>>({});

  const toggle = (id: string) => setSelected((prev) => ({ ...prev, [id]: !prev[id] }));

  const items = [
    { id: 'a', label: 'Exercice — Suites numériques', sub: 'Terminale · 15 min' },
    { id: 'b', label: "Problème — Géométrie dans l'espace", sub: 'Première · 25 min' },
    { id: 'c', label: 'Exercice — Dérivation', sub: 'Terminale · 10 min' },
  ];

  return (
    <div className="space-y-10">
      {/* ── SlidePanel ── */}
      <div className="space-y-4 pb-8 border-b border-border-color">
        <div className="space-y-1">
          <code className="inline-block text-xs font-mono bg-surface-color border border-border-color text-tertiary-color px-2 py-0.5 rounded-md">
            {'<SlidePanel />'}
          </code>
          <p className="text-sm text-text-color mt-2">
            Panneau latéral animé. Backdrop fade + slide depuis la droite via{' '}
            <code className="text-xs font-mono bg-surface-color border border-border-color text-text-gray px-1.5 py-0.5 rounded">
              @headlessui/react {'<Transition>'}
            </code>
            .
          </p>
        </div>

        <div className="space-y-1.5">
          <p className="text-xs text-text-gray uppercase tracking-widest font-mono">Props</p>
          <div className="flex gap-1.5 flex-wrap">
            {[
              'isOpen',
              'onClose',
              'title',
              'subtitle?',
              'footer?',
              'children',
              "size='sm'|'md'",
            ].map((p) => (
              <code
                key={p}
                className="text-xs font-mono bg-surface-color border border-border-color text-text-gray px-2 py-0.5 rounded-md"
              >
                {p}
              </code>
            ))}
          </div>
        </div>

        <div className="space-y-1.5">
          <p className="text-xs text-text-gray uppercase tracking-widest font-mono">Démo</p>
          <Button variant="teacher" size="sm" onClick={() => setPanelOpen(true)}>
            Ouvrir le panneau
          </Button>
          <SlidePanel
            isOpen={panelOpen}
            onClose={() => setPanelOpen(false)}
            title="Assigner le DS"
            subtitle="Sélectionnez un élève ou un groupe"
            footer={
              <Button variant="teacher" className="w-full justify-center">
                Confirmer
              </Button>
            }
          >
            <p className="text-sm text-text-gray">Contenu du panneau…</p>
          </SlidePanel>
        </div>
      </div>

      {/* ── CheckboxCard ── */}
      <div className="space-y-4">
        <div className="space-y-1">
          <code className="inline-block text-xs font-mono bg-surface-color border border-border-color text-tertiary-color px-2 py-0.5 rounded-md">
            {'<CheckboxCard />'}
          </code>
          <p className="text-sm text-text-color mt-2">
            Carte sélectionnable. État sélectionné : bordure{' '}
            <code className="text-xs font-mono bg-surface-color border border-border-color text-teacher-color px-1.5 py-0.5 rounded">
              border-teacher-color/60
            </code>{' '}
            + fond{' '}
            <code className="text-xs font-mono bg-surface-color border border-border-color text-teacher-color px-1.5 py-0.5 rounded">
              bg-teacher-color/5
            </code>
            . Token-only, aucune valeur hardcodée.
          </p>
        </div>

        <div className="space-y-1.5">
          <p className="text-xs text-text-gray uppercase tracking-widest font-mono">
            État normal vs sélectionné
          </p>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
            <CheckboxCard isSelected={false}>
              <div className="flex items-center gap-3 p-3">
                <CheckboxIndicator isSelected={false} />
                <div>
                  <p className="text-sm font-comfortaa-bold text-text-color">Non sélectionné</p>
                  <p className="text-xs text-text-gray">isSelected=false</p>
                </div>
              </div>
            </CheckboxCard>
            <CheckboxCard isSelected={true}>
              <div className="flex items-center gap-3 p-3">
                <CheckboxIndicator isSelected={true} />
                <div>
                  <p className="text-sm font-comfortaa-bold text-text-color">Sélectionné</p>
                  <p className="text-xs text-text-gray">isSelected=true</p>
                </div>
              </div>
            </CheckboxCard>
          </div>
        </div>

        <div className="space-y-1.5">
          <p className="text-xs text-text-gray uppercase tracking-widest font-mono">
            Démo interactive
          </p>
          <div className="space-y-2">
            {items.map((item) => (
              <CheckboxCard
                key={item.id}
                isSelected={!!selected[item.id]}
                onToggle={() => toggle(item.id)}
              >
                <div className="flex items-center gap-3 p-3">
                  <CheckboxIndicator isSelected={!!selected[item.id]} />
                  <div className="flex-1 text-left">
                    <p className="text-sm font-comfortaa-bold text-text-color">{item.label}</p>
                    <p className="text-xs text-text-gray">{item.sub}</p>
                  </div>
                </div>
              </CheckboxCard>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

// ─── Page principale ──────────────────────────────────────────────────────────
export default function StyleguideIndex() {
  const [activeTab, setActiveTab] = useState<Tab>('colors');
  const { user } = useAuth();

  if (!user) return null;

  const renderTab = () => {
    switch (activeTab) {
      case 'colors':
        return <ColorsTab />;
      case 'cards':
        return <CardsTab />;
      case 'buttons':
        return <ButtonsTab />;
      case 'typography':
        return <TypographyTab />;
      case 'usercards':
        return <UserCardsTab user={user} />;
      case 'css':
        return <CssClassesTab />;
      case 'composants':
        return <ComposantsTab />;
    }
  };

  return (
    <AppLayout title="Styleguide">
      <div className="py-12 px-4 sm:px-6 lg:px-8">
        <div className="max-w-7xl mx-auto space-y-8">
          <PageHeader
            title="Styleguide"
            subtitle="Design system MathsManager — composants, tokens, variantes."
            breadcrumbs={[{ label: 'Admin' }, { label: 'Styleguide' }]}
          />

          {/* Layout sidebar gauche + contenu */}
          <div className="flex gap-8 items-start">
            {/* Sidebar tabs */}
            <nav className="w-48 shrink-0 sticky top-8 space-y-1">
              {TABS.map((tab) => (
                <button
                  key={tab.id}
                  onClick={() => setActiveTab(tab.id)}
                  className={`w-full flex items-center gap-3 px-4 py-2.5 rounded-r-xl text-sm font-comfortaa-bold transition-colors text-left ${
                    activeTab === tab.id
                      ? 'bg-tertiary-color/10 text-tertiary-color border-l-2 border-tertiary-color'
                      : 'text-text-gray hover:text-text-color hover:bg-surface-color border-l-2 border-transparent'
                  }`}
                >
                  {tab.icon}
                  {tab.label}
                </button>
              ))}
            </nav>

            {/* Contenu */}
            <div className="flex-1 min-w-0">{renderTab()}</div>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
