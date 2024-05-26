<template>
  <BoardLayout page-name="이용권 구매">
    <div class="payment">
      <div class="grid">
        <div class="col">
          <h3>이용권 선택</h3>
          <ul class="payment__choice">
            <label v-for="(item, key) in props.products" :key="key" :for="`products-${item.month}`">
              <li
                :class="{
                  payment__choice__on: selectedItem == item,
                }"
              >
                <div class="info">
                  <div class="title">
                    <p>
                      <strong>{{ item.name }}</strong>
                      이용권 ({{ item.day }}일)
                      <small v-if="item.amount.origin !== item.amount.sale" class="badge--primary--alpha"
                        ><font-awesome-icon icon="fa-regular fa-arrow-down"></font-awesome-icon
                        >{{ calculateDiscountRate(item.amount.origin, item.amount.sale) }}%</small
                      >
                    </p></div
                  >

                  <div class="price">
                    <p v-if="item.amount.origin !== item.amount.sale">{{ numberWithCommas(item.amount.origin) }}원</p>
                    <strong class="price"
                      >{{ numberWithCommas(item.amount.sale) }}원
                      <span v-if="item.month !== 1"
                        >({{ numberWithCommas(Math.round(item.amount?.sale / item.month)) }}원/월)</span
                      ></strong
                    >
                  </div>
                </div>
                <div class="checkbox_wrap">
                  <input
                    :id="`products-${item.month}`"
                    type="radio"
                    name="products"
                    :value="item.amount.sale"
                    :data-code="key"
                    :data-name="item.name"
                    @click="setSelectedItem(item)"
                  />
                </div>
              </li>
            </label>
          </ul>

          <h3>결제 방법 선택</h3>
          <ul class="payment__choice">
            <label v-for="paymentType in paymentTypeList" :key="paymentType.id" :for="paymentType.id">
              <li :class="{ payment__choice__on: selectedPaymentType && selectedPaymentType.id == paymentType.id }">
                <div class="title">
                  <component :is="paymentType.component"></component>
                </div>
                <div class="radio_wrap">
                  <label :for="paymentType.id">
                    <input
                      :id="paymentType.id"
                      type="radio"
                      name="paymentType"
                      :value="paymentType.id"
                      @click="setSelectedPaymentType(paymentType)"
                    />
                  </label>
                </div>
              </li>
            </label>
          </ul>
        </div>

        <div class="frame col--sm">
          <div class="payment__check__box">
            <h3>선택한 이용권</h3>
            <div class="payment__check">
              <div v-if="selectedItem" class="title">
                <div>
                  <strong>
                    <font-awesome-icon class="is--gray" icon="fa-regular fa-ticket-simple"></font-awesome-icon
                    >{{ selectedItem.month }}개월 <span>이용권({{ selectedItem.day }}일)</span></strong
                  >
                  <p class="is--gray"
                    >{{
                      (getUserData().service_end_date ? dayjs(getUserData().service_end_date) : dayjs())
                        .add(Number(selectedItem.day), 'day')
                        .format('YYYY년 MM월 DD일')
                    }}까지</p
                  >
                </div>
              </div>

              <span v-else class="is--placeholder">이용권을 선택해 주세요</span>
            </div>

            <h3>선택한 결제 방법</h3>
            <div class="payment__check">
              <div v-if="selectedPaymentType" class="title">
                <component :is="selectedPaymentType.component"></component>
              </div>

              <span v-else class="is--placeholder">결제 방법을 선택해 주세요</span>
            </div>
            <div class="pay__agree">
              <label class="checkbox_wrap" for="ck_agree">
                <input id="ck_agree" name="ck_agree" type="checkbox" />
                <a href="javascript:;" @click="showAgreeModal">결제 및 환불 규정</a>에 동의합니다.
              </label>
            </div>
            <div class="btns">
              <button
                class="btn--brand btn--full"
                :disabled="isNil(selectedItem) || isNil(selectedPaymentType)"
                @click="pay()"
                >결제하기</button
              >
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 개인정보 동의 상세 보기 모달 -->
    <div v-if="isShowAgreeModal" class="modal modal--md">
      <div class="modal_head"
        ><strong>결제 및 환불 규정</strong
        ><button class="btn--close" @click="hideAgreeModal"><font-awesome-icon icon="fa-regular fa-xmark" /></button
      ></div>
      <div class="modal_body modal_body__txt"
        ><ul class="lst--bullet"
          ><li>
            이용권은 약정 기간 동안 월납 또는 일시납으로 금액을 지불하고 리딩수학의 서비스를 자유롭게 이용하는
            상품입니다. </li
          ><li>사용 중인 이용권이 있는 경우, 동일 계정에서 이용권 추가 구매 시 사용기간이 연장됩니다.</li
          ><li>이용권은 일시 정지가 불가합니다.</li
          ><li>이용권의 환불 시 이용한 일수만큼 정가 기준으로 일할 계산하여 제외한 금액이 환불됩니다.</li
          ><li>환불 후에는 리딩수학의 모든 서비스를 즉시 이용할 수 없습니다.</li
          ><li>상담 관련 문의사항은 고객센터로 문의 바랍니다. (1800-5039) </li></ul
        ></div
      >
      <div class="btns">
        <button class="btn--brand" @click="hideAgreeModal">확인</button>
      </div>
    </div>
  </BoardLayout>
</template>

<script setup lang="ts">
  import { onBeforeUnmount, onMounted, PropType, ref } from 'vue';
  import { router, usePage } from '@inertiajs/vue3';
  import { encryptParams } from '@/app/api/payment';
  import { params, PaymentParamsModel, paymentTypes } from '@/app/pages/payments/data';
  import BoardLayout from '@/app/layouts/BoardLayout.vue';
  import { isNil, round } from 'lodash';
  import { dayjs } from 'element-plus';
  import Card from '@/app/components/payments/paymentTypes/Card.vue';
  import Vbank from '@/app/components/payments/paymentTypes/Vbank.vue';
  import Nvpay from '@/app/components/payments/paymentTypes/Nvpay.vue';
  import Kakao from '@/app/components/payments/paymentTypes/Kakao.vue';
  import { getUserData } from '@/app/core/helpers/userHelper';
  import { PaymentProduct } from '@/app/api/model/payments';
  import { numberWithCommas } from '@/app/core/helpers/formattingHelper';

  const props = defineProps({
    products: {
      type: Object as PropType<PaymentProduct[]>,
      required: true,
    },
    config: {
      type: Object as PropType<{
        payment: {
          pg: {
            mid: string;
            va_mid: string;
            pz_mid: string;
            license_key: string;
            aes256_key: string;
            payment_server: string;
            cancel_server: string;
            cash_receipt_server: string;
            sales_receipt_server: string;
            vbank_account_cancel: string;
          };
        };
      }>,
      required: true,
    },
  });

  const paymentTypeList = [
    {
      id: 'card',
      component: Card,
    },
    {
      id: 'vbank',
      component: Vbank,
    },
    {
      id: 'nvpay',
      component: Nvpay,
    },
    {
      id: 'kakao',
      component: Kakao,
    },
  ];

  const noti = route('app.payments.noti', { studentId: usePage().props.auth.user.id });
  const next = route('app.payments.next');
  const canc = route('app.payments.canc');

  const setParams: PaymentParamsModel = params;

  const selectedItem = ref<PaymentProduct>();

  /** 결제 및 환불 규정 */
  const isShowAgreeModal = ref(false);

  function showAgreeModal() {
    isShowAgreeModal.value = true;
  }

  function hideAgreeModal() {
    isShowAgreeModal.value = false;
  }

  /**
   * 이용권 세팅
   */
  const setSelectedItem = (item: PaymentProduct) => {
    selectedItem.value = item;
  };

  const selectedPaymentType = ref<{ id: string; component: any }>();

  /**
   * 이용권 세팅
   */
  const setSelectedPaymentType = (item: { id: string; component: any }) => {
    selectedPaymentType.value = item;
  };

  /**
   * querySelector 간소화
   * @param selector
   */
  const getElement = (selector: string): HTMLInputElement => {
    return document.querySelector(selector) as HTMLInputElement;
  };

  /**
   * 날짜 및 주문정보 재설정
   * @param type
   */
  const init = (type) => {
    const curr_date = new Date();
    const year = curr_date.getFullYear().toString();
    const month = ('0' + (curr_date.getMonth() + 1)).slice(-2).toString();
    const day = ('0' + curr_date.getDate()).slice(-2).toString();
    const hours = ('0' + curr_date.getHours()).slice(-2).toString();
    const mins = ('0' + curr_date.getMinutes()).slice(-2).toString();
    const secs = ('0' + curr_date.getSeconds()).slice(-2).toString();

    setParams.custIp = ''; //고객 IP 세팅
    setParams.trdDt = year + month + day; //요청일자 세팅
    setParams.trdTm = hours + mins + secs; //요청시간 세팅
    setParams.mchtTrdNo = ''; //주문번호 세팅

    if (paymentTypes[type]) {
      // 결제 타입 설정 (모바일, 페이코, 네이버, 카카오)
      setParams.method = paymentTypes[type].method;
      setParams.autoPayType = paymentTypes[type].autoPayType;
      setParams.corpPayCode = paymentTypes[type].corpPayCode;
    } else {
      // 카드, 가상계좌 등등
      setParams.method = type;
    }

    setParams.custAcntSumry = usePage().props.auth.user.name;
    setParams.plainMchtCustId = usePage().props.auth.user.id;
    setParams.plainMchtCustNm = usePage().props.auth.user.name;
    setParams.modelId = usePage().props.auth.user.id;
  };

  /** 결제 버튼 동작 */
  const pay = () => {
    if (!getElement('[name="ck_agree"]:checked')?.value) {
      alert('규정에 동의해야 합니다.');
      return false;
    }
    const type = getElement('[name="paymentType"]:checked')?.value;
    if (type == 'vbank') {
      setParams.mchtId = props.config.payment.pg.va_mid;
    } else if (type == 'card') {
      setParams.mchtId = props.config.payment.pg.mid;
    } else {
      setParams.mchtId = props.config.payment.pg.pz_mid;
    }

    //날짜 및 결제수단 등 재설정
    init(type);

    setParams.notiUrl = noti;
    setParams.nextUrl = next;
    setParams.cancUrl = canc;
    setParams.plainTrdAmt = getElement('[name="products"]:checked')?.value;
    setParams.trdAmt = getElement('[name="products"]:checked')?.value;
    setParams.pmtPrdtNm = getElement('[name="products"]:checked')?.getAttribute('data-name') ?? '';
    setParams.mchtParam = JSON.stringify({
      model_id: setParams.modelId,
      model: 'App\\Models\\Student',
      product_code: getElement('[name="products"]:checked')?.getAttribute('data-code') ?? '',
      corpPayCode: getElement('[name="corpPayCode"]')?.value,
    });

    // 데이터 암호화
    encryptParams(setParams)
      .then((rsp: any) => {
        for (const name in rsp.data.data.enc_params) {
          setParams[name] = rsp.data.data.enc_params[name];
        }
        setParams.mchtTrdNo = rsp.data.data.od_id;

        const res = {
          env: props.config?.payment.pg.payment_server, //결제서버 URL
          pktHash: rsp.data.data.hash_cipher, //SHA256 처리된 해쉬 값 세팅
          ...setParams,
        };

        // eslint-disable-next-line @typescript-eslint/ban-ts-comment
        /* @ts-ignore: settle pg 예외처리 */
        // eslint-disable-next-line no-undef
        SETTLE_PG.pay(
          {
            ...res,
            ui: {
              type: 'popup', //popup, iframe, self, blank
              width: '430', //popup창의 너비
              height: '660', //popup창의 높이
            },
          },
          function (rsp) {
            //iframe인 경우 전달된 결제 완료 후 응답 파라미터 처리
            console.log(rsp);
          },
        );
      })
      .catch(() => {
        alert('에러');
      });
  };

  /**
   * 결제 팝업에서 전달되는 메시지 처리
   */
  const getPostMessage = () => {
    window.addEventListener('message', function (e) {
      if (Object.prototype.hasOwnProperty.call(e.data, 'response')) {
        // 프로퍼티가 존재하면 결과 페이지에 데이터 전달
        if (e.data?.response) {
          router.post(route('app.payments.result'), e.data.response);
        } else {
          alert('결제가 취소되었습니다.');
        }
      }
    });
  };

  const calculateDiscountRate = (originalPrice: number, discountedPrice: number) => {
    const discountRate = ((originalPrice - discountedPrice) / originalPrice) * 100;
    return round(discountRate); // 소수점 둘째 자리까지 반올림
  };

  /**
   * 이벤트 리스너 세팅
   */
  const setEventListener = () => {
    getPostMessage();
  };

  /**
   * 이벤트 리스너 제거
   */
  const removeEventListener = () => {
    getPostMessage();
  };

  onBeforeUnmount(() => {
    removeEventListener();
  });

  onMounted(() => {
    setEventListener();
  });
</script>
