import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import '@/assets/ts/fontAwesome';

const init = async () => {
  // scss 파일 동적 로딩
  const appName = window.location.pathname.split('/').pop()?.split('-').shift();
  switch (appName) {
    case 'app':
      document.querySelector('meta[name=viewport]')?.setAttribute('content', 'width=1024,user-scalable=no');

      await import('@/assets/css/scss/math_new.scss');
      break;
    case 'brand':
      document
        .querySelector('meta[name=viewport]')
        ?.setAttribute(
          'content',
          'width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no',
        );
      await import('@/assets/css/brand/brand.scss');
      break;
    default:
      break;
  }

  createInertiaApp({
    title: () => '리딩수학 - 퍼블리싱',
    resolve: (name) =>
      resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
      const app = createApp({ render: () => h(App, props) });

      app.use(plugin);
      app.component('FontAwesomeIcon', FontAwesomeIcon);

      app.mount(el);
    },
  });
};

init();
