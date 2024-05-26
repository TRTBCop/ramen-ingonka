<template>
  <div class="main__intro" style="display: flex; flex-direction: column">
    <Vue3Lottie class="ico__result" :animation-data="ImgCheck" :height="320" :width="320" :loop="true" />
    <p class="is--white is--size3 txt--center">초등 3학년 1학기 행성으로 <br />이동중이에요 …</p>
  </div>
</template>

<script setup lang="ts">
  import ImgCheck from '@/assets/img/math/ufo.json';
  import { Vue3Lottie } from 'vue3-lottie';
</script>
