<template>
  <!--begin::sidebar-->
  <div
    v-if="displaySidebar"
    id="kt_app_sidebar"
    ref="sidebarRef"
    class="app-sidebar flex-column"
    data-kt-drawer="true"
    data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}"
    data-kt-drawer-overlay="true"
    data-kt-drawer-width="225px"
    data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle"
  >
    <KTSidebarLogo :sidebar-ref="sidebarRef"></KTSidebarLogo>
    <KTSidebarMenu :menu-config="menuConfig"></KTSidebarMenu>
    <KTSidebarFooter></KTSidebarFooter>
  </div>
  <!--end::sidebar-->
</template>

<script lang="ts">
  import { defineComponent, ref, PropType } from 'vue';
  import { displaySidebar } from '@/admin/core/helpers/config';
  import KTSidebarLogo from '@/admin/layouts/main-layout/sidebar/SidebarLogo.vue';
  import KTSidebarMenu from '@/admin/layouts/main-layout/sidebar/SidebarMenu.vue';
  import KTSidebarFooter from '@/admin/layouts/main-layout/sidebar/SidebarFooter.vue';
  import { MenuItem } from '@/admin/core/config/MainMenuConfig';

  export default defineComponent({
    name: 'ThemeSidebar',
    components: {
      KTSidebarLogo,
      KTSidebarMenu,
      KTSidebarFooter,
    },
    props: {
      menuConfig: {
        type: Array as PropType<MenuItem[]>,
        default: null,
      },
    },
    setup() {
      const sidebarRef = ref<HTMLFormElement | null>(null);

      return {
        displaySidebar,
        sidebarRef,
      };
    },
  });
</script>
