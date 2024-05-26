<template>
  <MyLayout>
    <div class="ticket">
      <template v-for="payment in pageData.props.payments" :key="payment.id">
        <h4>{{ dayjs(payment.approved_at).format('YYYY년 MM월 DD일') }}</h4>
        <div
          class="input__box"
          :class="{
            input__box__current: getIsCurrentProduct(payment),
          }"
          @click="goPaymentDetailPage(payment.id)"
        >
          <div class="ticket__title">
            <strong>
              {{ getProductNameByPayment(payment) }} 이용권<small
                v-if="getIsCurrentProduct(payment)"
                class="badge--primary--alpha"
                >진행중</small
              ><small v-else-if="payment.status === PaymentStatusModel.CANCEL" class="badge--gray is--white"
                >전체 취소</small
              ><small v-else-if="payment.status === PaymentStatusModel.PARTIAL_CANCEL" class="badge--gray is--white"
                >부분 취소</small
              >
            </strong>
            <span
              >{{ dayjs(getProductServiceDateByPayment(payment).start).format('YYYY년 MM월 DD일') }} ~
              {{ dayjs(getProductServiceDateByPayment(payment).end).format('YYYY년 MM월 DD일') }}</span
            >
          </div>
          <button>
            <font-awesome-icon icon="fa-regular fa-arrow-right"></font-awesome-icon>
          </button>
        </div>
      </template>

      <!-- todo: 페이지 네이션 구현 -->
      <div class="pagination">
        <a class="prev disabled" href="javascript:;">
          <FontAwesomeIcon icon="fa-regular fa-chevron-left" />
        </a>
        <a class="currect" href="javascript:;">1</a>
        <a class="next disabled" href="javascript:;">
          <FontAwesomeIcon icon="fa-regular fa-chevron-right" />
        </a>
      </div>
    </div>
  </MyLayout>
</template>

<script setup lang="ts">
  import MyLayout from '@/app/layouts/MyLayout.vue';
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import { usePage } from '@inertiajs/vue3';
  import { PageProps } from '@/app/types/pageData';
  import { Payment, PaymentProduct } from '@/app/api/model/payments';
  import { dayjs } from 'element-plus';
  import { PaymentStatusModel } from '@/app/api/model/payments';
  import { goPaymentDetailPage } from '@/app/core/helpers/routerHelper';

  interface Page extends PageProps {
    products: { [key in string]: PaymentProduct };
    payments: Payment[];
  }

  const pageData = usePage<Page>();

  function getProductNameByPayment(payment: Payment) {
    return payment.extra.pg_result[0].pmtprdNm;
  }

  function getProductServiceDateByPayment(payment: Payment) {
    return payment.extra.service_date;
  }

  function getIsCurrentProduct(payment: Payment) {
    const serviceDate = getProductServiceDateByPayment(payment);
    const start = dayjs(serviceDate.start);
    const end = dayjs(serviceDate.end);
    const target = dayjs();
    return target.isAfter(start) && target.isBefore(end);
  }
</script>
