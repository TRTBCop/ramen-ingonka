<template></template>

<script setup lang="ts">
  import { onMounted } from 'vue';
  import { PropType } from 'vue/dist/vue';

  const props = defineProps({
    responseParams: {
      type: Object as PropType<any>,
      required: true,
    },
  });

  onMounted(() => {
    const obj = JSON.parse(JSON.stringify(props.responseParams));
    window.opener.postMessage({ response: obj }, '*');
    self.close();
  });
</script>
