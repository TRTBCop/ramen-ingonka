<template>
  <div id="kt_app_sidebar_logo" class="app-sidebar-logo px-6">
    <Link :href="route(`${$page.url.split('/')[1]}.dashboard`)">
      <img
        v-if="layout === 'dark-sidebar' || (themeMode === 'dark' && layout === 'light-sidebar')"
        alt="Logo"
        src="@/assets/img/brand/ico/logo.svg"
        class="h-40px app-sidebar-logo-default"
        style="filter: brightness(0) invert(1)"
      />
      <img
        v-if="layout === 'yellow-sidebar' || (themeMode === 'light' && layout === 'light-sidebar')"
        alt="Logo"
        src="@/assets/img/brand/ico/logo.svg"
        class="h-40px app-sidebar-logo-default"
      />
      <img alt="Logo" :src="getAssetPath('media/logos/default-small.svg')" class="h-20px app-sidebar-logo-minimize" />
    </Link>
    <div
      v-if="sidebarToggleDisplay"
      id="kt_app_sidebar_toggle"
      ref="toggleRef"
      class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
      data-kt-toggle="true"
      data-kt-toggle-state="active"
      data-kt-toggle-target="body"
      data-kt-toggle-name="app-sidebar-minimize"
    >
      <KTIcon icon-name="double-left" icon-class="fs-2 rotate-180" />
    </div>
  </div>
</template>

<script setup lang="ts">
  import { onMounted, ref } from 'vue';
  import { ToggleComponent } from '@/assets/ts/components';
  import { getAssetPath } from '@/admin/core/helpers/assets';
  import { layout, sidebarToggleDisplay, themeMode } from '@/admin/core/helpers/config';
  import { Link } from '@inertiajs/vue3';

  interface IProps {
    sidebarRef: HTMLElement | null;
  }

  const props = defineProps<IProps>();

  const toggleRef = ref<HTMLFormElement | null>(null);

  onMounted(() => {
    setTimeout(() => {
      const toggleObj = ToggleComponent.getInstance(toggleRef.value!) as ToggleComponent | null;

      if (toggleObj === null) {
        return;
      }

      // Add a class to prevent sidebar hover effect after toggle click
      toggleObj.on('kt.toggle.change', function () {
        // Set animation state
        props.sidebarRef?.classList.add('animating');

        // Wait till animation finishes
        setTimeout(function () {
          // Remove animation state
          props.sidebarRef?.classList.remove('animating');
        }, 300);
      });
    }, 1);
  });
</script>
