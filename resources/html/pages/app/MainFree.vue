<template>
  <AppMainFree>
    <div class="main__body training">
      <div class="training__body">
        <!-- [D] 계통도 행성 순서 / 1.수와연산 2.규칙성 3.도형 4.측정 5.자료와 가능성 -->
        <!--                  <div class="lst__semester">-->
        <!--                    <a href="javascript:;" class="semester">-->
        <!--                      <i class="semester__thmb semester&#45;&#45;1"></i>-->
        <!--                      <span>3학년 1학기</span>-->
        <!--                    </a>-->
        <!--                    <a href="javascript:;" class="semester semester&#45;&#45;on" @click="router.get('/html/app-MainFreeDetail')">-->
        <!--                      <i class="semester__thmb semester&#45;&#45;5"></i>-->
        <!--                      <span>3학년 2학기</span>-->
        <!--                    </a>-->
        <!--                    <a href="javascript:;" class="semester">-->
        <!--                      <i class="semester__thmb semester&#45;&#45;3"></i>-->
        <!--                      <span>4학년 1학기</span>-->
        <!--                    </a>-->
        <!--                  </div>-->

        <div class="lst__semester">
          <a href="javascript:;" class="semester">
            <i class="semester__thmb semester--3-1"></i>
            <span>3학년 1학기</span>
          </a>
          <a href="javascript:;" class="semester semester--on" @click="router.get('/html/app-MainFreeDetail')">
            <i class="semester__thmb semester--3-2"></i>
            <span>3학년 2학기</span>
            <div class="progress">
              <small class="is--size4">80% 진행했어요!</small>
              <progress value="50" min="0" max="100"></progress>
            </div>
          </a>
          <a href="javascript:;" class="semester">
            <i class="semester__thmb semester--5-2"></i>
            <span>4학년 1학기</span>
          </a>
        </div>

        <div class="slide">
          <button class="prev"><font-awesome-icon icon="fa-regular fa-chevron-left"></font-awesome-icon></button>
          <button class="next"><font-awesome-icon icon="fa-regular fa-chevron-right"></font-awesome-icon></button>
        </div>
      </div>
    </div>
  </AppMainFree>
</template>
<script setup lang="ts">
  import AppMainFree from '@/html/components/app/AppMainFree.vue';
  import { router } from '@inertiajs/vue3';
</script>
