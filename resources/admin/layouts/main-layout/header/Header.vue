<template>
  <div v-if="headerDisplay" id="kt_app_header" class="app-header">
    <div
      class="app-container d-flex align-items-stretch justify-content-between"
      :class="{
        'container-fluid': headerWidthFluid,
        'container-xxl': !headerWidthFluid,
      }"
    >
      <div
        v-if="layout === 'light-header' || layout === 'dark-header'"
        class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15"
      >
        <Link :href="route('admin.dashboard')">
          <img
            v-if="themeMode === 'light' && layout === 'light-header'"
            alt="Logo"
            :src="getAssetPath('media/logos/default.svg')"
            class="h-20px h-lg-30px app-sidebar-logo-default theme-light-show"
          />
          <img
            v-if="layout === 'dark-header' || (themeMode === 'dark' && layout === 'light-header')"
            alt="Logo"
            :src="getAssetPath('media/logos/default-dark.svg')"
            class="h-20px h-lg-30px app-sidebar-logo-default"
          />
        </Link>
      </div>
      <template v-else>
        <div v-tooltip class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
          <div id="kt_app_sidebar_mobile_toggle" class="btn btn-icon btn-active-color-primary w-35px h-35px">
            <KTIcon icon-name="abstract-14" icon-class="fs-2 fs-md-1" />
          </div>
        </div>
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
          <Link :href="route('admin.dashboard')" class="d-lg-none">
            <img alt="Logo" src="@/assets/img/brand/ico/logo.svg" class="h-30px" />
          </Link>
        </div>
      </template>
      <div id="kt_app_header_wrapper" class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
        <KTHeaderMenu />
        <KTHeaderNavbar />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { getAssetPath } from '@/admin/core/helpers/assets';
  import KTHeaderMenu from '@/admin/layouts/main-layout/header/menu/Menu.vue';
  import KTHeaderNavbar from '@/admin/layouts/main-layout/header/Navbar.vue';
  import { Link } from '@inertiajs/vue3';

  import { headerDisplay, headerWidthFluid, layout, themeMode } from '@/admin/core/helpers/config';
</script>
