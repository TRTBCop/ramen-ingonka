<template>
  <FindLayout page-type="password" :page-step="4">
    <div class="box__result">
      <strong>{{ pageData.props.access_id }}</strong>
      <p>가입일: {{ pageData.props.created_date }}</p>
    </div>

    <div class="panel__btns">
      <button class="btn--brand" @click="goOtherLoginPage">로그인하러 가기</button>
    </div>
  </FindLayout>
</template>

<script setup lang="ts">
  import { PageProps } from '@/admin/types';
  import { goOtherLoginPage } from '@/app/core/helpers/routerHelper';
  import FindLayout from '@/app/layouts/FindLayout.vue';
  import { usePage } from '@inertiajs/vue3';

  interface Page extends PageProps {
    access_id: string;
    created_date: string;
    route_name: string;
  }

  const pageData = usePage<Page>();
</script>
