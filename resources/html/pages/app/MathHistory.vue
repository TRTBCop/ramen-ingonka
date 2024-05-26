<template>
  <AppHeader />
  <div class="board">
    <div class="hero">
      <div class="container">
        <h2>학습 기록</h2>
      </div>
    </div>
    <div class="container">
      <div class="history">
        <div class="grid">
          <div class="col--sm">
            <h3 class="pt-0">주차 선택</h3>
            <div class="history__calendar">
              <div class="history__calendar__head">
                <button><font-awesome-icon icon="fa-regular fa-chevron-left"></font-awesome-icon></button>
                <p>
                  <span>2024.03.18 (월)</span>
                  <span>~</span>
                  <span>2024.03.18 (월)</span>
                </p>
                <button><font-awesome-icon icon="fa-regular fa-chevron-right"></font-awesome-icon></button>
              </div>
              <div class="calendar">달력</div>
              <div class="btns">
                <button class="btn--brand">적용하기</button>
              </div>
            </div>
            <h3 class="">도움말 <font-awesome-icon icon="fa-regular fa-circle-info"></font-awesome-icon></h3>
            <ul class="box__history history__info">
              <li>
                학습기록은 상단에 표시된 1주일 기간 동안의 모든 학습기록을 실시간으로 확인할 수 있으며, 기본 설정 기간은
                오늘 포함 1주일입니다.
              </li>
              <li> 학습기록 기간의 종료일은 재설정이 가능하며, 재설정한 기간으로 학습기록이 자동 변경됩니다.</li>
              <li>
                소단원은 3가지 훈련(개념/유형/서술형)으로 구성되어 있으며 모든 훈련을 완료해야 해당 소단원 결과를 볼 수
                있습니다.
              </li>
            </ul>
          </div>
          <div class="col">
            <h3 class="pt-0">주차 성취도</h3>
            <ul class="history__week">
              <li>
                <span>소단원 수</span>
                <strong>2</strong>
              </li>
              <li>
                <span>훈련수</span>
                <strong>7</strong>
              </li>
              <li>
                <span>훈련시간(분)</span>
                <strong>290</strong>
              </li>
              <li>
                <span>총 정답수 / 문제수</span>
                <strong>172/220</strong>
              </li>
              <li>
                <span>정답률</span>
                <strong>78%</strong>
              </li>
              <li>
                <span>성취도</span>
                <strong>80</strong>
              </li>
            </ul>
            <h3>4주간 성취도</h3>
            <p>그래프</p>
            <table class="tbl__history">
              <colgroup>
                <col />
              </colgroup>
              <thead>
                <tr>
                  <th>구분</th>
                  <th>소단원수</th>
                  <th>훈련수</th>
                  <th>훈련시간</th>
                  <th>총 정답수/문제수(정답률)</th>
                  <th>성취도</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>이번주</td>
                  <td>2</td>
                  <td>7</td>
                  <td>290</td>
                  <td>172/220 (78%)</td>
                  <td>78</td>
                </tr>
                <tr>
                  <td>1주 전</td>
                  <td>2</td>
                  <td>7</td>
                  <td>290</td>
                  <td>172/220 (78%)</td>
                  <td>78</td>
                </tr>
                <tr>
                  <td>2주 전</td>
                  <td>2</td>
                  <td>7</td>
                  <td>290</td>
                  <td>172/220 (78%)</td>
                  <td>78</td>
                </tr>
                <tr>
                  <td>3주 전</td>
                  <td>2</td>
                  <td>7</td>
                  <td>290</td>
                  <td>172/220 (78%)</td>
                  <td>78</td>
                </tr>
              </tbody>
            </table>
            <h3>학습내용별 학습기록</h3>
            <div class="box__history">
              <div class="box__history__head">
                <div class="box__history__title">
                  <em><span>초등6-2</span>4단원 비례식과 비례배분</em>
                  <strong>비례식</strong>
                </div>
              </div>
              <div class="box__history__body">
                <div class="history__round">
                  <span>ROUND 1</span>
                  <div class="history__round__btns">
                    <button class=""
                      >개념
                      <span class="stars"><i class="star star--sm star--s"></i>90</span>
                      <i class="fa-regular fa-magnifying-glass"></i>
                    </button>
                    <button class=""
                      >유형
                      <span class="stars"><i class="star star--sm star--s"></i>90</span>
                      <i class="fa-regular fa-magnifying-glass"></i>
                    </button>
                    <button class=""
                      >서술형
                      <span class="stars"><i class="star star--sm star--s"></i>90</span>
                      <i class="fa-regular fa-magnifying-glass"></i>
                    </button>
                    <div class="history__round__score">73</div>
                  </div>
                </div>
                <div class="history__round">
                  <span>ROUND 2</span>
                  <div class="history__round__btns">
                    <button class=""
                      >개념
                      <span class="stars"><i class="star star--sm star--s"></i>90</span>
                      <i class="fa-regular fa-magnifying-glass"></i>
                    </button>
                    <button class=""
                      >유형
                      <span class="stars"><i class="star star--sm star--s"></i>90</span>
                      <i class="fa-regular fa-magnifying-glass"></i>
                    </button>
                    <button class=""
                      >서술형
                      <span class="stars"><i class="star star--sm star--s"></i>90</span>
                      <i class="fa-regular fa-magnifying-glass"></i>
                    </button>
                    <div class="history__round__score high">100</div>
                  </div>
                </div>
                <div class="history__round">
                  <span class="">ROUND 3</span>
                  <div class="history__round__btns">
                    <button class=""
                      >개념
                      <span class="stars"><i class="star star--sm star--s"></i>90</span>
                      <i class="fa-regular fa-magnifying-glass"></i>
                    </button>
                    <button class=""
                      >유형
                      <span class="stars"><i class="star star--sm star--s"></i>90</span>
                      <i class="fa-regular fa-magnifying-glass"></i>
                    </button>
                    <button class=""
                      >서술형
                      <span class="stars"><i class="star star--sm star--s"></i>90</span>
                      <i class="fa-regular fa-magnifying-glass"></i>
                    </button>
                    <div class="history__round__score low">73</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="box__history">
              <div class="box__history__head">
                <div class="box__history__title">
                  <em><span>초등6-2</span>4단원 비례식과 비례배분</em>
                  <strong>비례식</strong>
                </div>
              </div>
              <div class="box__history__body">
                <div class="history__round history__round--type1">
                  <span>ROUND 1</span>
                  <div class="history__round__btns">
                    <button class=""
                      >개념
                      <span class="stars"><i class="star star--sm star--s"></i>90</span>
                      <i class="fa-regular fa-magnifying-glass"></i>
                    </button>
                    <button class=""
                      >유형
                      <span class="stars"><i class="star star--sm star--s"></i>90</span>
                      <i class="fa-regular fa-magnifying-glass"></i>
                    </button>
                    <button class=""
                      >서술형
                      <span class="stars"><i class="star star--sm star--s"></i>90</span>
                      <i class="fa-regular fa-magnifying-glass"></i>
                    </button>
                    <div class="history__round__score">73</div>
                  </div>
                </div>
                <div class="history__round history__round--type1">
                  <span>ROUND 1</span>
                  <div class="history__round__btns">
                    <button class=""
                      >개념
                      <span class="stars"><i class="star star--sm star--a"></i>90</span>
                      <i class="fa-regular fa-magnifying-glass"></i>
                    </button>
                    <button class="">유형 <strong>학습중</strong></button>
                    <button class="">유형 <strong>학습하기</strong></button>
                    <div class="history__round__score disabled"></div>
                  </div>
                </div>
              </div>
            </div>

            <h3>학습일시별 학습기록</h3>
            <div class="box__history">
              <div class="box__history__head">
                <div class="box__history__date">
                  <strong>2024.03.22</strong>
                </div>
              </div>
              <div class="box__history__body">
                <div class="history__round history__round--type2">
                  <div class="box__history__title">
                    <em class="row">
                      <small class="time"
                        ><font-awesome-icon icon="fa-regular fa-play"></font-awesome-icon><b>시작</b>09:40</small
                      ><span>초등6-2</span>4단원 비례식과 비례배분
                    </em>
                    <strong class="row"
                      ><small class="time"
                        ><font-awesome-icon icon="fa-regular fa-hourglass-clock"></font-awesome-icon
                        ><b>학습</b>48분</small
                      >
                      <p>올림이 없는 (세 자리 수)x(한 자리 수), 올림이 있는 (세 자리 수)x(한 자리 수)</p></strong
                    >
                  </div>
                  <div class="history__round__btns">
                    <div class="history__round__step">최초</div>
                    <button class=""
                      >개념
                      <span class="stars"><i class="star star--sm star--s"></i>90</span>
                      <i class="fa-regular fa-magnifying-glass"></i>
                    </button>
                  </div>
                </div>
              </div>
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
