<template>
  <div class="main__intro" style="display: flex; flex-direction: column">
    <Vue3Lottie class="ico__result" :animation-data="ImgCheck" :height="320" :width="320" :loop="true" />
    <p class="is--white is--size3 txt--center">
      {{ userSchoolInfo?.step }} {{ userSchoolInfo?.grade }}학년 {{ userSchoolInfo?.term }}학기 행성으로 <br />
      이동중이에요 …</p
    >
  </div>
</template>

<script setup lang="ts">
  import { computed } from 'vue';
  import { getUserSchoolInfo } from '@/app/core/helpers/userHelper';
  import ImgCheck from '@/assets/img/math/ufo.json';
  import { Vue3Lottie } from 'vue3-lottie';

  const userSchoolInfo = computed(() => getUserSchoolInfo());
</script>
