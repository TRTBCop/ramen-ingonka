<template> </template>

<script lang="ts">
  import { getAssetPath } from '@/admin/core/helpers/assets';
  import { defineComponent } from 'vue';

  export default defineComponent({
    name: 'KtMain',
    components: {},
    setup() {
      return {
        getAssetPath,
      };
    },
  });
</script>
