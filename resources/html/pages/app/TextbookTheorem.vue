<template>
  <AppLayout :theme-show="1">
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
          <div class="articles">
            <article class="articles"
              ><p
                ><span class="spl show">시간의</span><span class="spl show">덧셈과 뺄셈은</span
                ><span class="spl show">어떻게</span><span class="spl show">계산할까요?</span><br /><span
                  class="spl show"
                  >&nbsp;일상생활에서</span
                ><span class="spl show">시각과 시간을</span><span class="spl show">정확히</span
                ><span class="spl show">구분하지 않고</span><span class="spl show">사용하지만</span
                ><span class="spl show">시각과 시간은</span><span class="spl show">서로 다릅니다.</span
                ><span class="spl show">‘영화가</span><span class="spl show">시작한 시각은</span
                ><span class="spl show">3시입니다.’</span><span class="spl show">와 같이</span
                ><span class="spl show">시각은</span><span class="spl show">어느 한 시점을</span
                ><span class="spl show">나타내고,</span><span class="spl show">‘영화를</span
                ><span class="spl show">3시간 동안</span><span class="spl show">봤습니다.’</span
                ><span class="spl show">와 같이</span><span class="spl show">시간은</span
                ><span class="spl show">어떤 시각에서</span><span class="spl show">어떤 시각</span
                ><span class="spl show">사이를</span><span class="spl show">나타냅니다.</span><br /><span
                  class="spl show"
                  >&nbsp;시간의</span
                ><span class="spl show">덧셈과 뺄셈은</span><span class="spl show">같은 단위끼리</span
                ><span class="spl show">더하고 뺍니다.</span><span class="spl show">즉</span
                ><span class="spl show">시는 시끼리,</span><span class="spl show">분은 분끼리,</span
                ><span class="spl show">초는 초끼리</span><span class="spl show">계산합니다.</span><br /><span
                  class="spl show"
                  >&nbsp;먼저</span
                ><span class="spl show">받아올림이</span><span class="spl show">없는</span
                ><span class="spl show">시간의 덧셈을</span><span clasRs="spl show">계산해 봅니다.</span
                ><span class="spl show">예를 들어</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46043_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">8시 20분</span><span class="spl show">35초와</span
                ><span class="spl show">1시간 25분</span><span class="spl show">10초를</span
                ><span class="spl show">더하여 봅니다.</span><span class="spl show">초는 초끼리</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46049_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">계산하면</span><span class="spl show">35초에</span
                ><span class="spl show">10초를</span><span class="spl show">더하여</span
                ><span class="spl show">45초,</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46054_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">분은 분끼리</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46055_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">계산하여</span><span class="spl show">20분에</span
                ><span class="spl show">25분을</span><span class="spl show">더하면</span
                ><span class="spl show">45분,</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46060_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">시는 시끼리</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46061_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">계산하여</span><span class="spl show">8시에</span
                ><span class="spl show">1시간을</span><span class="spl show">더하면</span
                ><span class="spl show">9시가 되어</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46066_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">계산한 시각은</span><span class="spl show">9시 45분</span
                ><span class="spl show">45초가</span><span class="spl show">됩니다.</span
                ><i class="math"
                  ><img src="https://dailykor.com/data/math/training_img/46070_training_1.jpg" alt="이미지" /></i
                ><br /><span class="spl show">&nbsp;다음으로</span><span class="spl show">받아올림이</span
                ><span class="spl show">있는</span><span class="spl show">시간의 덧셈은</span
                ><span class="spl show">초 단위끼리의</span><span class="spl show">합이</span
                ><span class="spl show">60과 같거나</span><span class="spl show">60보다 크면</span
                ><span class="spl show">60초를</span><span class="spl show">1분으로</span
                ><span class="spl show">받아올림하고,</span><span class="spl show">분 단위끼리의</span
                ><span class="spl show">합이</span><span class="spl show">60과 같거나</span
                ><span class="spl show">60보다 크면</span><span class="spl show">60분을</span
                ><span class="spl show">1시간으로</span><span class="spl show">받아올림합니다.</span><br /><span
                  class="spl show"
                  >&nbsp;예를 들어</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46089_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">4시 35분</span><span class="spl show">40초와</span
                ><span class="spl show">2시간 40분</span><span class="spl show">30초를</span
                ><span class="spl show">더하여 봅니다.</span><span class="spl show">초 단위는</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46095_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">40초에</span><span class="spl show">30초를</span
                ><span class="spl show">더하면</span><span class="spl show">70초가 되므로</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46099_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">60초는</span><span class="spl show">1분으로</span
                ><span class="spl show">받아올림해서</span><span class="spl show">10초가 되고,</span
                ><span class="spl show">분 단위는</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46104_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">받아올림한</span><span class="spl show">1분과</span
                ><span class="spl show">35분,</span><span class="spl show">40분을</span
                ><span class="spl show">더하면</span><span class="spl show">76분이</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46110_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">되므로</span><span class="spl show">60분은</span
                ><span class="spl show"><em class="blank__on">1</em>시간으로</span
                ><span class="spl show">받아올림하고</span><span class="spl show">16분이</span
                ><span class="spl show">됩니다.</span><span class="spl show">시 단위는</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46117_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">받아올림한</span><span class="spl show">1시간과</span
                ><span class="spl show">4시,</span><span class="spl show">2시간을</span
                ><span class="spl show">더하면</span><span class="spl show">7시가 되어</span
                ><span class="spl show">계산한 시각은</span><span class="spl show">7시 16분</span
                ><span class="spl show">10초가 됩니다.</span
                ><i class="math"
                  ><img src="https://dailykor.com/data/math/training_img/46126_training_1.jpg" alt="이미지" /></i></p
              ><p
                ><br /><span class="spl show">&nbsp;이번에는</span><span class="spl show">받아내림이</span
                ><span class="spl show">없는</span><span class="spl show">시간의 뺄셈을</span
                ><span class="spl show">계산해 봅니다.</span><span class="spl show">예를 들어</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46133_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">9시 45분</span><span class="spl show">30초에서</span
                ><span class="spl show">1시간 20분</span><span class="spl show">15초를 빼면,</span
                ><span class="spl show">초 단위는</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46138_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">30초에서</span><span class="spl show">15초를 빼서</span
                ><span class="spl show">15초,</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46141_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">분 단위는</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46142_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">45분에서</span><span class="spl show">20분을 빼서</span
                ><span class="spl show">25분,</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46145_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">시 단위는</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46146_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">9시에서</span><span class="spl show">1시간을 빼서</span
                ><span class="spl show">8시가 되어</span><span class="spl show">계산한 시각은</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46150_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">8시 25분</span><span class="spl show">15초가 됩니다.</span
                ><i class="math"
                  ><img src="https://dailykor.com/data/math/training_img/46152_training_1.jpg" alt="이미지" /></i
                ><br /><span class="spl show">&nbsp;그리고</span><span class="spl show">받아내림이</span
                ><span class="spl show">있는</span><span class="spl show">시간의 뺄셈은</span
                ><span class="spl show">만약</span><span class="spl show">초 단위끼리</span
                ><span class="spl show">뺄 수 없을</span><span class="spl show">때에는</span
                ><span class="spl show">1분을</span><span class="spl show">60초로</span
                ><span class="spl show">받아내림하고,</span><span class="spl show">분 단위끼리</span
                ><span class="spl show">뺄 수 없을</span><span class="spl show">때에는</span
                ><span class="spl show">1시간을</span><span class="spl show">60분으로</span
                ><span class="spl show">받아<em class="blank__on">내림</em>합니다.</span><br /><span class="spl show"
                  >예를 들어</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46170_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">7시 30분</span><span class="spl show">20초에서</span
                ><span class="spl show">3시간 50분</span><span class="spl show">45초를</span
                ><span class="spl show">빼 봅니다.</span><span class="spl show">초 단위는</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46176_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">20초에서</span><span class="spl show">45초를</span
                ><span class="spl show">뺄 수 없으므로</span><span class="spl show">분 단위에서</span
                ><span class="spl show">1분을</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46181_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">60초로</span><span class="spl show">받아내림하여</span
                ><span class="spl show">계산하면</span><span class="spl show">60 + 20 – 45 =</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46185_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">35(초),</span><span class="spl show">분 단위는</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46187_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">받아내림한</span><span class="spl show">1분을 차감한</span
                ><span class="spl show">29분에서</span><span class="spl show">50분을</span
                ><span class="spl show">뺄 수 없으므로</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46192_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">시 단위에서</span><span class="spl show">1시간을</span
                ><span class="spl show">60분으로</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46195_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">받아내림하여</span><span class="spl show">계산하면</span
                ><span class="spl show">60 + 29 – 50 =</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46198_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">39(분)이</span><span class="spl show">됩니다.</span
                ><span class="spl show">시 단위는</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46201_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">받아내림한</span><span class="spl show">1시간을</span
                ><span class="spl show">차감한</span><span class="spl show">6시에서</span
                ><i class="math" style="display: none"
                  ><img src="https://dailykor.com/data/math/training_img/46205_training_1.jpg" alt="이미지" /></i
                ><span class="spl show">3시간을 빼면</span><span class="spl show">3시가 되어</span
                ><span class="spl show">계산한 시각은</span><span class="spl show">3시 39분</span
                ><span class="spl show">35초가 됩니다.</span
                ><i class="math"
                  ><img src="https://dailykor.com/data/math/training_img/46210_training_1.jpg" alt="이미지" /></i></p
              ><p
                ><br /><span class="spl show">시간 + 시간 =</span><span class="spl show">시각인가요,</span
                ><span class="spl show">시간인가요?,</span><span class="spl show">시각 + 시간 =</span
                ><span class="spl show">시각인가요,</span><span class="spl show">시간인가요?</span><br /><span
                  class="spl show"
                  >&nbsp;시간의</span
                ><span class="spl show">덧셈과 뺄셈은</span><span class="spl show">시각과 시간을</span
                ><span class="spl show">더하는 경우,</span><span class="spl show">시간과 시간을</span
                ><span class="spl show">빼는 경우 등</span><span class="spl show">다양한 경우가</span
                ><span class="spl show">있습니다.</span><span class="spl show">간단히</span
                ><span class="spl show">정리해 보면</span><span class="spl show">다음과</span
                ><span class="spl show">같습니다.</span><br /><span class="spl show">&nbsp;첫째,</span
                ><span class="spl show">(시각)＋(시간)＝</span><span class="spl show">(시각)입니다.</span
                ><span class="spl show">예를 들어</span><span class="spl show">1시에서</span
                ><span class="spl show">3시간 후는</span><span class="spl show">4시입니다.</span><br /><span
                  class="spl show"
                  >&nbsp;둘째,</span
                ><span class="spl show">(시간)＋(시간)＝</span><span class="spl show">(시간)입니다.</span
                ><span class="spl show">예를 들어</span><span class="spl show">3시간과</span
                ><span class="spl show">2시간의 합은</span><span class="spl show">5시간입니다.</span><br /><span
                  class="spl show"
                  >&nbsp;셋째,</span
                ><span class="spl show">(시간)－(시간)＝</span><span class="spl show">(시간)입니다.</span
                ><span class="spl show">예를 들어</span><span class="spl show">6시간과</span
                ><span class="spl show">2시간의 차는</span><span class="spl show">4시간입니다.</span><br /><span
                  class="spl show"
                  >&nbsp;넷째,</span
                ><span class="spl show">(시각)－(시간)＝</span><span class="spl show">(시각)입니다.</span
                ><span class="spl show">예를 들어</span><span class="spl show">5시의</span
                ><span class="spl show">1시간 전은</span
                ><span class="spl show"><em class="blank__on">4</em>시입니다.</span><br /><span class="spl show"
                  >&nbsp;다섯째,</span
                ><span class="spl show">(시각)－(시각)＝</span><span class="spl show">(시간)입니다.</span
                ><span class="spl show">예를 들어</span><span class="spl show">2시와 4시</span
                ><span class="spl show">사이는</span><span class="spl show">2시간입니다.</span></p
              ></article
            >
          </div>
        </div>
      </div>

      <div class="frame col--sm">
        <div class="frame__head title_area">
          <h3 class="title">개념 요약</h3>
        </div>
        <div class="frame__body">
          <div class="theorem_area">
            <div class="btns">
              <button class="btn--gray">클릭!</button>
            </div>
            <div class="theorem">
              <p>① 받아올림이 한 번 있는 (세 자리 수) + (세 자리 수)</p>
              <p>같은 자리의 수끼리 합이 10이거나 10보다 크면 바로 윗자리로 1을 받아올림하여 계산합니다.</p>
              <p>받아올림을 하면 바로 윗자리의 수는 1이 커지게 됩니다. </p>
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
