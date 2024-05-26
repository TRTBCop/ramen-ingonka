<template>
  <!--begin::Authentication Layout -->
  <div class="d-flex flex-column flex-lg-row flex-column-fluid">
    <!--begin::Body-->
    <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
      <!--begin::Form-->
      <div class="d-flex flex-center flex-column flex-lg-row-fluid">
        <!--begin::Wrapper-->
        <div class="w-lg-500px p-10">
          <slot />
        </div>
        <!--end::Wrapper-->
      </div>
      <!--end::Form-->

      <!--begin::Footer-->
      <div class="d-flex flex-center flex-wrap px-5">
        <!--begin::Links-->
        <!--        <div class="d-flex fw-semibold text-primary fs-base">
          <a href="#" class="px-5">Terms</a>
          <a href="#" class="px-5">Plans</a>
          <a href="#" class="px-5">Contact Us</a>
        </div>-->
        <!--end::Links-->
      </div>
      <!--end::Footer-->
    </div>
    <!--end::Body-->

    <!--begin::Aside-->
    <div
      class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2"
      :style="`background-image: url('${getAssetPath('../media/misc/auth-bg.png')}')`"
    >
      <!--begin::Content-->
      <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
        <!--begin::Image-->
        <img
          class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20"
          src="@/assets/img/brand/ico/logo.svg"
          alt=""
        />
        <!--end::Image-->
      </div>
      <!--end::Content-->
    </div>
    <!--end::Aside-->
  </div>
  <!--end::Authentication Layout -->
</template>

<script setup lang="ts">
  import { getAssetPath } from '@/admin/core/helpers/assets';
  import { onMounted } from 'vue';
  import LayoutService from '@/admin/core/services/LayoutService';
  import { useBodyStore } from '@/admin/stores/body';

  const store = useBodyStore();

  onMounted(() => {
    LayoutService.emptyElementClassesAndAttributes(document.body);

    store.addBodyClassname('app-blank');
    store.addBodyClassname('bg-body');
  });
</script>
