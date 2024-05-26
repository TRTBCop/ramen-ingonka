<template>
  <div class="mt-5">
    <el-button type="info" @click="goPreviewPage">미리보기</el-button>
  </div>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';

  const props = defineProps({
    curriculum: {
      type: Number as PropType<number>,
      default: 0,
    },
    stage: {
      type: Number as PropType<1 | 2 | 3>,
      default: 1,
    },
  });

  function goPreviewPage() {
    window.open(route('app.training.preview.show', { curriculum: props.curriculum, stage: props.stage }));
  }
</script>
