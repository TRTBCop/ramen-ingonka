<template>
  <AppHeader />
  <div class="main theme--6-1">
    <div class="main__head">
      <div class="mode mode__training">
        <button class="on" @click="router.get('/html/app-Main')">기본 모드</button>
        <button @click="router.get('/html/app-MainFree')">자유 모드</button>
      </div>

      <div class="mode mode__sub">
        <strong>3학년 1학기 행성</strong>
      </div>
    </div>

    <slot></slot>
  </div>
</template>

<script setup lang="ts">
  import AppHeader from '@/html/components/app/AppHeader.vue';
  import { router } from '@inertiajs/vue3';
</script>
