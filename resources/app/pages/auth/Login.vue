<template>
  <AppLoginLayout>
    <div class="grid pv-0">
      <div class="frame col">
        <div class="container--sm">
          <h2 class="brand_logo"> LOGIN </h2>
          <!-- 카카오/네이버 로그인 -->
          <div class="login_social">
            <button class="btn--white" @click="onClickRedirect(naverUrl)">
              <img src="@/assets/img/math/ico_naver.svg" alt="" />
              <span>네이버로 로그인</span>
            </button>
            <button class="btn--white" @click="onClickRedirect(kakaoUrl)">
              <img src="@/assets/img/math/ico_kakao.svg" alt="" />
              <span>카카오톡으로 로그인</span>
            </button>
            <button class="btn--white" @click="goOtherLoginPage">
              <img src="@/assets/img/math/ico_logo.svg" alt="" />
              <span>아이디로 로그인</span>
            </button>
          </div>
        </div>
      </div>
      <div class="frame col bg--gray">
        <LoginSlider />
      </div>
    </div>
  </AppLoginLayout>
</template>

<script setup lang="ts">
  import { router } from '@inertiajs/vue3';
  import AppLoginLayout from '@/app/layouts/AppLoginLayout.vue';
  import LoginSlider from '@/app/components/login/LoginSlider.vue';
  import { goOtherLoginPage } from '@/app/core/helpers/routerHelper';

  const naverUrl = route('auth.social.login', 'naver');
  const kakaoUrl = route('auth.social.login', 'kakao');

  const onClickRedirect = (url: string) => {
    window.location.href = url;
  };
</script>

<style>
  .swiper-pagination-horizontal {
    bottom: 0 !important;
  }
</style>
