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
              <div class="question__box--toggle">
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
              <p>
                (1) 각 수를 몇백 몇십으로 어림해서 계산하면 428은 430이고, 216은
                <AppBlanks class="blank__correct" :blank-length="4" :blank-count="2" :blank-type="2">1</AppBlanks>이므로
                두 수의 합을 어림한 값은<AppBlanks :blank-length="1" :blank-count="1" :blank-type="1">1</AppBlanks
                >S입니다.
              </p>
              <p
                >(2) 백의 자리부터 차례대로 계산하면 400+200=600, 20+10=30, 8+6 =
                <AppBlanks class="blank__correct" :blank-length="1" :blank-count="1" :blank-type="1">1</AppBlanks>이므로
                428+216=
                <AppBlanks class="blank__focus" :blank-length="1" :blank-count="1" :blank-type="1">1</AppBlanks>
                입니다.</p
              >
              <p
                >(3) 400+200, 28+16을 차례대로 계산하면 400+200=600, 28+16 =
                <AppBlanks :blank-length="1" :blank-count="1" :blank-type="1">1</AppBlanks>이므로 428+216 =
                <AppBlanks :blank-length="1" :blank-count="1" :blank-type="1">1</AppBlanks>입니다.</p
              >
            </div>
          </div>
        </div>
      </div>

      <div class="frame col--sm">
        <div class="frame__head title_area">
          <h3 class="title">6문제 남았어요</h3>
        </div>
        <div class="frame__body">
          <div class="toggle_area">
            <div class="answers">
              <div class="answer__active">
                <input type="text" placeholder="답을 입력해주세요" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>

  <div class="layer ly__math">
    <div class="inner">
      <h2 class="layer__head"> <img src="@/assets/img/math/layer_descriptive.svg" />문제1 </h2>
      <div class="layer__body">
        <p>
          <span class="spl show">훨씬</span><span class="spl show">편리할 수</span
          ><span class="spl show">있습니다.</span><span class="spl show">세로셈으로</span
          ><span class="spl show">고쳐서</span><span class="spl show">계산할 때는</span
          ><span class="spl show">각 수의 자리를</span><span class="spl show">잘 맞추어 쓴 후</span
          ><span class="spl show">계산해야</span><span class="spl show">합니다.</span><span class="spl show">즉,</span
          ><span class="spl show">일의 자리부터</span
          ><i class="math" style="display: none"
            ><img
              src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/EeiitYXycAfY1rP0P5EooEn56MbGncSZ7VKYly9W.png"
              alt="이미지" /></i
          ><span class="spl show">십의 자리,</span
          ><i class="math" style="display: none"
            ><img
              src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/V77iLiIswW1GxpNmgNBBTfUCKsVwv9CQnRLuVW4b.png"
              alt="이미지" /></i
          ><span class="spl show">백의 자리까지</span
          ><i class="math" style="display: none"
            ><img
              src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/uBuZMv34Q9TvcoTe4XClJLd7QEY6PS0OC6fJF6sz.png"
              alt="이미지" /></i
          ><span class="spl current">더한 값을</span><span class="spl">적어줍니다.</span
          ><span class="spl">각 자리마다</span><span class="spl">나타내는 값이</span><span class="spl">다르므로</span
          ><span class="spl">꼭</span><span class="spl">를 맞추어</span><span class="spl link">계산합니다.</span
          ><i class="math"
            ><img
              src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/DvKhb1wqHO9zOAyASs5hfatbz9QVO3GAnuzHHXl6.png"
              alt="이미지" /></i
        ></p>
      </div>
      <div class="btns">
        <button class="btn--gray">문제 풀기</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import AppLayout from '@/html/layouts/AppLayout.vue';
  import AppBlanks from '@/html/components/app/AppBlanks.vue';
</script>
