<template>
  <div id="wrap">
    <div class="main__intro">
      <GradeTermModifyModal :is-show="true" />
    </div>
  </div>
</template>

<script setup lang="ts">
  import GradeTermModifyModal from '@/app/components/modals/GradeTermModifyModal.vue';
</script>
