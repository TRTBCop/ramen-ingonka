<template>
  <ErrorLayout>
    <div class="error_wrap">
      <div class="error__text">
        <h3>{{ pageData.props.status }}</h3>
        <h4>{{ pageData.props.message }}</h4>
        <p v-html="pageData.props.description"> </p>
        <div class="btns">
          <!-- <button class="btn--sub"> <font-awesome-icon icon="fa-regular fa-arrow-left" />이전 화면 </button> -->
          <button class="btn--brand" @click="goBrandPage">
            <font-awesome-icon icon="fa-regular fa-home" />홈으로 이동
          </button>
        </div>
      </div>
    </div>
  </ErrorLayout>
</template>

<script setup lang="ts">
  import ErrorLayout from '@/app/layouts/ErrorLayout.vue';
  import { usePage } from '@inertiajs/vue3';
  import { PageProps } from '@/app/types/pageData';
  import { goBrandPage } from '@/app/core/helpers/routerHelper';

  interface Page extends PageProps {
    status: number;
    message: string;
    description: string;
  }

  const pageData = usePage<Page>();
</script>
