<template>
  <div id="wrap" class="pretest_wrap">
    <AppTestHeader />
    <div class="training training__theme theme--pretest">
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
  import AppTestHeader from '@/app/components/headers/AppTestHeader.vue';
</script>
