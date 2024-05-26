<template>
  <AppLayout>
    <template v-if="paymentSuccess">
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
              <dl v-if="paymentInfo">
                <dt>상품명</dt>
                <dd>{{ paymentInfo.name }} 이용권</dd>
                <dt>상품 금액</dt>
                <dd>{{ numberWithCommas(paymentInfo.amount.sale) }}원</dd>
                <dt>이용 기간</dt>
                <dd
                  >{{ dayjs().format('YYYY-MM-DD') }} ~
                  {{ dayjs().add(paymentInfo.day, 'day').format('YYYY-MM-DD') }}</dd
                >
              </dl>

              <div class="btns pv-0">
                <button class="btn--sub" @click="goMyPaymentPage">결제 정보 보기</button>
                <button class="btn--primary" @click="goMainPage">홈으로</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
    <template v-else>
      <div>결제 실패</div>
    </template>
  </AppLayout>
</template>

<script setup lang="ts">
  import { onMounted, ref, computed } from 'vue';
  import AppLayout from '@/app/layouts/AppLayout.vue';
  import { PageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import { isNil } from 'lodash';
  import { dayjs } from 'element-plus';
  import ImgCheck from '@/assets/img/math/check.json';
  import { Vue3Lottie } from 'vue3-lottie';
  import { numberWithCommas } from '@/app/core/helpers/formattingHelper';
  import { goMainPage, goMyPaymentPage } from '@/app/core/helpers/routerHelper';

  interface Page extends PageProps {
    params?: {
      mchtTrdNo: string;
      mchtParam: string;
      method: string;
      outRsltCd: string;
      outRsltMsg: string;
      outStatCd: string;
      trdAmt: string;
      trdNo: string;
      vtlAcntNo?: string;
      expireDt?: string;
      fnCd?: string;
      fnNm?: string;
    };
  }

  const pageData = usePage<Page>();

  const paymentSuccess = ref(false);

  const paymentInfo = computed(() => {
    const jsonMchtParam = pageData.props.params?.mchtParam;

    if (isNil(jsonMchtParam)) return null;

    const mchtParam: {
      model_id: number;
      model: string;
      product_code: string;
    } = JSON.parse(jsonMchtParam);

    return pageData.props.config.payment.products[mchtParam.product_code];
  });

  onMounted(() => {
    if (pageData.props.params) {
      paymentSuccess.value = ['0021', '0051'].includes(pageData.props.params?.outStatCd);
    } else {
      paymentSuccess.value = false;
    }
  });
</script>
