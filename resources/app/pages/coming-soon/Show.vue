<template>
  <AppLoginLayout>
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
      <button class="btn--sub" @click="goBack">이전 페이지로</button>
    </div>
  </AppLoginLayout>
</template>

<script setup lang="ts">
  import AppLoginLayout from '@/app/layouts/AppLoginLayout.vue';

  function goBack() {
    window.history.back();
  }
</script>
