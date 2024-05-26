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
                <button class="btn_dropdown"></button>
              </div>

              <div class="articles">
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
            <div class="question__box question__box--white">
              <span class="badge--lightgray"><i class="fa-regular fa-scroll-old"></i>문제 풀이</span>
              <div class="articles"
                ><span class="spl show cu">단위분수를 만들어야 하므로 분자에는 1을 놓아야 합니다.</span><br /><span
                  class="spl show current"
                >
                  <em class="order__blank">단위분수를</em>
                  <em class="order__blank">단위분수를</em>
                  <em class="order__blank">단위분수를</em>
                  <em class="order__blank order__blank__focus">단위분수를</em>
                  <em class="order__blank order__blank__on">단위분수를</em>
                  <em class="order__blank">만들어야</em> <span>예시로 중간에 글 넣음</span> </span
                ><br />
                <span class="spl show">단위분수를 만들어야 하므로 분자에는 1을 놓아야 합니다.</span><br /><span
                  class="spl show"
                >
                  <em class="order__blank order__blank__wrong__check">하므로</em>
                  <span>예시로 중간에 글 넣음</span>
                  <em class="order__blank order__blank__wrong__check">분자에는</em>
                  <em class="order__blank order__blank__wrong__check">분자에는</em>
                  <em class="order__blank order__blank__wrong__check">분자에는</em>
                  <em class="order__blank order__blank__wrong__check">1을</em></span
                ><br />
                <span class="spl show">단위분수를 만들어야 하므로 분자에는 1을 놓아야 합니다.</span><br /><span
                  class="spl show"
                >
                  <em class="order__blank order__blank__correct">하므로</em>
                  <span>예시로 중간에 글 넣음</span>
                  <em class="order__blank order__blank__correct">분자에는</em>
                  <em class="order__blank order__blank__correct">분자에는</em>
                  <em class="order__blank order__blank__correct">분자에는</em>
                  <em class="order__blank order__blank__correct">1을</em></span
                ><br />
                <span class="spl show">단위분수를 만들어야 하므로 분자에는 1을 놓아야 합니다.</span><br /><span
                  class="spl show"
                >
                  <em class="order__blank order__blank__wrong">하므로</em>
                  <span>예시로 중간에 글 넣음</span>
                  <em class="order__blank order__blank__wrong">분자에는</em>
                  <em class="order__blank order__blank__wrong">분자에는</em>
                  <em class="order__blank order__blank__wrong">분자에는</em>
                  <em class="order__blank order__blank__wrong">1을</em></span
                ><br />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="frame col--sm">
        <div class="frame__head title_area">
          <h3 class="title">순서에 맞게 문장을 만들어 보세요.</h3>
        </div>
        <div class="frame__body frame__body--center">
          <div class="order__btns"
            ><button>1을</button><button>단위분수를</button><button>놓아야 합니다.</button><button>분자에는 </button
            ><button>만들어야 하므로</button></div
          >
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
  import AppLayout from '@/html/layouts/AppLayout.vue';
  import AppBlanks from '@/html/components/app/AppBlanks.vue';
</script>
