<template>
  <BrandSub>
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
          <li>
            <h3>1년 내내 무제한</h3>
            <strong>월 45,000원</strong>
            <small class="price__badge">25% 할인</small>
            <b>
              <span>총 정상가 720,000</span><br />
              총 할인가 540,000
            </b>
            <p>365일 동안 이용가능</p>
          </li>
          <li>
            <h3>6개월 이용권</h3>
            <strong>월 50,000원</strong>
            <small class="price__badge">17% 할인</small>
            <b>
              <span>총 정상가 360,000원</span><br />
              총 할인가 300,000원
            </b>
            <p>180일 동안 이용가능</p>
          </li>
          <li>
            <h3>3개월 이용권</h3>
            <strong>월 55,000원</strong>
            <small class="price__badge">25% 할인</small>
            <b>
              <span>총 정상가 180,000원</span><br />
              총 할인가 165,000원
            </b>
            <p>365일 동안 이용가능</p>
          </li>
          <li>
            <h3>1개월 이용권</h3>
            <strong>월 60,000원</strong>
            <p>365일 동안 이용가능</p>
          </li>
        </ul>

        <div class="btns">
          <button class="btn--brand">5일 무료체험 시작하기</button>
        </div>
      </div>
    </div>
  </BrandSub>
</template>

<script setup lang="ts">
  import BrandSub from '@/html/layouts/BrandSub.vue';
</script>
