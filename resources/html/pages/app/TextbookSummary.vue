<template>
  <AppLayout :theme-show="1">
    <div class="grid">
      <div class="frame col">
        <div class="frame__head title_area">
          <h3 class="title">지문</h3>
          <ol class="numbers">
            <li class="on"></li>
            <li></li>
            <li></li>
          </ol>
        </div>
        <div class="frame__body">
          <div class="articles">
            <article class=""
              ><p
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
                ><span class="spl show">280 + 420 =</span
                ><span class="spl show"><em class="blank__on">700</em>입니다.</span><br /><span class="spl show"
                  >&nbsp;두 번째,</span
                ><span class="spl show">각 자리의 수를</span><span class="spl show">더하여 합하는</span
                ><span class="spl show">방법입니다.</span><span class="spl show link">275 + 423을</span
                ><i class="math" style="display: none"
                  ><img
                    src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/jmjgDC9hNSveSdXFyAVhJUhA4LNuGlDm4YullgQ0.png"
                    alt="이미지" /></i
                ><span class="spl show">계산할 때</span><span class="spl show">백의 자리끼리</span
                ><span class="spl show link">더하면</span
                ><i class="math" style="display: none"
                  ><img
                    src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/CCvDgjRVuS3dxJb9c6fIqSlc900EbSoWRDA0X3yt.png"
                    alt="이미지" /></i
                ><span class="spl show">200 + 400 =</span><span class="spl show">600,</span
                ><span class="spl show">십의 자리끼리</span><span class="spl show link">더하면</span
                ><i class="math" style="display: none"
                  ><img
                    src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/V7c1SHWNWvRGsamnw7HKgL4o4uJtdaVXJcMrbb0n.png"
                    alt="이미지" /></i
                ><span class="spl show">70 + 20 =</span><span class="spl show">90,</span
                ><span class="spl show">일의 자리끼리</span><span class="spl show link">더하면</span
                ><i class="math" style="display: none"
                  ><img
                    src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/HEmsw0jdlGYVQuUZM28HKHyS784G3lb4h9kdSIJA.png"
                    alt="이미지" /></i
                ><span class="spl show">5 + 3 =</span><span class="spl show">8이므로</span
                ><span class="spl show">이어서</span><span class="spl show">각 더한 수들을</span
                ><span class="spl show link">합해주면</span
                ><i class="math" style="display: none"
                  ><img
                    src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/wPI77qKT1OeocVZUcV3JtzoKQ2bNV4kktOA2oMjG.png"
                    alt="이미지" /></i
                ><span class="spl show">600 + 90 + 8 =</span><span class="spl show link">698입니다.</span
                ><i class="math" style="display: none"
                  ><img
                    src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/7GR3ZaaIdUNMMBDrskp53hr2R8VEyNjDurFdq0TZ.png"
                    alt="이미지" /></i
                ><span class="spl show">따라서</span><span class="spl show">275 + 423 =</span
                ><span class="spl show link">698입니다.</span
                ><i class="math"
                  ><img
                    src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/67meKJNZdXMqJwpKEgr1T4PwuZLBjZEFmOR8CXav.png"
                    alt="이미지" /></i
                ><br /><span class="spl show">&nbsp;세 번째,</span><span class="spl show">백의 자리의</span
                ><span class="spl show">숫자끼리</span><span class="spl show">더하고,</span
                ><span class="spl show">나머지</span><span class="spl show">몇십 몇을</span
                ><span class="spl show">더하는</span><span class="spl show">방법이</span
                ><span class="spl show">있습니다.</span><span class="spl show link">275 + 423을</span
                ><i class="math" style="display: none"
                  ><img
                    src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/6bBlP8z1mSX2KvhDzLmKqBPvgsYMnNDVsVQ0I6hw.png"
                    alt="이미지" /></i
                ><span class="spl show">계산할 때</span><span class="spl show">백의 자리</span
                ><span class="spl show">숫자끼리</span><span class="spl show link">더하면</span
                ><i class="math" style="display: none"
                  ><img
                    src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/fqY2iovzBzCRg2vfsKm7jJzJ6JbQ4Hp5uqkpK1uC.png"
                    alt="이미지" /></i
                ><span class="spl show">200 + 400 =</span><span class="spl show">600,</span
                ><span class="spl show">나머지 자리인</span><span class="spl show">몇십 몇을</span
                ><span class="spl show link">더하면</span
                ><i class="math" style="display: none"
                  ><img
                    src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/7ocG9tKuxTb2n4zLyWfUBvHkIYihTvLOZTBl2Quc.png"
                    alt="이미지" /></i
                ><span class="spl show">75 + 23 =</span><span class="spl show">98이 되고</span
                ><span class="spl show">이어서</span><span class="spl show">두 수를</span
                ><span class="spl show">합해주면</span><span class="spl show">600 + 98 =</span
                ><span class="spl show link">698이 됩니다.</span
                ><i class="math"
                  ><img
                    src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/7oylB5igFL3ce05TMsQzr7JlIlb8CLb7tmsvowH7.png"
                    alt="이미지" /></i
                ><span class="spl show">따라서</span><span class="spl show">275 + 423 =</span
                ><span class="spl show">698입니다.</span></p
              ><p
                ><br /><span class="spl show">&nbsp;이번에는</span><span class="spl show">세로셈을</span
                ><span class="spl show">이용해</span><span class="spl show">덧셈을</span
                ><span class="spl show">해 보도록</span><span class="spl show">하겠습니다.</span
                ><span class="spl show">자릿수가</span><span class="spl show">많아져</span
                ><span class="spl show">복잡해지면</span><span class="spl show">세로셈으로</span
                ><span class="spl show">고쳐서</span><span class="spl show">계산하는 것이</span
                ><span class="spl show">훨씬</span><span class="spl show">편리할 수</span
                ><span class="spl show">있습니다.</span><span class="spl show">세로셈으로</span
                ><span class="spl show">고쳐서</span><span class="spl show">계산할 때는</span
                ><span class="spl show">각 수의 자리를</span><span class="spl show">잘 맞추어 쓴 후</span
                ><span class="spl show">계산해야</span><span class="spl show">합니다.</span
                ><span class="spl show">즉,</span><span class="spl show link">일의 자리부터</span
                ><i class="math" style="display: none"
                  ><img
                    src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/EeiitYXycAfY1rP0P5EooEn56MbGncSZ7VKYly9W.png"
                    alt="이미지" /></i
                ><span class="spl show link">십의 자리,</span
                ><i class="math" style="display: none"
                  ><img
                    src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/V77iLiIswW1GxpNmgNBBTfUCKsVwv9CQnRLuVW4b.png"
                    alt="이미지" /></i
                ><span class="spl current link">백의 자리까지</span
                ><i class="math" style="display: none"
                  ><img
                    src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/uBuZMv34Q9TvcoTe4XClJLd7QEY6PS0OC6fJF6sz.png"
                    alt="이미지" /></i
                ><span class="spl current">더한 값을</span
                ><span class="spl current"><em class="blank__focus"></em></span><span class="spl">적어줍니다.</span
                ><span class="spl">각 자리마다</span><span class="spl">나타내는 값이</span
                ><span class="spl">다르므로</span><span class="spl">꼭</span
                ><span class="spl"><em class="blank__d"></em>를 맞추어</span><span class="spl link">계산합니다.</span
                ><i class="math"
                  ><img
                    src="https://s3.ap-northeast-2.amazonaws.com/dev-reading-math/temp/images/DvKhb1wqHO9zOAyASs5hfatbz9QVO3GAnuzHHXl6.png"
                    alt="이미지" /></i></p
            ></article>
          </div>
        </div>
      </div>

      <div class="frame col--sm">
        <div class="frame__head title_area">
          <h3 class="title">그림으로 설명하기 </h3>
          <button class="btn_dropdown"></button>
        </div>
        <div class="frame__body">
          <div class="image_area">
            <div class="image_box">
              <img src="@/assets/img/math/sample2.png" />
              <div class="image_count">
                <button class="btn_prev"></button>
                <strong><span class="num__current">4</span>/<span class="num__total">20</span></strong>
                <button class="btn_next" disabled></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>

  <div class="layer ly__math">
    <div class="inner">
      <h2 class="layer__head"> <img src="@/assets/img/math/layer_textbook.svg" />개념 요약하기 </h2>
      <div class="layer__body">
        <p>
          <span class="spl show"
            >① 받아올림이 한 번 있는 (세 자리 수) + (세 자리 수) <em class="blank__on">답</em></span
          >
          <span class="spl show"
            >같은 자리의 수끼리 합이 10이거나 10보다 크면 바로 윗자리로 <em class="blank__focus">답답</em>을
            받아올림하여 계산합니다.</span
          >
          <span class="spl">받아올림을 하면 바로 윗자리의 수는 <em class="blank">답답</em>이 커지게 됩니다.</span>
        </p>
      </div>
      <div class="btns--answers">
        <button class="layer__box correct"><p>1</p></button>
        <button class="layer__box" disabled> <p>10</p> </button>
      </div>
      <div class="btns">
        <button class="btn--gray">지문 다시보기</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import AppLayout from '@/html/layouts/AppLayout.vue';
</script>
