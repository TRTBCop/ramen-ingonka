<template>
  <AppHeader />
  <div class="board">
    <div class="hero">
      <div class="container">
        <h2>마이페이지</h2>
      </div>
    </div>
    <div class="container">
      <div class="mypage">
        <div class="grid">
          <div class="frame col--sm">
            <button><i class="profile__img profile__img--lg profile__img--type04"></i></button>
            <div class="mypage__link">
              <a class="current" href="javascript:;" @click="router.get('/html/app-Mypage')">내 정보</a>
              <a href="javascript:;" @click="router.get('/html/app-MypagePayment')">이용권 정보 </a>
            </div>
          </div>
          <div class="frame col">
            <slot></slot>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
  import AppHeader from '@/html/components/app/AppHeader.vue';
  import { router } from '@inertiajs/vue3';
</script>
