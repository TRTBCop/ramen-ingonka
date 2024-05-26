<template>
  <div id="wrap" class="">
    <AppHeader />
    <div class="main main_training theme--5-1" :class="themeTypeClass">
      <slot></slot>
      <div class="frame__bottom">
        <button class="btn--gray"
          ><font-awesome-icon icon="fa-regular fa-arrow-left"></font-awesome-icon>이전 훈련</button
        >
        <button class="btn--gray"
          ><font-awesome-icon icon="fa-regular fa-arrow-right"></font-awesome-icon>다음 훈련</button
        >
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import AppHeader from '@/html/components/app/AppHeader.vue';
  import { computed, PropType } from 'vue';
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

  const props = defineProps({
    themeShow: {
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
    }
    return '';
  });
</script>
