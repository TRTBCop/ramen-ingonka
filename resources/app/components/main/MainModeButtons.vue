<template>
  <div class="mode mode__training">
    <button
      :class="{
        on: currentMode === 'main',
      }"
      @click="goMainPage"
      >기본 모드</button
    >
    <button
      :class="{
        on: currentMode === 'free',
      }"
      @click="goFreeMainPage"
      >자유 모드</button
    >
  </div>
</template>

<script setup lang="ts">
  import { computed } from 'vue';
  import { goMainPage, goFreeMainPage } from '@/app/core/helpers/routerHelper';

  type ModeNames = 'main' | 'free';

  const currentMode = computed<ModeNames>(() => {
    if (route().current() === 'app.main.free') {
      return 'free';
    } else {
      return 'main';
    }
  });
</script>
