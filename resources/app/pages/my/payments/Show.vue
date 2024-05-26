<template>
  <MyLayout>
    <button class="btn--back" @click="goMyPaymentPage">
      <font-awesome-icon icon="fa-regular fa-arrow-left" />
      뒤로가기
    </button>
    <h3>상품 정보</h3>
    <div class="input__box">
      <dl class="mypage__info">
        <dt>상품 구분</dt>
        <dd>이용권</dd>
        <dt>상품명</dt>
        <dd>{{ getProductNameByPayment(payment) }} 이용권</dd>
        <dt>상품 금액</dt>
        <dd>{{ numberWithCommas(payment.amount) }}원</dd>
        <dt>서비스 이용 기간</dt>
        <dd>{{ getProductServiceDateByPayment(payment).start }} ~ {{ getProductServiceDateByPayment(payment).end }}</dd>
      </dl>
    </div>

    <h3>결제 정보</h3>
    <div class="input__box">
      <dl class="mypage__info">
        <dt>결제 번호</dt>
        <dd>{{ payment.trd_no }}</dd>
        <dt>결제 수단</dt>
        <dd>{{ payment.txt_method }}</dd>
        <dt>결제 상세</dt>
        <dd>{{ payment.extra.pg_result[0].cardNm }} {{ payment.extra.pg_result[0].cardNo }}</dd>
        <dt>결제 일시</dt>
        <dd>{{ numberWithCommas(Number(payment.extra.pg_result[0].trdAmt)) }}원</dd>
        <dt>결제 상태 </dt>
        <dd
          :class="{
            'is--danger':
              payment.status === PaymentStatusModel.CANCEL || payment.status === PaymentStatusModel.PARTIAL_CANCEL,
          }"
          >{{ payment.txt_status }}</dd
        >
      </dl>
    </div>

    <template
      v-if="payment.status === PaymentStatusModel.CANCEL || payment.status === PaymentStatusModel.PARTIAL_CANCEL"
    >
      <h3>취소 정보</h3>
      <div class="input__box">
        <dl class="mypage__info">
          <dt>취소 일시</dt>
          <dd>{{ dayjs(payment.canceled_at).format('YYYY-MM-DD H:mm:ss') }}</dd>
          <dt>취소 금액</dt>
          <dd>{{ numberWithCommas(payment.cancel_amount) }}원</dd>
        </dl>
      </div>
    </template>
  </MyLayout>
</template>

<script setup lang="ts">
  import { computed } from 'vue';
  import { Payment, PaymentStatusModel } from '@/app/api/model/payments';
  import MyLayout from '@/app/layouts/MyLayout.vue';
  import { PageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import { dayjs } from 'element-plus';
  import { numberWithCommas } from '@/app/core/helpers/formattingHelper';
  import { goMyPaymentPage } from '@/app/core/helpers/routerHelper';

  interface Page extends PageProps {
    payment: Payment;
  }

  const pageData = usePage<Page>();

  const payment = computed(() => pageData.props.payment);

  function getProductNameByPayment(payment: Payment) {
    return payment.extra.pg_result[0].pmtprdNm;
  }

  function getProductServiceDateByPayment(payment: Payment) {
    return payment.extra.service_date;
  }
</script>
