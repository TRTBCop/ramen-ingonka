<template>
  <button :class="`btn--${color}`" :disabled="disabled" @click="emits('click')">
    <span v-if="loading"> <i class="fa-regular fa-spinner fa-spin"></i></span>
    <slot />
  </button>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';

  defineProps({
    text: {
      type: String as PropType<string>,
      default: '',
    },
    disabled: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    color: {
      type: String as PropType<'gray' | 'primary' | 'brand'>,
      default: 'gray',
    },
    loading: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  const emits = defineEmits(['click']);
</script>
