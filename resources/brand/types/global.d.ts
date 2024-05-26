import ziggyRoute, { Config as ZiggyConfig } from 'ziggy-js';

declare global {
  const route: typeof ziggyRoute;
  const Ziggy: ZiggyConfig;
}

declare module 'vue' {
  interface ComponentCustomProperties {
    route: typeof ziggyRoute;
  }
}
