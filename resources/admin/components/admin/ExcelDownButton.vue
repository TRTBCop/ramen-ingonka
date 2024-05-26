<template>
  <a :href="href" class="d-flex align-items-center btn btn-light-primary">
    <KTIcon icon-name="folder-down" icon-class="fs-2" />
    엑셀 다운로드
  </a>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';

  defineProps({
    href: {
      type: String as PropType<string>,
      default: '',
    },
  });
</script>
