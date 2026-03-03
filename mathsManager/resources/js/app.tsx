import '../css/app.css'; // Primary CSS entry point (Tailwind + Design System)
import './bootstrap';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { route } from 'ziggy-js';

// window.Ziggy is injected server-side by the @routes Blade directive with the correct APP_URL.
// This ensures routes always point to the right domain (local, preprod, prod) without hardcoding.
// @ts-expect-error
window.route = (name, params, absolute) => route(name, params, absolute, window.Ziggy);

const appName = import.meta.env.VITE_APP_NAME || 'Maths Manager';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
  setup({ el, App, props }) {
    const root = createRoot(el);
    root.render(<App {...props} />);
  },
  progress: {
    color: '#4B5563',
  },
});
