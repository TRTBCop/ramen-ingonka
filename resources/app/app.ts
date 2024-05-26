import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { setupStore } from '@/app/stores';
import ApiService from '@/app/core/services/ApiService';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import '@/assets/ts/fontAwesome';
import '@/assets/css/scss/math_new.scss';
import VCalendar from 'v-calendar';
import 'v-calendar/style.css';
import dayjs from 'dayjs';
import 'dayjs/locale/ko'; // 한글 로케일 불러오기
import { initApexCharts } from '@/admin/core/plugins/apexcharts';
import 'froala-editor/css/froala_style.min.css';
import { createI18n } from 'vue-i18n';
import messages from '@/app/lang/index';
import Gleap from 'gleap';

const { VITE_GLEAP_KEY } = import.meta.env;

const i18n = createI18n({
  legacy: false,
  locale: 'ko',
  messages: messages,
});

if (VITE_GLEAP_KEY) {
  Gleap.setLanguage('ko');
  Gleap.initialize(VITE_GLEAP_KEY);
}

// Use plugin with optional defaults
createInertiaApp({
  title: () => '리딩수학 - 앱',
  resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) });

    dayjs.locale('ko');
    app.use(plugin).use(ZiggyVue, Ziggy).use(i18n).use(VCalendar, {});
    app.component('FontAwesomeIcon', FontAwesomeIcon);

    ApiService.init(app);
    initApexCharts(app);

    // Configure store
    setupStore(app);

    app.mount(el);
  },
});

// 뒤로가기 시에 페이지 reload 하게 끔 설정
window.addEventListener('popstate', (e) => {
  e.preventDefault();
  setTimeout(() => {
    router.reload();
  });
});
