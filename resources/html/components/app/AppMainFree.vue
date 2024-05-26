<template>
  <div id="wrap" class="">
    <AppHeader />
    <div class="main main__free">
      <div class="main__head">
        <div class="mode mode__training">
          <button @click="router.get('/html/app-Main')">기본 모드</button>
          <button class="on" @click="router.get('/html/app-MainFree')">자유 모드</button>
        </div>

        <div class="mode mode__sub">
          <button>학기</button>
          <button class="on">계통</button>
          <div class="mode__switch">
            <small>모두 보기</small>
            <label class="switch">
              <input type="checkbox" checked />
              <span class="slider"></span>
            </label>
          </div>
        </div>
      </div>

      <slot></slot>

      <!--      <div class="btn__floating__left">-->
      <!--        <button class="btn&#45;&#45;gray"-->
      <!--          ><font-awesome-icon icon="fa-regular fa-arrow-left"></font-awesome-icon>뒤로 가기</button-->
      <!--        >-->
      <!--      </div>-->
    </div>
  </div>
</template>

<script setup lang="ts">
  import AppHeader from '@/html/components/app/AppHeader.vue';
  import { router } from '@inertiajs/vue3';
</script>
