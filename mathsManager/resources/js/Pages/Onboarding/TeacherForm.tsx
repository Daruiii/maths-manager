import { Head, useForm } from '@inertiajs/react';
import OnboardingLayout from '@/Layouts/OnboardingLayout';
import Card from '@/Components/Common/UI/Card';
import Button from '@/Components/Common/UI/Button';
import TextInput from '@/Components/Common/Form/TextInput';
import InputLabel from '@/Components/Common/Form/InputLabel';
import SelectInput from '@/Components/Common/Form/SelectInput';
import TextAreaInput from '@/Components/Common/Form/TextAreaInput';
import InputError from '@/Components/Common/Form/InputError';
import CityAutocomplete from '@/Components/Features/Onboarding/CityAutocomplete';
import TeacherInfosColumn from '@/Components/Features/Onboarding/TeacherInfosColumn';
import { TEACHING_LEVELS, DIPLOMAS } from '@/Constants/onboarding';

export default function TeacherForm() {
  const { data, setData, post, processing, errors } = useForm({
    bio: '',
    location: '',
    teaching_level: '',
    diploma: '',
    phone: '',
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    post(route('onboarding.teacher.submit'));
  };

  return (
    <OnboardingLayout maxWidth="max-w-5xl">
      <Head title="Profil professeur" />

      <div className="flex flex-col md:flex-row gap-6 lg:gap-12 px-4 sm:px-0 items-center md:items-start">
        <TeacherInfosColumn />

        <div className="md:w-7/12 w-full">
          <Card className="p-6 md:p-8 shadow-2xl rounded-[2rem] border border-border-color bg-secondary-color">
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="mb-6 text-center">
                <h2 className="text-xl md:text-2xl font-comfortaa-bold text-tertiary-color">
                  Complétez votre profil
                </h2>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <InputLabel htmlFor="teaching_level" value="Niveau *" />
                  <SelectInput
                    id="teaching_level"
                    value={data.teaching_level}
                    onChange={(e) => setData('teaching_level', e.target.value)}
                    className="mt-1 w-full"
                  >
                    <option value="">Sélectionner...</option>
                    {TEACHING_LEVELS.map(({ value, label }) => (
                      <option key={value} value={value}>
                        {label}
                      </option>
                    ))}
                  </SelectInput>
                  <InputError message={errors.teaching_level} />
                </div>
                <div>
                  <InputLabel htmlFor="diploma" value="Diplôme *" />
                  <SelectInput
                    id="diploma"
                    value={data.diploma}
                    onChange={(e) => setData('diploma', e.target.value)}
                    className="mt-1 w-full"
                  >
                    <option value="">Sélectionner...</option>
                    {DIPLOMAS.map(({ value, label }) => (
                      <option key={value} value={value}>
                        {label}
                      </option>
                    ))}
                  </SelectInput>
                  <InputError message={errors.diploma} />
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <CityAutocomplete
                  value={data.location}
                  onChange={(loc) => setData('location', loc)}
                  error={errors.location}
                />
                <div>
                  <InputLabel htmlFor="phone" value="Téléphone (optionnel)" />
                  <TextInput
                    id="phone"
                    type="tel"
                    value={data.phone}
                    onChange={(e) => {
                      const val = e.target.value.replace(/[^0-9 ]/g, '');
                      setData('phone', val);
                    }}
                    placeholder="06 12 34 56 78"
                    className="mt-1 w-full"
                  />
                  <InputError message={errors.phone} />
                </div>
              </div>

              <div>
                <InputLabel htmlFor="bio" value="Présentation *" />
                <TextAreaInput
                  id="bio"
                  value={data.bio}
                  onChange={(e) => setData('bio', e.target.value)}
                  rows={2}
                  placeholder="Ex : Prof de maths depuis 5 ans..."
                  className="mt-1 w-full"
                />
                <div className="flex justify-between items-center mt-1">
                  <p className="text-xs text-text-gray font-comfortaa">
                    {data.bio.length}/1000 caractères
                  </p>
                  <InputError message={errors.bio} className="mt-0" />
                </div>
              </div>

              <Button
                type="submit"
                variant="teacher"
                isLoading={processing}
                className="w-full mt-2 h-12"
              >
                Soumettre ma candidature
              </Button>
            </form>
          </Card>
        </div>
      </div>
    </OnboardingLayout>
  );
}
