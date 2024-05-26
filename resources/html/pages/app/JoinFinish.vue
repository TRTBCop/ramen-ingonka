<template>
  <div class="result_wrap bg--white">
    <div class="result">
      <div class="result__title">
        <h3>김리딩(test134)님, 반가워요!</h3>
        <p>리딩수학에 가입해주셔서 감사합니다.</p>
        <Vue3Lottie class="ico__result" :animation-data="ImgCheck" :height="200" :width="200" :loop="false" />
      </div>
      <div class="slide_up">
        <span
          >2024년 3월 6일부터 2024년 3월 10일까지 <br />
          5일 동안 무료 체험을 진행할 수 있어요.</span
        >
        <ul class="benefits">
          <li>
            <strong>5일 무료 체험</strong>
            <font-awesome-icon class="ico--benefit" icon="fa-regular fa-laptop"></font-awesome-icon>
          </li>
          <li>
            <strong>소단원 1회차</strong><br /><span>훈련 3개</span>
            <font-awesome-icon class="ico--benefit" icon="fa-regular fa-scroll"></font-awesome-icon>
          </li>
          <li>
            <strong>진단평가</strong><br /><span>1학기</span>
            <font-awesome-icon class="ico--benefit" icon="fa-regular fa-school"></font-awesome-icon>
          </li>
        </ul>
        <div class="slide_up2">
          <span>개념으로 문제를 해결하는 1등들의 바른 수학 공부법, 시작해볼까요?</span>
          <button class="btn--brand mt-1">로그인 하고 무료체험 시작하기</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import ImgCheck from '@/assets/img/math/check.json';
  import { Vue3Lottie } from 'vue3-lottie';
  document.querySelector('meta[name=viewport]')?.setAttribute('content', 'width=device-width,initial-scale=1');
</script>
