import AppLayout from '@/Layouts/AppLayout';
import { usePage, Link } from '@inertiajs/react';
import { Settings, MapPin, GraduationCap, FileText, Calendar } from 'lucide-react';
import type { PageProps, ProfileStatistics } from '@/types';
import ProfileCard from '@/Components/Features/Profile/ProfileCard';
import Card from '@/Components/Common/UI/Card';
import PageHeader from '@/Components/Common/UI/PageHeader';
import Button from '@/Components/Common/UI/Button';

interface ProfileProps extends PageProps {
  statistics?: ProfileStatistics;
}

export default function Show({ statistics }: ProfileProps) {
  const user = usePage<PageProps>().props.auth.user;
  const isTeacher = user?.role === 'teacher';

  return (
    <AppLayout title="Mon Profil">
      <div className="py-12 px-4 sm:px-6 lg:px-8">
        <div className="max-w-7xl mx-auto space-y-8">
          <PageHeader
            title="Mon Profil"
            subtitle="Consultez vos informations publiques et vos statistiques."
            breadcrumbs={[{ label: 'Mon Profil' }]}
            action={
              <Link href={route('profile.edit')}>
                <Button variant="secondary" icon={Settings} iconSize={18}>
                  <span className="hidden sm:inline">Modifier mes informations</span>
                </Button>
              </Link>
            }
          />

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
            {/* Column 1: Profile Card */}
            <div className="space-y-8 lg:col-span-1">
              <ProfileCard statistics={statistics} />
            </div>

            {/* Column 2: Details (if Teacher) or other info */}
            <div className="space-y-8 lg:col-span-2">
              {isTeacher ? (
                <Card
                  title="Informations Publiques"
                  icon={<GraduationCap className="w-5 h-5" />}
                  variant="teacher"
                >
                  <div className="space-y-6">
                    {/* Details Grid (Moved to top) */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                      {user.location && (
                        <div className="flex items-start gap-3 bg-surface-color/30 p-4 rounded-xl border border-border-color/50">
                          <MapPin className="w-5 h-5 text-teacher-color mt-0.5" />
                          <div>
                            <span className="block text-xs text-text-gray uppercase tracking-wider mb-1">
                              Localisation
                            </span>
                            <span className="text-sm font-medium text-text-color">
                              {user.location}
                            </span>
                          </div>
                        </div>
                      )}

                      {user.teaching_level && (
                        <div className="flex items-start gap-3 bg-surface-color/30 p-4 rounded-xl border border-border-color/50">
                          <GraduationCap className="w-5 h-5 text-teacher-color mt-0.5" />
                          <div>
                            <span className="block text-xs text-text-gray uppercase tracking-wider mb-1">
                              Niveau d'enseignement
                            </span>
                            <span className="text-sm font-medium text-text-color capitalize">
                              {user.teaching_level}
                            </span>
                          </div>
                        </div>
                      )}

                      {user.diploma && (
                        <div className="flex items-start gap-3 bg-surface-color/30 p-4 rounded-xl border border-border-color/50">
                          <FileText className="w-5 h-5 text-teacher-color mt-0.5" />
                          <div>
                            <span className="block text-xs text-text-gray uppercase tracking-wider mb-1">
                              Diplôme
                            </span>
                            <span className="text-sm font-medium text-text-color capitalize">
                              {user.diploma}
                            </span>
                          </div>
                        </div>
                      )}

                      {user.status === 'active' ? (
                        user.approved_at && (
                          <div className="flex items-start gap-3 bg-surface-color/30 p-4 rounded-xl border border-border-color/50">
                            <Calendar className="w-5 h-5 text-teacher-color mt-0.5" />
                            <div>
                              <span className="block text-xs text-text-gray uppercase tracking-wider mb-1">
                                Professeur Maths Manager depuis le
                              </span>
                              <span className="text-sm font-medium text-text-color capitalize">
                                {new Date(user.approved_at).toLocaleDateString('fr-FR', {
                                  day: 'numeric',
                                  month: 'long',
                                  year: 'numeric',
                                })}
                              </span>
                            </div>
                          </div>
                        )
                      ) : (
                        <div className="flex items-start gap-3 bg-surface-color/30 p-4 rounded-xl border border-border-color/50">
                          <Calendar className="w-5 h-5 text-warning-color mt-0.5" />
                          <div>
                            <span className="block text-xs text-text-gray uppercase tracking-wider mb-1">
                              Statut du compte
                            </span>
                            <span className="text-sm font-medium text-warning-color">
                              {user.status === 'pending_approval'
                                ? 'En attente de validation'
                                : user.status === 'rejected'
                                  ? 'Candidature refusée'
                                  : user.status === 'inactive'
                                    ? 'Inactif'
                                    : user.status}
                            </span>
                          </div>
                        </div>
                      )}
                    </div>

                    {/* Bio Section (Moved to bottom) */}
                    {user.bio ? (
                      <div className="bg-surface-color/50 rounded-xl p-6 border border-border-color">
                        <div className="flex items-center gap-2 mb-3 text-text-color font-comfortaa-bold">
                          <FileText className="w-4 h-4 text-teacher-color" />À propos de moi
                        </div>
                        <p className="text-text-gray font-comfortaa text-sm whitespace-pre-wrap leading-relaxed">
                          {user.bio}
                        </p>
                      </div>
                    ) : (
                      <div className="bg-surface-color/50 rounded-xl p-6 border border-border-color border-dashed text-center">
                        <p className="text-text-gray/70 text-sm font-comfortaa italic">
                          Vous n'avez pas encore rédigé de présentation.
                        </p>
                      </div>
                    )}
                  </div>
                </Card>
              ) : (
                <Card
                  title="Bienvenue sur Maths Manager"
                  className="h-full flex flex-col justify-center items-center text-center p-12 bg-surface-color/50 border-dashed"
                >
                  <div className="w-16 h-16 bg-primary-color/10 rounded-full flex items-center justify-center mb-4">
                    <span className="text-3xl">👋</span>
                  </div>
                  <h3 className="text-xl font-comfortaa-bold text-text-color mb-2">
                    Votre profil est configuré
                  </h3>
                  <p className="text-text-gray text-sm max-w-sm mb-6">
                    Vous pouvez modifier vos informations personnelles ou votre mot de passe à tout
                    moment.
                  </p>
                  <Link href={route('profile.edit')}>
                    <Button variant="secondary">Aller aux paramètres</Button>
                  </Link>
                </Card>
              )}
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
