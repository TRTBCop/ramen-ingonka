<script setup lang="ts">
  import { computed } from 'vue';
  import { useThemeStore } from '@/admin/stores/theme';
  import { useConfigStore } from '@/admin/stores/config';
  import { Link } from '@inertiajs/vue3';

  const storeTheme = useThemeStore();
  const storeConfig = useConfigStore();

  const themeMode = computed(() => {
    return storeTheme.mode;
  });

  const setMode = (mode: 'dark' | 'light' | 'system') => {
    let configMode = mode;

    storeConfig.setLayoutConfigProperty('general.mode', configMode);

    storeTheme.setThemeMode(configMode);
  };
</script>

<template>
  <!--begin::Menu-->
  <div
    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-muted menu-active-bg menu-state-primary fw-semibold py-4 fs-base w-175px"
    data-kt-menu="true"
    data-kt-element="theme-mode-menu"
  >
    <!--begin::Menu item-->
    <div class="menu-item px-3 my-0">
      <a :class="{ active: themeMode === 'light' }" class="menu-link px-3 py-2" @click="setMode('light')">
        <span class="menu-icon" data-kt-element="icon">
          <KTIcon icon-name="night-day" icon-class="fs-2" />
        </span>
        <span class="menu-title">Light</span>
      </a>
    </div>
    <!--end::Menu item-->
    <!--begin::Menu item-->
    <div class="menu-item px-3 my-0">
      <a :class="{ active: themeMode === 'dark' }" class="menu-link px-3 py-2" @click="setMode('dark')">
        <span class="menu-icon" data-kt-element="icon">
          <KTIcon icon-name="moon" icon-class="fs-2" />
        </span>
        <span class="menu-title">Dark</span>
      </a>
    </div>
    <!--end::Menu item-->
    <!--begin::Menu item-->
    <div class="menu-item px-3 my-0">
      <a :class="{ active: themeMode === 'system' }" class="menu-link px-3 py-2" @click="setMode('system')">
        <span class="menu-icon" data-kt-element="icon">
          <KTIcon icon-name="screen" icon-class="fs-2" />
        </span>
        <span class="menu-title">System</span>
      </a>
    </div>
    <!--end::Menu item-->
  </div>
  <!--end::Menu-->
</template>
