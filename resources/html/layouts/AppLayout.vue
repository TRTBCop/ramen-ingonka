<template>
  <div id="wrap" class="">
    <AppHeader />
    <div class="training training__theme" :class="themeTypeClass">
      <slot></slot>
      <div v-if="themeBottom" class="frame__bottom">
        <div class="character_area">
          <i class="character"></i>
          <span class="comment comment__wrong">오답입니다</span>
          <span class="comment comment__correct">정답입니다</span>
        </div>
        <div class="row">
          <button class="btn--gray">버튼</button>
          <button class="btn--primary">버튼</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import AppHeader from '@/html/components/app/AppHeader.vue';
  import { computed, PropType } from 'vue';

  const props = defineProps({
    themeShow: {
      type: Number as PropType<number>,
      default: 1,
    },

    themeBottom: {
      type: Number as PropType<number>,
      default: 1,
    },
  });

  const themeTypeClass = computed(() => {
    if (props.themeShow === 1) {
      return 'theme--textbook';
    } else if (props.themeShow === 2) {
      return 'theme--question';
    } else if (props.themeShow === 3) {
      return 'theme--descriptive';
    } else if (props.themeShow === 4) {
      return 'theme--pretestIntro';
    } else if (props.themeShow === 5) {
      return 'theme--pretest';
    }
    return '';
  });
</script>
