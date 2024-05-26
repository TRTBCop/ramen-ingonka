<template>
  <AppMypage>
    <div class="ticket">
      <div class="payment__none">
        <p>이용권 정보가 없습니다.</p>
      </div>
      <h4>2023년10월 19일</h4>
      <div class="input__box">
        <div class="ticket__title" @click="router.get('/html/app-MypagePaymentDetail')">
          <strong>12개월 이용권<small class="badge--gray is--white">전체 취소</small></strong>
          <span>2023년 10월 18일 ~ 2024년 10월 18일</span>
        </div>
        <button>
          <font-awesome-icon icon="fa-regular fa-arrow-right"></font-awesome-icon>
        </button>
      </div>

      <h4>2023년10월 19일</h4>
      <div class="input__box input__box__current">
        <div class="ticket__title" @click="router.get('/html/app-MypagePaymentDetail')">
          <strong>12개월 이용권<small class="badge--primary--alpha">진행중</small></strong>
          <span>2023년 10월 18일 ~ 2024년 10월 18일</span>
        </div>
        <button>
          <font-awesome-icon icon="fa-regular fa-arrow-right"></font-awesome-icon>
        </button>
      </div>

      <h4>2023년10월 19일</h4>
      <div class="input__box">
        <div class="ticket__title" @click="router.get('/html/app-MypagePaymentDetail')">
          <strong>12개월 이용권</strong>
          <span>2023년 10월 18일 ~ 2024년 10월 18일</span>
        </div>
        <button>
          <font-awesome-icon icon="fa-regular fa-arrow-right"></font-awesome-icon>
        </button>
      </div>
      <div class="pagination">
        <a class="prev disabled" href="javascript:;">
          <i class="fa-regular fa-chevron-left"></i>
        </a>
        <a class="currect" href="javascript:;">1</a>
        <a href="javascript:;">2</a>
        <a href="javascript:;">3</a>
        <a class="next" href="javascript:;">
          <i class="fa-regular fa-chevron-right"></i>
        </a>
      </div>
    </div>
  </AppMypage>
</template>
<script setup lang="ts">
  import AppMypage from '@/html/layouts/AppMypage.vue';
  import { router } from '@inertiajs/vue3';
</script>
