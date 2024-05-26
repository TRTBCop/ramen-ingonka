<template>
  <AppLayout :theme-show="1">
    <!--
        [D] 기본 : article > span.spl
        읽었을때 : span.spl.show
        현재 : span.current
      -->
    <!--
        [D] 이미지매칭 버튼 : .link
        이미지매칭 - 눌러졌을때 : .link__pressed
        이미지매칭 - 현재 : .link__current
      -->
    <!--
        [D] 빈칸 : em.blank
        빈칸 맞췄을때 : em.blank__on
        빈칸 포커싱 : em.blank__focus
      -->

    <div class="grid">
      <div class="frame col">
        <div class="frame__head title_area">
          <h3 class="title">지문</h3>
          <ol class="numbers">
            <li class="done"></li>
            <li class="done"></li>
            <li class="on"></li>
            <li></li>
            <li></li>
          </ol>
        </div>
        <div class="frame__body">
          <article class=""
            ><span class="spl show">받아올림이</span
            ><!--v-if--><span class="spl show">없는</span
            ><!--v-if--><span class="spl show">(세 자리 수) +</span
            ><!--v-if--><span class="spl show">(세 자리 수)는</span
            ><!--v-if--><span class="spl show">어떻게</span
            ><!--v-if--><span class="spl show">계산할까요?</span
            ><!--v-if--><!--v-if--><br /><span class="spl show">&nbsp;받아올림이</span
            ><!--v-if--><span class="spl show">없는</span
            ><!--v-if--><span class="spl show">(세 자리 수) +</span
            ><!--v-if--><span class="spl show">(세 자리 수)는</span
            ><!--v-if--><span class="spl show">일의 자리부터</span
            ><!--v-if--><span class="spl show">십의 자리,</span
            ><!--v-if--><span class="spl show">백의 자리까지</span
            ><!--v-if--><span class="spl show">차례로 더하여</span
            ><!--v-if--><span class="spl show">계산합니다.</span
            ><!--v-if--><!--v-if--><br /><span class="spl show">&nbsp;275 + 423을</span
            ><!--v-if--><span class="spl show">여러 가지</span
            ><!--v-if--><span class="spl show">방법으로</span
            ><!--v-if--><span class="spl show">계산해 봅니다.</span
            ><!--v-if--><!--v-if--><br /><span class="spl show">&nbsp;첫 번째,</span
            ><!--v-if--><span class="spl show">어림하여</span
            ><!--v-if--><span class="spl show">계산하는</span
            ><!--v-if--><span class="spl show">방법입니다.</span
            ><!--v-if--><span class="spl show">어림하여</span
            ><!--v-if--><span class="spl show">계산하면</span
            ><!--v-if--><span class="spl show">정확한 값을</span
            ><!--v-if--><span class="spl show">구할 수는</span
            ><!--v-if--><span class="spl show">없지만,</span
            ><!--v-if--><span class="spl show">계산을</span
            ><!--v-if--><span class="spl show">하기 전에</span
            ><!--v-if--><span class="spl show">계산 결과의</span
            ><!--v-if--><span class="spl show">값을</span
            ><!--v-if--><span class="spl show">예상할 수 있고</span
            ><!--v-if--><span class="spl show">계산을 하고 난</span
            ><!--v-if--><span class="spl show">다음</span
            ><!--v-if--><span class="spl show">계산 결과가</span
            ><!--v-if--><span class="spl show">맞았는지</span
            ><!--v-if--><span class="spl show">확인할 수</span
            ><!--v-if--><span class="spl show">있습니다.</span
            ><!--v-if--><span class="spl show">예를 들어</span
            ><!--v-if--><span class="spl show">275 + 423을</span
            ><!--v-if--><span class="spl show">계산할 때</span
            ><!--v-if--><span class="spl show">각 수를</span
            ><!--v-if--><span class="spl show">몇백 몇십으로</span
            ><!--v-if--><span class="spl show">어림해 보면</span
            ><!--v-if--><span class="spl show">275는</span
            ><!--v-if--><span class="spl show">280으로,</span
            ><!--v-if--><span class="spl show">423은</span
            ><!--v-if--><span class="spl show">420으로</span
            ><!--v-if--><span class="spl show">어림할 수</span
            ><!--v-if--><span class="spl show">있습니다.</span
            ><!--v-if--><span class="spl show">따라서</span
            ><!--v-if--><span class="spl show">275 + 423을</span
            ><!--v-if--><span class="spl show">어림한 값은</span
            ><!--v-if--><span class="spl show">280 + 420 =</span
            ><!--v-if--><span class="spl current"
              ><span class="on"
                ><em class="blank blank__focus"></em>
                <span class="answers">
                  <div class="answers--bubble show">
                    <div class="answers--choice"
                      ><button><i class="answer__number"></i><span>기본</span></button
                      ><button class="answer__correct"
                        ><i class="answer__number"></i><span>안녕하세요 저는 정답입니다</span></button
                      ><button class="answer__wrong__check"
                        ><i class="answer__number"></i><span>저는 틀렸다가 오답체크 했습니다.</span></button
                      ><button class="answer__wrong"><i class="answer__number"></i><span>오답</span></button
                      ><button class="answer__check"
                        ><i class="answer__number"></i><span>다중선택: 여러개를 선택해야하는 경우</span></button
                      ></div
                    >
                  </div>
                </span> </span
              >입니다.</span
            ><!--v-if--><!--v-if--><br /><span class="spl">&nbsp;두 번째,</span
            ><!--v-if--><span class="spl">각 자리의 수를</span
            ><!--v-if--><span class="spl">더하여 합하는</span
            ><!--v-if--><span class="spl">방법입니다.</span
            ><!--v-if--><span class="spl">275 + 423을</span
            ><!--v-if--><span class="spl">계산할 때</span
            ><!--v-if--><span class="spl">백의 자리끼리</span
            ><!--v-if--><span class="spl">더하면</span
            ><!--v-if--><span class="spl">200 + 400 =</span
            ><!--v-if--><span class="spl">600,</span
            ><!--v-if--><span class="spl">십의 자리끼리</span
            ><!--v-if--><span class="spl">더하면</span
            ><!--v-if--><span class="spl">70 + 20 =</span
            ><!--v-if--><span class="spl">90,</span
            ><!--v-if--><span class="spl">일의 자리끼리</span
            ><!--v-if--><span class="spl">더하면</span
            ><!--v-if--><span class="spl">5 + 3 =</span
            ><!--v-if--><span class="spl">8이므로</span
            ><!--v-if--><span class="spl">이어서</span
            ><!--v-if--><span class="spl">각 더한 수들을</span
            ><!--v-if--><span class="spl">합해주면</span
            ><!--v-if--><span class="spl">600 + 90 + 8 =</span
            ><!--v-if--><span class="spl">698입니다.</span
            ><!--v-if--><span class="spl">따라서</span
            ><!--v-if--><span class="spl">275 + 423 =</span
            ><!--v-if--><span class="spl">698입니다.</span
            ><!--v-if--><!--v-if--><br /><span class="spl">&nbsp;세 번째,</span
            ><!--v-if--><span class="spl">백의 자리의</span
            ><!--v-if--><span class="spl">숫자끼리</span
            ><!--v-if--><span class="spl">더하고,</span
            ><!--v-if--><span class="spl">나머지</span
            ><!--v-if--><span class="spl">몇십 몇을</span
            ><!--v-if--><span class="spl">더하는</span
            ><!--v-if--><span class="spl">방법이</span
            ><!--v-if--><span class="spl">있습니다.</span
            ><!--v-if--><span class="spl">275 + 423을</span
            ><!--v-if--><span class="spl">계산할 때</span
            ><!--v-if--><span class="spl">백의 자리</span
            ><!--v-if--><span class="spl">숫자끼리</span
            ><!--v-if--><span class="spl">더하면</span
            ><!--v-if--><span class="spl">200 + 400 =</span
            ><!--v-if--><span class="spl">600,</span
            ><!--v-if--><span class="spl">나머지 자리인</span
            ><!--v-if--><span class="spl">몇십 몇을</span
            ><!--v-if--><span class="spl">더하면</span
            ><!--v-if--><span class="spl">75 + 23 =</span
            ><!--v-if--><span class="spl">98이 되고</span
            ><!--v-if--><span class="spl">이어서</span
            ><!--v-if--><span class="spl">두 수를</span
            ><!--v-if--><span class="spl">합해주면</span
            ><!--v-if--><span class="spl">600 + 98 =</span
            ><!--v-if--><span class="spl">698이 됩니다.</span
            ><!--v-if--><span class="spl">따라서</span
            ><!--v-if--><span class="spl">275 + 423 =</span
            ><!--v-if--><span class="spl">698입니다.</span
            ><!--v-if--><br /><!--v-if--><br /><span class="spl">&nbsp;이번에는</span
            ><!--v-if--><span class="spl">세로셈을</span
            ><!--v-if--><span class="spl">이용해</span
            ><!--v-if--><span class="spl">덧셈을</span
            ><!--v-if--><span class="spl">해 보도록</span
            ><!--v-if--><span class="spl">하겠습니다.</span
            ><!--v-if--><span class="spl">자릿수가</span
            ><!--v-if--><span class="spl">많아져</span
            ><!--v-if--><span class="spl">복잡해지면</span
            ><!--v-if--><span class="spl">세로셈으로</span
            ><!--v-if--><span class="spl">고쳐서</span
            ><!--v-if--><span class="spl">계산하는 것이</span
            ><!--v-if--><span class="spl">훨씬</span
            ><!--v-if--><span class="spl">편리할 수</span
            ><!--v-if--><span class="spl">있습니다.</span
            ><!--v-if--><span class="spl">세로셈으로</span
            ><!--v-if--><span class="spl">고쳐서</span
            ><!--v-if--><span class="spl">계산할 때는</span
            ><!--v-if--><span class="spl">각 수의 자리를</span
            ><!--v-if--><span class="spl">잘 맞추어 쓴 후</span
            ><!--v-if--><span class="spl">계산해야</span
            ><!--v-if--><span class="spl">합니다.</span
            ><!--v-if--><span class="spl">즉,</span
            ><!--v-if--><span class="spl">일의 자리부터</span
            ><!--v-if--><span class="spl">십의 자리,</span
            ><!--v-if--><span class="spl">백의 자리까지</span
            ><!--v-if--><span class="spl">더한 값을</span
            ><!--v-if--><span class="spl"
              ><span class="on"
                ><em class="blank"></em
                ><div class="answers--bubble" style="left: 50%; transform: translateX(-50%); top: auto; bottom: 4rem"
                  ><div class="answers"
                    ><button class=""><i class="answer__number"></i><span>차례대로</span></button
                    ><button class=""><i class="answer__number"></i><span>마음대로</span></button></div
                  ></div
                ></span
              ></span
            ><!--v-if--><span class="spl">적어줍니다.</span
            ><!--v-if--><span class="spl">각 자리마다</span
            ><!--v-if--><span class="spl">나타내는 값이</span
            ><!--v-if--><span class="spl">다르므로</span
            ><!--v-if--><span class="spl">꼭</span
            ><!--v-if--><span class="spl"
              ><span class="on"
                ><em class="blank"></em
                ><div class="answers--bubble" style="right: 0px; top: auto; bottom: 4rem"
                  ><div class="answers"
                    ><button class=""><i class="answer__number"></i><span>자리</span></button
                    ><button class=""><i class="answer__number"></i><span>순서</span></button
                    ><button class=""><i class="answer__number"></i><span>부호</span></button></div
                  ></div
                ></span
              >를 맞추어</span
            ><!--v-if--><span class="spl">계산합니다.</span
            ><!--v-if--><br /><!--v-if--><br /><!--v-if--><!--v-if--></article
          >
        </div>
      </div>

      <div class="frame col--sm">
        <div class="frame__head title_area">
          <h3 class="title">그림으로 설명하기 </h3>
        </div>
        <div class="frame__body">
          <div class="image_area">
            <div class="image__box">
              <img src="@/assets/img/math/sample2.png" />
            </div>
            <div class="image_count">
              <button class="btn__prev"></button>
              <strong><span class="num__current">4</span>/<span class="num__total">20</span></strong>
              <button class="btn__next" disabled></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
  import AppLayout from '@/html/layouts/AppLayout.vue';
  import AppBlanks from '@/html/components/app/AppBlanks.vue';
</script>
