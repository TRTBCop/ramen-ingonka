<template>
  <AppLoginIntroLayout>
    <h2 class="brand_logo"> LOGIN </h2>

    <!-- 카카오/네이버 로그인 -->
    <div class="login_social">
      <button class="btn--white">
        <img src="@/assets/img/math/ico_naver.svg" alt="" />
        <span>네이버로 로그인</span>
      </button>
      <button class="btn--white">
        <img src="@/assets/img/math/ico_kakao.svg" alt="" />
        <span>카카오톡으로 로그인</span>
      </button>
      <button class="btn--white" @click="router.get('/html/app-LoginReadingMath')">
        <img src="@/assets/img/math/ico_logo.svg" alt="" />
        <span>아이디로 로그인</span>
      </button>
    </div>

    <div class="login_btns_wrap">
      <a href="javascript:;" class="txt--center is--darker" @click="router.get('/html/app-LoginJoin')"
        >리딩수학이 처음이신가요? 회원가입하기</a
      >
    </div>
  </AppLoginIntroLayout>
</template>

<script setup lang="ts">
  import AppLoginIntroLayout from '@/html/layouts/AppLoginIntroLayout.vue';
  import { router } from '@inertiajs/vue3';
</script>
