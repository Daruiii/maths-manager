import AppLayout from '@/Layouts/AppLayout';
import { usePage } from '@inertiajs/react';
import { User, Lock, Trash2 } from 'lucide-react';
import type { PageProps, ProfileStatistics } from '@/types';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm';
import ProfileCard from '@/Components/Features/Profile/ProfileCard';
import Card from '@/Components/Common/UI/Card';
import PageHeader from '@/Components/Common/UI/PageHeader';

interface ProfileProps extends PageProps {
  mustVerifyEmail?: boolean;
  status?: string;
  statistics?: ProfileStatistics;
}

export default function Edit({ mustVerifyEmail, status, statistics }: ProfileProps) {
  return (
    <AppLayout title="Profil">
      <div className="py-12 px-4 sm:px-6 lg:px-8">
        <div className="max-w-7xl mx-auto">
          <PageHeader
            title="Mon Profil"
            subtitle="Gérez vos informations et sécurisez votre compte."
            breadcrumbs={[{ label: 'Mon Profil' }]}
          />

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Column 1: Profile Card + Danger Zone */}
            <div className="space-y-8">
              <ProfileCard statistics={statistics} />

              <div className="hidden lg:block">
                <Card
                  variant="danger"
                  title="Zone de Danger"
                  icon={<Trash2 className="w-5 h-5" strokeWidth={2.5} />}
                >
                  <DeleteUserForm className="" />
                </Card>
              </div>
            </div>

            {/* Column 2: Personal Info */}
            <div className="space-y-6">
              <Card
                title="Informations personnelles"
                icon={<User className="w-5 h-5" strokeWidth={2.5} />}
              >
                <UpdateProfileInformationForm
                  mustVerifyEmail={mustVerifyEmail}
                  status={status}
                  className=""
                />
              </Card>
            </div>

            {/* Column 3: Security */}
            <div className="space-y-6">
              {!usePage<PageProps>().props.auth.user?.provider ? (
                <Card
                  title="Sécurité"
                  variant="default"
                  icon={<Lock className="w-5 h-5" strokeWidth={2.5} />}
                >
                  <UpdatePasswordForm className="" />
                </Card>
              ) : (
                <Card
                  title="Sécurité"
                  variant="default"
                  icon={<Lock className="w-5 h-5" strokeWidth={2.5} />}
                  className="opacity-75"
                >
                  <div className="p-6 text-center text-gray-500 dark:text-gray-400">
                    <p className="mb-4">
                      Votre mot de passe est géré par{' '}
                      <strong>
                        {usePage<PageProps>().props.auth.user?.provider === 'google'
                          ? 'Google'
                          : 'votre fournisseur'}
                      </strong>
                      .
                    </p>
                    <div className="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-full text-sm">
                      <Lock className="w-4 h-4 mr-2" />
                      Modification désactivée
                    </div>
                  </div>
                </Card>
              )}
            </div>

            {/* Mobile only: Danger Zone at bottom */}
            <div className="lg:hidden col-span-1">
              <Card
                variant="danger"
                title="Zone de Danger"
                icon={<Trash2 className="w-5 h-5" strokeWidth={2.5} />}
              >
                <DeleteUserForm className="" />
              </Card>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
