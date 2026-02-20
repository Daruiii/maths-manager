import { useForm, usePage } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import { PageProps, User } from '@/types';
import InputLabel from '@/Components/Common/Form/InputLabel';
import TextInput from '@/Components/Common/Form/TextInput';
import SelectInput from '@/Components/Common/Form/SelectInput';
import TextAreaInput from '@/Components/Common/Form/TextAreaInput';
import InputError from '@/Components/Common/Form/InputError';
import Button from '@/Components/Common/UI/Button';
import CityAutocomplete from '@/Components/Features/Onboarding/CityAutocomplete';
import { TEACHING_LEVELS, DIPLOMAS } from '@/Constants/onboarding';

export default function UpdateTeacherInformationForm({ className = '' }: { className?: string }) {
  const user = usePage<PageProps>().props.auth.user as User;

  const { data, setData, post, errors, processing } = useForm({
    _method: 'PATCH',
    first_name: user.first_name, // keep these to satisfy validation if required depending on state
    last_name: user.last_name,
    email: user.email,
    bio: user.bio || '',
    location: user.location || '',
    teaching_level: user.teaching_level || '',
    diploma: user.diploma || '',
    phone: user.phone || '',
  });

  const submit: FormEventHandler = (e) => {
    e.preventDefault();
    post(route('profile.update'), {
      preserveScroll: true,
      forceFormData: true,
    });
  };

  return (
    <section className={className}>
      <div className="mb-4">
        <p className="text-sm text-text-gray">
          Mettez à jour vos informations publiques de professeur.
        </p>
      </div>

      <form onSubmit={submit} className="space-y-6">
        <div>
          <InputLabel htmlFor="bio" value="Présentation *" />
          <TextAreaInput
            id="bio"
            value={data.bio}
            onChange={(e) => setData('bio', e.target.value)}
            rows={3}
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

        <div className="flex items-center gap-4">
          <Button disabled={processing}>Enregistrer</Button>
        </div>
      </form>
    </section>
  );
}
