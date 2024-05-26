<template>
  <div class="login_wrap">
    <header>
      <a href="/html/brand-Index" class="logo col" title="리딩수학">
        <img src="@/assets/img/math/logo.svg" alt="" />
      </a>
    </header>
    <div class="coming_soon_page">
      <h3>페이지가 준비 중이에요!</h3>
      <p
        >조금만 기다려주세요, <br />
        더 좋아진 모습으로 빠르게 돌아올게요!</p
      >
      <h1>
        COMING <br />SOON
        <img src="@/assets/img/brand/bg/coming_soon.svg" alt="" />
      </h1>
      <button class="btn--sub">이전 페이지로</button>
    </div>
  </div>
</template>

<script setup lang="ts">
  document.querySelector('meta[name=viewport]')?.setAttribute('content', 'width=device-width,initial-scale=1');
</script>
