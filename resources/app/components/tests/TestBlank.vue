<template>
  <em
    class="blank"
    :class="{
      blank__focus: isFocus,
      blank__on: value,
    }"
    @click="handleOnClick"
    v-html="value || placeHolder"
  ></em>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';

  defineProps({
    isFocus: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    placeHolder: {
      type: String as PropType<string>,
      default: '',
    },
    value: {
      type: [String, Number] as PropType<string | number | null | undefined>,
      default: '',
    },
  });

  const emits = defineEmits<{
    (e: 'onClick', ...args: unknown[]): void;
  }>();

  function handleOnClick() {
    emits('onClick');
  }
</script>
