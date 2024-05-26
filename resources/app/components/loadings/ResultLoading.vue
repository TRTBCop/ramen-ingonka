<template>
  <div class="layer ly__loading">
    <img src="@/assets/img/math/loading.png" alt="" />
    <p v-if="text">{{ text }}</p>
  </div>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';

  defineProps({
    text: {
      type: String as PropType<string>,
      default: '',
    },
  });
</script>
