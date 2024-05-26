<template>
  <AppHeader />
  <div class="board payment">
    <div class="hero">
      <div class="container">
        <h2>이용권 구매</h2>
      </div>
    </div>
    <div class="container">
      <div class="grid">
        <div class="col">
          <h3>이용권 선택</h3>
          <!-- [D] radio 체크 된 li에 .payment__choice__on 추가 -->
          <ul class="payment__choice">
            <li class="payment__choice__on">
              <div class="info">
                <div class="title">
                  <p>
                    <strong>1개월</strong>
                    이용권 (30일)
                  </p>
                </div>
                <div class="price">
                  <strong class="price">40,000원</strong>
                </div>
              </div>
              <div class="radio_wrap">
                <label for="period-1">
                  <input id="period-1" type="radio" name="period" value="period-1" checked />
                </label>
              </div>
            </li>
            <li>
              <div class="info">
                <div class="title">
                  <p>
                    <strong>3개월</strong>
                    이용권 (90일)
                    <small class="badge--primary--alpha"
                      ><font-awesome-icon icon="fa-regular fa-arrow-down"></font-awesome-icon>9%</small
                    >
                  </p>
                </div>
                <div class="price">
                  <p>120,000원</p>
                  <strong>110,000원 <span>(36,666원/월)</span></strong>
                </div>
              </div>
              <div class="radio_wrap">
                <label for="period-2">
                  <input id="period-2" type="radio" name="period" value="period-2" />
                </label>
              </div>
            </li>
            <li>
              <div class="info">
                <div class="title">
                  <p>
                    <strong>6개월</strong>
                    이용권 (180일)
                    <small class="badge--primary--alpha"
                      ><font-awesome-icon icon="fa-regular fa-arrow-down"></font-awesome-icon>17%</small
                    >
                  </p>
                </div>
                <div class="price">
                  <p>240,000원</p>
                  <strong>199,000원 <span>(50,000원/월)</span></strong>
                </div>
              </div>
              <div class="radio_wrap">
                <label for="period-3"> <input id="period-3" type="radio" name="period" value="period-3" /> </label>
              </div>
            </li>
            <li>
              <div class="info">
                <div class="title">
                  <p>
                    <strong>12개월</strong>
                    이용권 (360일)
                    <small class="badge--primary--alpha"
                      ><font-awesome-icon icon="fa-regular fa-arrow-down"></font-awesome-icon>25%</small
                    >
                  </p>
                </div>
                <div class="price">
                  <p>480,000원</p>
                  <strong>360,000원 <span>(30,000원/월)</span></strong>
                </div>
              </div>
              <div class="radio_wrap">
                <label for="period-4">
                  <input id="period-4" type="radio" name="period" value="period-4" />
                </label>
              </div>
            </li>
          </ul>

          <h3>결제 방법 선택</h3>
          <ul class="payment__choice">
            <li>
              <div class="title">
                <p><font-awesome-icon icon="ico__pay fa-regular fa-credit-card"></font-awesome-icon>신용카드 결제</p>
              </div>
              <div class="radio_wrap">
                <label for="card">
                  <input id="card" type="radio" name="payment" values="card" />
                </label>
              </div>
            </li>
            <li>
              <div class="title">
                <p><font-awesome-icon icon="ico__pay fa-regular fa-bank"></font-awesome-icon>가상계좌 입금</p>
              </div>
              <div class="radio_wrap">
                <label for="cms">
                  <input id="cms" type="radio" name="payment" value="cms" />
                </label>
              </div>
            </li>
            <li>
              <div class="title">
                <p><i class="ico__pay ico__pay--naver"></i>네이버페이 결제</p>
              </div>
              <div class="radio_wrap">
                <label for="naver">
                  <input id="naver" type="radio" name="payment" value="naver" />
                </label>
              </div>
            </li>
            <li>
              <div class="title">
                <p><i class="ico__pay ico__pay--kakao"></i>카카오페이 결제</p>
              </div>
              <div class="radio_wrap">
                <label for="kakao">
                  <input id="kakao" type="radio" name="payment" value="kakao" />
                </label>
              </div>
            </li>
          </ul>
        </div>

        <!-- [D] 이용권 없을때 span.is_dark -->
        <div class="frame col--sm">
          <div class="payment__check__box">
            <h3>선택한 이용권</h3>
            <div class="payment__check">
              <span class="is--placeholder">이용권을 선택해 주세요</span>
            </div>
            <div class="payment__check">
              <div class="title">
                <div>
                  <strong>
                    <font-awesome-icon class="is--gray" icon="fa-regular fa-ticket-simple"></font-awesome-icon>3개월
                    <span>이용권(90일)</span></strong
                  >
                  <p class="is--gray">2024년 12월 23일까지</p>
                </div>
              </div>
              <span class=""></span>
            </div>

            <h3>선택한 결제 방법</h3>
            <div class="payment__check">
              <span class="is--placeholder">결제 방법을 선택해 주세요</span>
            </div>
            <div class="payment__check">
              <div class="title">
                <p><i class="ico__pay ico__pay--kakao"></i>카카오페이 결제</p>
              </div>
            </div>
            <div class="pay__agree">
              <label class="checkbox_wrap checkbox--sm" for="ck_agree">
                <input id="ck_agree" type="checkbox" />
                <a href="">결제 및 환불 규정</a>에 동의합니다.
              </label>
            </div>
            <div class="btns">
              <button class="btn--brand btn--full">결제하기</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
  import AppHeader from '@/html/components/app/AppHeader.vue';
  import { router } from '@inertiajs/vue3';
</script>
