<template>
  <AppMain>
    <div class="main__body">
      <div class="main__hills main__objects">
        <div class="main__hills__inner">
          <div class="object--2"><i i style="--w: 0%"></i></div>
          <div class="object--1"><i i style="--w: 100%"></i></div>
          <div class="object--3"><i i style="--w: 25%"></i></div>
        </div>
      </div>
      <div class="main__sky main__objects">
        <div class="main__sky__inner">
          <div class="object--6"><i style="--w: 25%"></i></div>
          <div class="object--4"><i i style="--w: 25%"></i></div>
          <div class="object--5"><i i style="--w: 25%"></i></div>
        </div>
      </div>
      <a class="object--alien" href="javascript:" @click="router.get('/html/app-MainTraining')">
        <p class="bubble"
          >각, 직각의 개념 훈련을 시작해 보세요!각, 직각의 개념 훈련을 시작해 보세요!각, 직각의 개념 훈련을 시작해
          보세요!</p
        >
        <i class="alien"></i>
      </a>
    </div>

    <div class="btn__floating__right">
      <button class="btn--gray">
        <font-awesome-icon icon="fa-regular fa-arrow-right"></font-awesome-icon>다음 행성으로
      </button>
    </div>

    <!--    <div class="btns">-->
    <!--      <button class="btn&#45;&#45;brand">학습하러 가기</button>-->
    <!--      <button class="btn&#45;&#45;brand">다음 행성으로</button>-->
    <!--    </div>-->
  </AppMain>
</template>
<script setup lang="ts">
  import AppMain from '@/html/components/app/AppMain.vue';
  import { router } from '@inertiajs/vue3';
</script>
