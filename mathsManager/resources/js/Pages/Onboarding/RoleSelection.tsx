import { Head, useForm } from '@inertiajs/react';
import OnboardingLayout from '@/Layouts/OnboardingLayout';
import { GraduationCap, BookOpen } from 'lucide-react';
import RoleCard from '@/Components/Features/Onboarding/RoleCard';
import { ROLE_FEATURES } from '@/Constants/onboarding';

export default function RoleSelection() {
  const studentForm = useForm({});
  const teacherForm = useForm({});

  const handleStudent = () => {
    studentForm.post(route('onboarding.student'));
  };

  const handleTeacher = () => {
    teacherForm.get(route('onboarding.teacher'));
  };

  return (
    <OnboardingLayout maxWidth="max-w-5xl">
      <Head title="Choix de votre espace" />
      <div className="text-center mb-10 md:mb-12 px-4 md:px-0">
        <h1 className="text-2xl md:text-4xl font-comfortaa-bold text-transparent bg-clip-text bg-gradient-to-r from-tertiary-color to-student-color mb-3 md:mb-4 leading-tight">
          Comment souhaitez-vous utiliser Maths Manager ?
        </h1>
      </div>
      <div className="grid md:grid-cols-2 gap-6 md:gap-8 px-4 sm:px-0">
        <RoleCard
          theme="student"
          title="Espace Élève"
          description="Pour progresser et réussir en maths."
          icon={<BookOpen size={32} />}
          features={ROLE_FEATURES.student}
          buttonText="Rejoindre en tant qu'Élève"
          loading={studentForm.processing || teacherForm.processing}
          onClick={handleStudent}
        />

        <RoleCard
          theme="teacher"
          title="Espace Professeur"
          description="Créez l'excellence pour vos classes."
          icon={<GraduationCap size={32} />}
          features={ROLE_FEATURES.teacher}
          buttonText="Rejoindre en tant que Professeur"
          loading={studentForm.processing || teacherForm.processing}
          onClick={handleTeacher}
        />
      </div>
      <div className="text-center mt-10 md:mt-12 px-4 md:px-0">
        <p className="text-sm md:text-base text-text-gray font-comfortaa max-w-2xl mx-auto">
          Choisissez l'espace qui correspond à vos besoins. Vous ne pourrez plus le modifier par la
          suite.
        </p>
      </div>
    </OnboardingLayout>
  );
}
