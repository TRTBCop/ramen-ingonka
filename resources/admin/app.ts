import './bootstrap';
import '@/assets/css/app.scss';

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import { createPinia } from 'pinia';
import { Tooltip } from 'bootstrap';

import ElementPlus from 'element-plus';
import ko from 'element-plus/dist/locale/ko.mjs';
import * as ElementPlusIconsVue from '@element-plus/icons-vue';

import i18n from '@/admin/core/plugins/i18n';

//imports for app initialization
import ApiService from '@/admin/core/services/ApiService';
import { initApexCharts } from '@/admin/core/plugins/apexcharts';
import { initInlineSvg } from '@/admin/core/plugins/inline-svg';
import { initVeeValidate } from '@/admin/core/plugins/vee-validate';
import { initializeComponents, initKtIcon } from '@/admin/core/plugins/keenthemes';

import { useConfigStore } from '@/admin/stores/config';
import { useThemeStore } from '@/admin/stores/theme';
import { useBodyStore } from '@/admin/stores/body';
import { themeConfigValue } from '@/admin/core/helpers/config';
import 'froala-editor/js/plugins.pkgd.min.js';
import 'froala-editor/css/froala_editor.pkgd.min.css';
import 'froala-editor/css/froala_style.min.css';
import '@/assets/css/froalaEditorCustomStyle.scss';
import VueFroala from 'vue-froala-wysiwyg';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

(async function init() {
  /** froala 에디터에 math-type 적용 */
  window.FroalaEditor = await import('froala-editor');
  const jsDemoImagesTransform = document.createElement('script');
  jsDemoImagesTransform.type = 'text/javascript';
  jsDemoImagesTransform.src = '/froala_wiris/wiris.js';
  document.head.appendChild(jsDemoImagesTransform);

  jsDemoImagesTransform.onload = () => {
    createInertiaApp({
      title: (title) => `${title} - ${appName}`,
      resolve: (name) =>
        resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
      setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app
          .use(plugin)
          .use(ZiggyVue, Ziggy)
          .use(createPinia())
          .use(ElementPlus, {
            locale: ko,
          })
          .use(i18n)
          .use(VueFroala);

        app.directive('tooltip', (el) => {
          new Tooltip(el);
        });
        for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
          app.component(key, component);
        }

        ApiService.init(app);
        initApexCharts(app);
        initInlineSvg(app);
        initKtIcon(app);
        initVeeValidate();

        app.mount(el);
        const configStore = useConfigStore();
        const themeStore = useThemeStore();
        const bodyStore = useBodyStore();

        configStore.overrideLayoutConfig();
        themeStore.setThemeMode(themeConfigValue.value);
        initializeComponents();
        bodyStore.removeBodyClassName('page-loading');
      },
      progress: {
        color: '#4B5563',
      },
    });
  };
})();

// 뒤로가기 시에 페이지 reload 하게 끔 설정
window.addEventListener('popstate', (e) => {
  e.preventDefault();
  setTimeout(() => {
    router.reload();
  });
});
