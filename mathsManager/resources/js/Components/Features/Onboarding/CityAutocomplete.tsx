import { useState, useEffect, useRef } from 'react';
import TextInput from '@/Components/Common/Form/TextInput';
import InputLabel from '@/Components/Common/Form/InputLabel';
import InputError from '@/Components/Common/Form/InputError';

interface CityResult {
  nom: string;
  codesPostaux: string[];
}

interface CityAutocompleteProps {
  value: string;
  onChange: (value: string) => void;
  error?: string;
}

export default function CityAutocomplete({ value, onChange, error }: CityAutocompleteProps) {
  const [cityQuery, setCityQuery] = useState(value);
  const [citySuggestions, setCitySuggestions] = useState<CityResult[]>([]);
  const [showSuggestions, setShowSuggestions] = useState(false);
  const debounceRef = useRef<ReturnType<typeof setTimeout> | null>(null);

  useEffect(() => {
    if (cityQuery.length < 2) {
      setCitySuggestions([]);
      return;
    }
    // Prevent fetching if it's the exact same formatted string containing a parenthesis (meaning it was just selected)
    if (cityQuery === value && cityQuery.includes('(')) {
      return;
    }

    if (debounceRef.current) clearTimeout(debounceRef.current);
    debounceRef.current = setTimeout(async () => {
      try {
        const res = await fetch(
          `https://geo.api.gouv.fr/communes?nom=${encodeURIComponent(cityQuery)}&fields=nom,codesPostaux&limit=6&boost=population`
        );
        const result: CityResult[] = await res.json();
        setCitySuggestions(result);
        setShowSuggestions(true);
      } catch {
        setCitySuggestions([]);
      }
    }, 300);
  }, [cityQuery, value]);

  const selectCity = (city: CityResult) => {
    const label = `${city.nom} (${city.codesPostaux[0] ?? ''})`;
    onChange(label);
    setCityQuery(label);
    setShowSuggestions(false);
  };

  return (
    <div className="relative">
      <InputLabel htmlFor="location" value="Ville *" />
      <TextInput
        id="location"
        value={cityQuery}
        onChange={(e) => {
          setCityQuery(e.target.value);
          if (e.target.value === '') {
            onChange('');
          }
        }}
        onBlur={() => {
          setTimeout(() => {
            setShowSuggestions(false);
            // Si la query ne correspond pas à la valeur validée, on vide
            if (cityQuery !== value) {
              setCityQuery('');
              onChange('');
            }
          }, 200);
        }}
        onFocus={() => {
          if (citySuggestions.length > 0) setShowSuggestions(true);
        }}
        placeholder="Ex : Paris, Lyon, Bordeaux..."
        className="mt-1 w-full"
        autoComplete="off"
      />
      {showSuggestions && citySuggestions.length > 0 && (
        <ul className="absolute z-10 mt-1 w-full rounded-xl border border-border-color bg-secondary-color shadow-lg overflow-hidden max-h-60 overflow-y-auto">
          {citySuggestions.map((city) => (
            <li
              key={`${city.nom}-${city.codesPostaux[0]}`}
              onMouseDown={() => selectCity(city)}
              className="cursor-pointer px-4 py-3 text-sm text-text-color hover:bg-surface-color transition-colors flex justify-between font-comfortaa"
            >
              <span>{city.nom}</span>
              <span className="text-xs text-text-gray">{city.codesPostaux[0]}</span>
            </li>
          ))}
        </ul>
      )}
      <InputError message={error} />
    </div>
  );
}
