<template>
  <AppHeader />
  <div class="board">
    <div class="container--sm">
      <div class="payment__finish">
        <div class="payment__title">
          <div>
            <p>이용권 구매가 완료되었습니다.</p>
            <em>리딩수학과 함께 재밌게 공부해요!</em>
          </div>
          <Vue3Lottie class="ico__result" :animation-data="ImgCheck" :height="200" :width="200" :loop="false" />
        </div>

        <div class="slide_up">
          <dl>
            <dt>상품명</dt>
            <dd>1개월 이용권</dd>
            <dt>상품 금액</dt>
            <dd>60,000원</dd>
            <dt>이용 기간</dt>
            <dd>2023-11-07 ~ 2023-12-07</dd>
          </dl>

          <div class="btns pv-0">
            <button class="btn--sub">결제 정보 보기</button>
            <button class="btn--brand">홈으로</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
  import ImgCheck from '@/assets/img/math/check.json';
  import AppHeader from '@/html/components/app/AppHeader.vue';
  import { Vue3Lottie } from 'vue3-lottie';
</script>
