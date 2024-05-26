<template>
  <AppLayout :theme-show="3">
    <div class="grid">
      <div class="frame col">
        <div class="frame__head tab_area">
          <ul class="tabs tabs--count">
            <li class="current"></li>
            <li class=""></li>
          </ul>
        </div>
        <div class="frame__body">
          <div class="question_area">
            <div class="question__box">
              <div class="question__box__toggle">
                <h3 class="">문제</h3>
                <button class="btn_dropdown" :class="{ btn_dropdown__on: openToggleMenu }" @click="toggleMenu"></button>
              </div>

              <div class="articles" :class="{ articles__hide: openToggleMenu }">
                <article>
                  <p
                    ><span class="spl show">받아올림이</span><span class="spl show">없는</span
                    ><span class="spl show">(세 자리 수) +</span><span class="spl show">(세 자리 수)는</span
                    ><span class="spl show">어떻게</span><span class="spl show">계산할까요?</span><br /><span
                      class="spl show"
                      >&nbsp;받아올림이</span
                    ><span class="spl show">없는</span><span class="spl show">(세 자리 수) +</span
                    ><span class="spl show">(세 자리 수)는</span><span class="spl show">일의 자리부터</span
                    ><span class="spl show">십의 자리,</span><span class="spl show">백의 자리까지</span
                    ><span class="spl show">차례로 더하여</span><span class="spl show">계산합니다.</span><br /><span
                      class="spl show"
                      >&nbsp;275 + 423을</span
                    ><span class="spl show">여러 가지</span><span class="spl show">방법으로</span
                    ><span class="spl show">계산해 봅니다.</span><br /><span class="spl show">&nbsp;첫 번째,</span
                    ><span class="spl show">어림하여</span><span class="spl show">계산하는</span
                    ><span class="spl show">방법입니다.</span><span class="spl show">어림하여</span
                    ><span class="spl show">계산하면</span><span class="spl show">정확한 값을</span
                    ><span class="spl show">구할 수는</span><span class="spl show">없지만,</span
                    ><span class="spl show">계산을</span><span class="spl show">하기 전에</span
                    ><span class="spl show">계산 결과의</span><span class="spl show">값을</span
                    ><span class="spl show">예상할 수 있고</span><span class="spl show">계산을 하고 난</span
                    ><span class="spl show">다음</span><span class="spl show">계산 결과가</span
                    ><span class="spl show">맞았는지</span><span class="spl show">확인할 수</span
                    ><span class="spl show">있습니다.</span><span class="spl show">예를 들어</span
                    ><span class="spl show">275 + 423을</span><span class="spl show">계산할 때</span
                    ><span class="spl show">각 수를</span><span class="spl show">몇백 몇십으로</span
                    ><span class="spl show">어림해 보면</span><span class="spl show">275는</span
                    ><span class="spl show">280으로,</span><span class="spl show">423은</span
                    ><span class="spl show">420으로</span><span class="spl show">어림할 수</span
                    ><span class="spl show">있습니다.</span><span class="spl show">따라서</span
                    ><span class="spl show">275 + 423을</span><span class="spl show">어림한 값은</span
                    ><span class="spl show">280 + 420 =</span>
                  </p>
                </article>
              </div>
            </div>
            <div class="question__box question__box__explain">
              <span class="badge--lightgray"><i class="fa-regular fa-scroll-old"></i>문제 풀이</span>
              <div class="articles skeleton"
                ><span class="spl show">단위분수를 만들어야 하므로 분자에는 1을 놓아야 합니다.</span><br /><span
                  class="spl show"
                  ><em class="order__blank__on">단위분수를</em> <em class="order__blank__on">만들어야</em
                  ><em class="order__blank__on">하므로</em> <em class="order__blank__on">분자에는</em>
                  <em class="order__blank__on">1을 놓아야 합니다.</em></span
                >
                <span class="spl">단위분수를 만들어야 하므로 분자에는 1을 놓아야 합니다.</span
                ><span class="spl">단위분수를 만들어야 하므로 분자에는 1을 놓아야 합니다.</span><br />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="frame col--sm">
        <div class="frame__head tab_area">
          <ul class="tabs">
            <li class="current">답안</li>
            <li class="">문제</li>
          </ul>
        </div>
        <div class="frame__body">
          <article class="fr-view"
            >아프리카 콩고의 국기는 초록색, 노란색, 빨간색으로 세 가지 색으로 이루어져 있고, 유럽에 있는 스위스의 국기는
            빨간색, 흰색 두 가지 색으로 이루어져 있습니다. 두 나라의 국기에서 찾을 수 있는 직각은 모두 몇 개인지 구해
            보세요.<br /><br /><img
              src="https://dev-reading-math.s3.ap-northeast-2.amazonaws.com/question/zNp1N3DVGuskZaF7fYYOQRie7RHm5epAY34ThVCA.png"
              style="width: 217px"
              class="fr-fic fr-dib" />&nbsp; &nbsp;&nbsp;<img
              src="https://dev-reading-math.s3.ap-northeast-2.amazonaws.com/question/63KXgOAsO2BA5wDcj69xIMcCcLpZgFfpA3Jcgvxd.png"
              style="width: 217px"
              class="fr-fic fr-dib"
          /></article>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
  import AppLayout from '@/html/layouts/AppLayout.vue';
  import AppBlanks from '@/html/components/app/AppBlanks.vue';
  import { ref } from 'vue';

  const openToggleMenu = ref(false);

  const toggleMenu = () => {
    openToggleMenu.value = !openToggleMenu.value;
  };
</script>
