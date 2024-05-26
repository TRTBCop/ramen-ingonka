<template>
  <div class="wrap sub_page">
    <BrandHeader></BrandHeader>
    <slot></slot>
    <BrandFooter></BrandFooter>
  </div>
</template>

<script setup lang="ts">
  import BrandHeader from '@/html/components/brand/BrandHeader.vue';
  import BrandFooter from '@/html/components/brand/BrandFooter.vue';
</script>
