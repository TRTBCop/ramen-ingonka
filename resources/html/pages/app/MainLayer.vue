<template>
  <AppMain>
    <div class="main__body">
      <div class="main__hills"></div>
      <div class="main__objects">
        <i class="object--1"></i>
        <i class="object--2"></i>
        <i class="object--3"></i>
        <i class="object--4"></i>
        <i class="object--5"></i>
        <i class="object--6"></i>
        <a href="javascript:;" @click="router.get('/html/app-MainTraining')">
          <i class="alien"></i>
          <span>행성 메인으로</span>
        </a>
      </div>
    </div>

    <div class="layer">
      <div class="inner">
        <div class="main__objects">
          <i class="object--6" style="position: initial"></i>
        </div>
        <p class="txt--center">
          ‘1단원 - 소수의 곱셈’ 을 완료했어요! <br />
          배달천재드론을 받았어요~
        </p>
        <div class="btns btns--center">
          <button class="btn--white">닫기</button>
        </div>
      </div>
    </div>
  </AppMain>
</template>
<script setup lang="ts">
  import AppMain from '@/html/components/app/AppMain.vue';
  import { router } from '@inertiajs/vue3';
</script>
