import '../css/app.css'; // Primary CSS entry point (Tailwind + Design System)
import './bootstrap';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { route } from 'ziggy-js';
import { Ziggy } from './ziggy';

// @ts-expect-error
window.route = (name, params, absolute, config = Ziggy) => route(name, params, absolute, config);

const appName = import.meta.env.VITE_APP_NAME || 'Maths Manager';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.tsx`,
      import.meta.glob('./Pages/**/*.tsx')
    ),
  setup({ el, App, props }) {
    const root = createRoot(el);
    root.render(<App {...props} />);
  },
  progress: {
    color: '#4B5563',
  },
});
