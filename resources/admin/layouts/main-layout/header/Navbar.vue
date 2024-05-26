<template>
  <div class="app-navbar flex-shrink-0">
    <div class="app-navbar-item ms-1 ms-md-3">
      <a
        class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px"
        data-kt-menu-trigger="{default:'click', lg: 'hover'}"
        data-kt-menu-attach="parent"
        data-kt-menu-placement="bottom-end"
      >
        <KTIcon v-if="themeMode === 'light'" icon-name="night-day" icon-class="theme-light-show fs-2 fs-md-1" />
        <KTIcon v-else icon-name="moon" icon-class="theme-dark-show fs-2 fs-md-1" />
      </a>
      <KTThemeModeSwitcher />
    </div>
    <div id="kt_header_user_menu_toggle" class="app-navbar-item ms-1 ms-md-3">
      <div
        class="cursor-pointer symbol symbol-30px symbol-md-40px"
        data-kt-menu-trigger="click"
        data-kt-menu-attach="parent"
        data-kt-menu-placement="bottom-end"
      >
        <img alt="avatar" :src="$page.props.auth.user.avatar" />
      </div>
      <KTUserMenu />
    </div>
    <div v-tooltip class="app-navbar-item d-lg-none ms-2 me-n3" title="Show header menu">
      <div
        id="kt_app_header_menu_toggle"
        class="btn btn-icon btn-active-color-primary w-30px h-30px w-md-35px h-md-35px"
      >
        <KTIcon icon-name="text-align-left" icon-class="fs-2 fs-md-1" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { computed } from 'vue';
  import KTThemeModeSwitcher from '@/admin/layouts/main-layout/theme-mode/ThemeModeSwitcher.vue';
  import KTUserMenu from '@/admin/layouts/main-layout/menus/UserAccountMenu.vue';
  import { ThemeModeComponent } from '@/assets/ts/layout';
  import { useThemeStore } from '@/admin/stores/theme';

  const store = useThemeStore();

  const themeMode = computed(() => {
    if (store.mode === 'system') {
      return ThemeModeComponent.getSystemMode();
    }
    return store.mode;
  });
</script>
