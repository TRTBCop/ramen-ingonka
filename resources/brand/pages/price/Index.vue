<template>
  <BrandLayout>
    <Head><title>가격 안내</title></Head>
    <div class="hero">
      <div class="container">
        <h2>리딩수학 이용권</h2>
        <p
          >리딩수학만의 특별한 콘텐츠를 <br />
          지금 바로 이용해 보세요!</p
        >
      </div>
    </div>
    <div class="sub">
      <div class="container">
        <div class="price__info">
          <div class="price__title">
            <img src="@/assets/img/brand/ico/price_character.svg" alt="" />
            <h3>이용권 구매 시 공통 특전</h3>
          </div>
          <dl>
            <dt>혜택1</dt>
            <dd>전 학기 수리문해력 종합진단평가 응시 가능</dd>
            <dt>혜택2</dt>
            <dd>오답노트 응시 가능</dd>
            <dt>혜택3</dt>
            <dd>학습보고서 월1회 제공</dd>
          </dl>
        </div>

        <ul class="price__ticket">
          <li v-for="product in products" :key="product.name">
            <h3>{{ product.name }} 이용권</h3>
            <strong>월 {{ numberWithCommas(Math.round(product.amount.sale / product.month)) }}원</strong>
            <!-- 할인 가격인 경우에만 노출 -->
            <template v-if="product.month !== 1">
              <template v-if="calculateDiscountRate(product.amount.origin, product.amount.sale)">
                <small class="price__badge"
                  >{{ calculateDiscountRate(product.amount.origin, product.amount.sale) }}% 할인</small
                >
                <b>
                  <span>총 정상가 {{ numberWithCommas(product.amount.origin) }}</span
                  ><br />
                  총 할인가 {{ numberWithCommas(product.amount.sale) }}
                </b>
              </template>
              <b v-else>총 정상가 {{ numberWithCommas(product.amount.origin) }}</b>
            </template>
            <p>{{ product.day }}일 동안 이용가능</p>
          </li>
        </ul>

        <div class="btns">
          <StartFreeButotn />
        </div>
      </div>
    </div>
  </BrandLayout>
</template>

<script setup lang="ts">
  import { Head } from '@inertiajs/vue3';
  import BrandLayout from '@/brand/layouts/BrandLayout.vue';
  import StartFreeButotn from '@/brand/components/buttons/StartFreeButotn.vue';
  import { round } from 'lodash';
  import { PropType } from 'vue';

  interface PaymentProduct {
    name: string;
    day: string;
    month: number;
    amount: {
      origin: number;
      sale: number;
    };
  }

  defineProps({
    products: {
      type: Object as PropType<PaymentProduct[]>,
      required: true,
    },
  });

  /**
   * 할인율 구하는 메서드
   */
  function calculateDiscountRate(originalPrice: number, discountedPrice: number) {
    const discountRate = ((originalPrice - discountedPrice) / originalPrice) * 100;
    return round(discountRate); // 소수점 둘째 자리까지 반올림
  }

  /** 천단위로 콤마를 찍는 메서드 */
  function numberWithCommas(x: number) {
    return x.toLocaleString('en-US');
  }
</script>
