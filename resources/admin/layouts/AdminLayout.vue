<template>
  <div id="kt_app_root" class="d-flex flex-column flex-root app-root">
    <div id="kt_app_page" class="app-page flex-column flex-column-fluid">
      <KTHeader />
      <div id="kt_app_wrapper" class="app-wrapper flex-column flex-row-fluid">
        <KTSidebar :menu-config="AdminMenuConfig" />
        <div id="kt_app_main" class="app-main flex-column flex-row-fluid">
          <div class="d-flex flex-column flex-column-fluid">
            <KTToolbar />
            <KTContent>
              <slot />
            </KTContent>
          </div>
          <KTFooter />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { nextTick, onBeforeMount, onMounted, watch } from 'vue';

  import KTContent from '@/admin/layouts/main-layout/content/Content.vue';
  import KTHeader from '@/admin/layouts/main-layout/header/Header.vue';
  import KTSidebar from '@/admin/layouts/main-layout/sidebar/Sidebar.vue';
  import KTFooter from '@/admin/layouts/main-layout/footer/Footer.vue';
  import KTToolbar from '@/admin/layouts/main-layout/toolbar/Toolbar.vue';
  import { reinitializeComponents } from '@/admin/core/plugins/keenthemes';
  import LayoutService from '@/admin/core/services/LayoutService';
  import { ElNotification } from 'element-plus';
  import { usePage } from '@inertiajs/vue3';
  import { AdminMenuConfig } from '@/admin/core/config/MainMenuConfig';
  import { useConfigStore } from '@/admin/stores/config';
  import { reduce, isEmpty } from 'lodash';

  const configStore = useConfigStore();

  onBeforeMount(() => {
    LayoutService.init();
  });

  const notification = () => {
    ElNotification({
      title: usePage().props.flash.message[0],
      message: usePage().props.flash.message[1],
      type: usePage().props.flash.message[0],
    });
  };

  const errorNotification = () => {
    ElNotification({
      dangerouslyUseHTMLString: true,
      title: usePage().props.flash.message[0],
      message: reduce(usePage().props.errors, (prev, current) => `${prev}${current}<br>`, ''),
      type: usePage().props.flash.message[0],
    });
  };

  configStore.setLayoutConfigProperty('general.layout', 'dark-sidebar');

  onMounted(() => {
    nextTick(() => {
      reinitializeComponents();

      if (!isEmpty(usePage().props.errors)) {
        errorNotification();
      } else if (usePage().props.flash.message) {
        notification();
      }
    });
  });

  watch(
    () => usePage().props.flash.message,
    () => {
      if (!isEmpty(usePage().props.errors)) {
        errorNotification();
      } else {
        notification();
      }
    },
  );
</script>

<style lang="scss">
  .override-styles {
    z-index: 99999 !important;
    pointer-events: initial;
  }

  .el-select {
    width: 100%;
  }

  .el-date-editor.el-input,
  .el-date-editor.el-input__inner {
    width: 100%;
  }

  .dataTables_wrapper .table {
    min-width: 800px;
  }
</style>
