import { Config } from 'ziggy-js';

declare const Ziggy: Config;

declare global {
  interface Window {
    Ziggy: Config;
  }
}

export { Ziggy };
