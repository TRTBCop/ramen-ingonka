import { PageProps as InertiaPageProps } from '@inertiajs/core';

declare global {
  interface Window {
    $: any;
  }

  const $: any;
  const route: typeof ziggyRoute;
  const Ziggy: ZiggyConfig;
  const __APP_INFO__: {
    releaseVersion: string;
    lastBuildDate: string;
  };
}

declare module 'vue' {
  interface ComponentCustomProperties {
    route: typeof ziggyRoute;
  }
}

declare module '@inertiajs/core' {
  interface PageProps extends InertiaPageProps, AppPageProps {}
}
