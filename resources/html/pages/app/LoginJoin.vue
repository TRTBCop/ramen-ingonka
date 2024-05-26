<template>
  <AppLoginIntroLayout>
    <h2 class="brand_logo"> SIGN UP </h2>

    <!-- 카카오/네이버 로그인 -->
    <div class="login_social">
      <button class="btn--white">
        <img src="../../../assets/img/math/ico_naver.svg" alt="" />
        <span>네이버로 시작하기</span>
      </button>
      <button class="btn--white">
        <img src="../../../assets/img/math/ico_kakao.svg" alt="" />
        <span>카카오톡으로 시작하기</span>
      </button>
      <button class="btn--white" @click="router.get('/html/app-Join')">
        <img src="../../../assets/img/math/ico_logo.svg" alt="" />
        <span>아이디로 시작하기</span>
      </button>
    </div>

    <div class="login_btns_wrap">
      <a href="javascript:;" class=" " @click="router.get('/html/app-Login')">
        <i class="fa-regular fa-arrow-left"></i>뒤로 가기
      </a>
    </div>
  </AppLoginIntroLayout>
</template>

<script setup lang="ts">
  import AppLoginIntroLayout from '@/html/layouts/AppLoginIntroLayout.vue';
  import { router } from '@inertiajs/vue3';
</script>
