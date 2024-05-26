<template>
  <AppMypage>
    <button class="btn--back" @click="router.get('/html/app-MypagePayment')">
      <font-awesome-icon icon="fa-regular fa-arrow-left" />
      뒤로가기
    </button>
    <h3>상품 정보</h3>
    <div class="input__box">
      <dl class="mypage__info">
        <dt>상품 구분</dt>
        <dd>이용권</dd>
        <dt>상품명</dt>
        <dd>1개월 이용권</dd>
        <dt>상품 금액</dt>
        <dd>60,000원</dd>
        <dt>서비스 이용 기간</dt>
        <dd>2023-11~07 ~ 2023-11~07</dd>
      </dl>
    </div>

    <h3>결제 정보</h3>
    <div class="input__box">
      <dl class="mypage__info">
        <dt>결제 구분</dt>
        <dd>202310181313064283B</dd>
        <dt>결제 수단</dt>
        <dd>신용카드</dd>
        <dt>결제 상세</dt>
        <dd>XX카드 XXXX-XXXX-XXXX-XXXX</dd>
        <dt>결제 일시</dt>
        <dd>60,000원</dd>
        <dt>결제 상태 </dt>
        <dd>결제완료</dd>
        <dt>결제 상태 </dt>
        <dd class="is--danger">전체 취소</dd>
      </dl>
    </div>

    <h3>취소 정보</h3>
    <div class="input__box">
      <dl class="mypage__info">
        <dt>취소 일시</dt>
        <dd>2023-10-18 13:13:18</dd>
        <dt>취소 금액</dt>
        <dd>60,000원 </dd>
      </dl>
    </div>
  </AppMypage>
</template>
<script setup lang="ts">
  import AppMypage from '@/html/layouts/AppMypage.vue';
  import { router } from '@inertiajs/vue3';
</script>
