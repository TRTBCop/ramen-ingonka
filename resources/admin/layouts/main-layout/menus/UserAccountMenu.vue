<script setup lang="ts">
  import { useI18n } from 'vue-i18n';
  import { getAssetPath } from '@/admin/core/helpers/assets';
  import { computed } from 'vue';
  import { useAuthStore } from '@/admin/stores/auth';
  import { Link } from '@inertiajs/vue3';

  const i18n = useI18n();
  const store = useAuthStore();

  i18n.locale.value = localStorage.getItem('lang') ? (localStorage.getItem('lang') as string) : 'en';

  const countries = {
    en: {
      flag: getAssetPath('media/flags/united-states.svg'),
      name: 'English',
    },
    es: {
      flag: getAssetPath('media/flags/spain.svg'),
      name: 'Spanish',
    },
    de: {
      flag: getAssetPath('media/flags/germany.svg'),
      name: 'German',
    },
    ja: {
      flag: getAssetPath('media/flags/japan.svg'),
      name: 'Japanese',
    },
    fr: {
      flag: getAssetPath('media/flags/france.svg'),
      name: 'French',
    },
  };

  const signOut = () => {
    store.logout();
  };

  const setLang = (lang: string) => {
    localStorage.setItem('lang', lang);
    i18n.locale.value = lang;
  };

  const currentLanguage = computed(() => {
    return i18n.locale.value;
  });

  const currentLangugeLocale = computed(() => {
    return countries[i18n.locale.value as keyof typeof countries];
  });
</script>

<template>
  <!--begin::Menu-->
  <div
    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semobold py-4 fs-6 w-275px"
    data-kt-menu="true"
  >
    <!--begin::Menu item-->
    <div class="menu-item px-3">
      <div class="menu-content d-flex align-items-center px-3">
        <!--begin::Avatar-->
        <div class="symbol symbol-50px me-5">
          <img alt="avatar" :src="$page.props.auth.user.avatar" />
        </div>
        <!--end::Avatar-->

        <!--begin::Username-->
        <div class="d-flex flex-column">
          <div class="fw-bold d-flex align-items-center fs-5">
            {{ $page.props.auth.user.name }}
            <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2"> </span>
          </div>
          <a href="#" class="fw-semobold text-muted text-hover-primary fs-7">{{ $page.props.auth.user.access_id }}</a>
        </div>
        <!--end::Username-->
      </div>
    </div>
    <!--end::Menu item-->

    <!--begin::Menu separator-->
    <div class="separator my-2"></div>
    <!--end::Menu separator-->

    <!--begin::Menu item-->
    <div class="menu-item px-5">
      <Link :href="route('admin.profile.show')" class="menu-link px-5"> 내 개인정보 </Link>
    </div>
    <!--end::Menu item-->

    <!--begin::Menu separator-->
    <div class="separator my-2"></div>
    <!--end::Menu separator-->

    <!--begin::Menu item-->
    <div class="menu-item px-5">
      <Link :href="route('admin.logout')" method="post" as="button" class="btn w-100 menu-link px-5">로그아웃</Link>
    </div>
    <!--end::Menu item-->
  </div>
  <!--end::Menu-->
</template>
