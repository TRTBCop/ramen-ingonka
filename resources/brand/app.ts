import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import { createPinia } from 'pinia';
import { Tooltip } from 'bootstrap';

import ElementPlus from 'element-plus';
import ko from 'element-plus/dist/locale/ko.mjs';
import * as ElementPlusIconsVue from '@element-plus/icons-vue';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) });

    app.use(plugin).use(ZiggyVue, Ziggy).use(createPinia()).use(ElementPlus, {
      locale: ko,
    });

    app.directive('tooltip', (el) => {
      new Tooltip(el);
    });
    for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
      app.component(key, component);
    }

    app.mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
