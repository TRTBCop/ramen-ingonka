<template>
  <span :class="`blanks__type${blankLength} ${blankTypeClass}`">
    <em v-for="blank in blankCount" :key="blank" class="blank" :class="blankColorClass">
      <slot />
    </em>
  </span>
</template>

<script setup lang="ts">
  import { computed, Prop, PropType } from 'vue';
  import AppKeypad from '@/html/components/app/AppKeypad.vue';
  import AppAnswer from '@/html/components/app/AppAnswer.vue';

  const props = defineProps({
    blankCount: {
      type: Number as PropType<number>,
      default: 1,
    },
    blankLength: {
      type: Number as PropType<number>,
      default: 1,
    },
    blankType: {
      type: Number as PropType<1 | 2 | 3>,
      default: 1,
    },
    dataKeypad: {
      type: Number as PropType<number>,
      default: 0,
    },
    dataAnswer: {
      type: Number as PropType<number>,
      default: 0,
    },
    blankColor: {
      type: String as PropType<'default' | 'on' | 'focus' | 'correct' | 'wrong' | 'wrongCheck'>,
      default: 'default',
    },
  });

  const blankTypeClass = computed(() => {
    if (props.blankType === 2) {
      return 'proper';
    } else if (props.blankType === 3) {
      return 'improper';
    }
    return '';
  });

  const blankColorClass = computed(() => {
    if (props.blankColor === 'on') {
      return 'blank__on';
    } else if (props.blankColor === 'focus') {
      return 'blank__focus';
    } else if (props.blankColor === 'correct') {
      return 'blank__correct';
    } else if (props.blankColor === 'wrong') {
      return 'blank__wrong';
    } else if (props.blankColor === 'wrongCheck') {
      return 'blank__wrong__check';
    }

    return '';
  });
</script>
