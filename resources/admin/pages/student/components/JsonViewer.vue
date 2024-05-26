<template>
  <pre>{{ JSON.stringify(props.data, null, 2) }}</pre>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';

  const props = defineProps({
    data: {
      type: Object as PropType<any>,
      default: null,
    },
  });
</script>
