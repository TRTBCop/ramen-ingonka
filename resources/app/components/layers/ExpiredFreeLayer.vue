<template>
  <div class="layer ly__benefit">
    <div class="inner">
      <div class="layer__head">
        <h2>무료체험이 종료되었습니다!</h2>
      </div>
      <div class="layer__body">
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
      </div>
      <p class="mt-1">리딩수학의 더 많은 서비스를 이용해보세요!</p>
      <div class="btns btns--center">
        <button class="btn--brand" @click="goPaymentPage">이용권 구매하기</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { goPaymentPage } from '@/app/core/helpers/routerHelper';
</script>
