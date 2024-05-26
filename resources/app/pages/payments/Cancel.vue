<template></template>

<script setup lang="ts">
  import { onMounted } from 'vue';

  onMounted(() => {
    window.opener.postMessage({ response: null }, '*');
    self.close();
  });
</script>
