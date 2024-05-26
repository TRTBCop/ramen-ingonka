<template>
  <div id="wrap">
    <AppHeader />
    <div class="result_wrap training__theme" :class="themeTypeClass">
      <div class="result__head">
        <h4>
          십의 자리에서 올림이 있는 (몇십몇)x(몇), 일의 자리에서 올림이 있는 (몇십몇)x(몇)
          <font-awesome-icon></font-awesome-icon>
        </h4>
        <div>
          <strong class="badge__time">훈련 완료 일시<span>2024-01-15 15:40</span></strong>
          <strong class="badge__time">훈련 소요 시간<span>01:40:20</span></strong>
          <small class="is--empha">기준 시간보다 n분 더 걸렸어요.</small>
        </div>
      </div>
      <div class="result__body">
        <div class="result__rank__total">
          <div class="star__card star__card--a">
            <i class="star star--a"></i>
          </div>
          <button class="btn__layer"><i class="fa-regular fa-circle-info"></i></button>
          <div class="result__rank__total__layer">
            <div>
              <dl>
                <dt>최종 훈련 점수</dt>
                <dd>78점</dd>
                <dt><i>－</i>60분 초과</dt>
                <dd>10점</dd>
                <hr />
                <dt>나의 점수</dt>
                <dd>68점</dd>
              </dl>
            </div>
            <button class="btn__layer btn__layer__close"><i class="fa-regular fa-circle-xmark"></i></button>
          </div>
        </div>
        <table class="tbl__result">
          <colgroup>
            <col style="width: 18%" />
            <col style="width: 18%" />
            <col style="width: 18%" />
            <col style="width: 18%" />
            <col style="width: 18%" />
            <col style="width: 8%" />
          </colgroup>
          <thead>
            <tr>
              <th>학습 구분</th>
              <th>학습 점수</th>
              <th>정답수</th>
              <th>문제수</th>
              <th>성취도</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>개념 학습</th>
              <td><i class="rank rank--s rank--sm"></i></td>
              <td>10</td>
              <td>10</td>
              <td>100%</td>
              <td
                ><button @click="router.get('/html/app-ResultTotalLayer')"
                  ><i class="fa-regular fa-magnifying-glass"></i></button
              ></td>
            </tr>
            <tr>
              <th>기초 연산</th>
              <td><i class="rank rank--a rank--sm"></i></td>
              <td>10</td>
              <td>10</td>
              <td>100%</td>
              <td
                ><button @click="router.get('/html/app-ResultTotalLayer')"
                  ><i class="fa-regular fa-magnifying-glass"></i></button
              ></td>
            </tr>
            <tr>
              <th>개념 학습</th>
              <td><i class="rank rank--b rank--sm"></i></td>
              <td>10</td>
              <td>10</td>
              <td>100%</td>
              <td
                ><button @click="router.get('/html/app-ResultTotalLayer')"
                  ><i class="fa-regular fa-magnifying-glass"></i></button
              ></td>
            </tr>
            <tr>
              <th>개념 학습</th>
              <td><i class="rank rank--c rank--sm"></i></td>
              <td>10</td>
              <td>10</td>
              <td>100%</td>
              <td
                ><button @click="router.get('/html/app-ResultTotalLayer')"
                  ><i class="fa-regular fa-magnifying-glass"></i></button
              ></td>
            </tr>
            <tr>
              <th>전체</th>
              <td></td>
              <td>10</td>
              <td>10</td>
              <td>100%</td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="result__tip">
        <i class="result--character"></i>
        <p
          >진방울님의 개념 훈련 결과는 100점 만점 중 75점입니다. <br />
          개념을 전반적으로 알고 있지만 깊은 이해가 부족합니다.
          <br />
          핵심 개념을 스스로 정리해 본 뒤 실수가 없도록 집중하여 기초 연산을 다시 풀어보세요.</p
        >
      </div>
      <div class="frame__bottom">
        <div class="row">
          <button class="btn--primary"><i class="fa-regular fa-home"></i>홈으로</button>
          <button class="btn--gray"><i class="fa-regular fa-note"></i>오답노트 확인하기</button>
        </div>
        <!--        <div class="row">-->
        <!--          <button class="btn&#45;&#45;gray"><i class="fa-regular fa-arrow-rotate-right"></i>다시 도전하기</button>-->
        <!--          <button class="btn&#45;&#45;gray"><i class="fa-regular fa-arrow-right"></i>다음 훈련으로</button>-->
        <!--        </div>-->
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import AppHeader from '@/html/components/app/AppHeader.vue';
  import { router } from '@inertiajs/vue3';
  import { computed, PropType } from 'vue';

  const props = defineProps({
    themeShow: {
      type: Number as PropType<number>,
      default: 1,
    },
  });

  const themeTypeClass = computed(() => {
    if (props.themeShow === 1) {
      return 'theme--textbook';
    } else if (props.themeShow === 2) {
      return 'theme--question';
    } else if (props.themeShow === 3) {
      return 'theme--descriptive';
    }
    return '';
  });
</script>
