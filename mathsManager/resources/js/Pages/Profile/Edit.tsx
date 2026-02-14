import AppLayout from '@/Layouts/AppLayout';
import type { PageProps, ProfileStatistics } from '@/types';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm';
import ProfileCard from '@/Components/Features/Profile/ProfileCard';

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
          <div className="mb-8">
            <h2 className="font-comfortaa-bold text-3xl text-gray-800 dark:text-gray-200">
              Mon Profil
            </h2>
            <p className="mt-2 text-text-gray dark:text-gray-400">
              Gérez vos informations personnelles et sécurisez votre compte.
            </p>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Left Column (Desktop) / Top (Mobile) */}
            <div className="lg:col-span-1 space-y-8">
              <ProfileCard statistics={statistics} />

              {/* Desktop only: Delete form is here */}
              <div className="hidden lg:block p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-sm rounded-2xl">
                <DeleteUserForm className="max-w-xl" />
              </div>
            </div>

            {/* Right Column (Desktop) / Middle (Mobile) */}
            <div className="lg:col-span-2 space-y-8">
              <div className="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-sm rounded-2xl">
                <UpdateProfileInformationForm
                  mustVerifyEmail={mustVerifyEmail}
                  status={status}
                  className="max-w-xl"
                />
              </div>

              <div className="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-sm rounded-2xl">
                <UpdatePasswordForm className="max-w-xl" />
              </div>
            </div>

            {/* Mobile only: Delete form at the very bottom */}
            <div className="lg:hidden col-span-1 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-sm rounded-2xl">
              <DeleteUserForm className="max-w-xl" />
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
