<template>
  <AppMainFree>
    <div class="main__body training">
      <div class="lst__semester">
        <div class="semester semester--on">
          <a href="" @click="router.get('/html/app-MainFreeDetail')"> </a>
          <i class="semester__thmb semester--3-2"></i>
          <span>3학년 2학기</span>
          <div class="progress">
            <small class="is--size4">80% 진행했어요!</small>
            <progress value="50" min="0" max="100"></progress>
          </div>
        </div>
      </div>
      <div class="curriculum">
        <AppCurriculum></AppCurriculum>
      </div>
    </div>
  </AppMainFree>
</template>
<script setup lang="ts">
  import AppMainFree from '@/html/components/app/AppMainFree.vue';
  import AppCurriculum from '@/html/components/app/AppCurriculum.vue';
  import { router } from '@inertiajs/vue3';
</script>
