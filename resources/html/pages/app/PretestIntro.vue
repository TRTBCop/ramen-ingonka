<template>
  <!-- 진단평가 / 인트로 / 기본 -->
  <AppLayout :theme-show="4">
    <div class="grid">
      <div class="frame col">
        <div class="frame__head title_area"> </div>
        <div class="frame__body">
          <div class="frame__container">
            <img src="@/assets/img/math/ico/pretest_intro.svg" alt="" />
            <h2>수리문해력 종합진단평가</h2>
            <p
              >평가는 현재 수학 학습 성취도 수준을 확인하는 것으로 학기별로 한 번씩만 응시 가능합니다. 본 평가 진행
              시간은 40분이고, 평가를 시작하면 도중에 중단할 수 없으니 충분한 시간을 확보한 후 편안한 마음가짐으로
              시작하시기 바랍니다.</p
            >
            <div class="frame__box">
              <dl>
                <dt>수학적역량별 진단</dt>
                <dd
                  >글 속에 숨어 있는 수학적 정보들을 찾아내어 문제상황을 해결하는 수리문해력뿐만 아니라 수학적 문제
                  해결력, 추론력, 계산력까지 평가할 수 있는 문항들로 구성하였습니다.</dd
                >
                <dt>내용영역별 진단</dt>
                <dd
                  >교육과정의 내용 범주를 기초로 수와 연산, 도형과 측정, 규칙성, 자료와 가능성의 각 계통별로 다양한
                  형태의 문항들로 구성하였습니다.</dd
                >
                <dt>단원별 진단</dt>
                <dd>교육과정의 내용 범주를 기초로 각 학기의 대단원별 문항들로 구성하였습니다.</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="frame col--sm">
        <div class="frame__head title_area">
          <h3 class="title">학기를 선택해주세요.</h3>
        </div>
        <div class="frame__body">
          <ul class="pretest__semester">
            <li>
              <h3>3학년 1학기</h3>
              <div class="row">
                <div class="">
                  <strong>평가 완료</strong>
                  <p> 2023-06-08 </p>
                </div>
                <button><font-awesome-icon icon="fa-regular fa-file-chart-column"></font-awesome-icon></button>
              </div>
            </li>
            <li>
              <h3>3학년 1학기</h3>
              <div class="row">
                <div class="">
                  <strong>평가 완료</strong>
                  <p> 2023-06-08 </p>
                </div>
                <button><font-awesome-icon icon="fa-regular fa-file-chart-column"></font-awesome-icon></button>
              </div>
            </li>
            <li class="current">
              <h3>3학년 1학기</h3>
              <!--              <div class="row">-->
              <!--                <div class="">-->
              <!--                  <strong>평가 완료</strong>-->
              <!--                  <p> 2023-06-08 </p>-->
              <!--                </div>-->
              <!--                <button><font-awesome-icon icon="fa-regular fa-file-chart-column"></font-awesome-icon></button>-->
              <!--              </div>-->
            </li>
          </ul>
        </div>
      </div>
    </div>
  </AppLayout>
  <!-- 진단평가 / 인트로 / 진행하지 않은 학기 선택 -->
  <AppLayout :theme-show="4">
    <div class="grid">
      <div class="frame col">
        <div class="frame__head title_area"> </div>
        <div class="frame__body">
          <div class="frame__container">
            <h2>4학년 2학기 <br />수리문해력 종합진단평가</h2>
            <div class="frame__box">
              <ul class="pretest__info">
                <li>진단평가의 제한 시간은 40분입니다.</li>
                <li>한번 완료한 진단평가는 다시 볼 수 없습니다.</li>
                <li>진단평가는 시작 후 중단할 수 없으며 진행 중 강제 로그아웃 시 재시작 할 수 없습니다.</li>
              </ul>
            </div>
            <p
              >준비가 되었으면 아래 버튼을 눌러 <br />
              진단평가를 시작하세요.</p
            >
            <button class="btn--gray">시작하기</button>
          </div>
        </div>
      </div>

      <div class="frame col--sm">
        <div class="frame__head title_area">
          <h3 class="title">학기를 선택해주세요.</h3>
        </div>
        <div class="frame__body">
          <ul class="pretest__semester">
            <li>
              <h3>3학년 1학기</h3>
              <div class="row">
                <div class="">
                  <strong>평가 완료</strong>
                  <p> 2023-06-08 </p>
                </div>
                <button><font-awesome-icon icon="fa-regular fa-file-chart-column"></font-awesome-icon></button>
              </div>
            </li>
            <li>
              <h3>3학년 1학기</h3>
              <div class="row">
                <div class="">
                  <strong>평가 완료</strong>
                  <p> 2023-06-08 </p>
                </div>
                <button><font-awesome-icon icon="fa-regular fa-file-chart-column"></font-awesome-icon></button>
              </div>
            </li>
            <li class="current">
              <h3>3학년 1학기</h3>
              <!--              <div class="row">-->
              <!--                <div class="">-->
              <!--                  <strong>평가 완료</strong>-->
              <!--                  <p> 2023-06-08 </p>-->
              <!--                </div>-->
              <!--                <button><font-awesome-icon icon="fa-regular fa-file-chart-column"></font-awesome-icon></button>-->
              <!--              </div>-->
            </li>
          </ul>
        </div>
      </div>
    </div>
  </AppLayout>
  <!-- 진단평가 / 인트로 / 진행한 학기 선택  -->
  <AppLayout :theme-show="4">
    <div class="grid">
      <div class="frame col">
        <div class="frame__head title_area"> </div>
        <div class="frame__body">
          <div class="frame__container">
            <h2>4학년 2학기 <br />수리문해력 종합진단평가 결과</h2>
            <div class="result__box">
              <ul class="">
                <li>
                  <font-awesome-icon icon="fa-regular fa-ballot-check"></font-awesome-icon>
                  <div>
                    <p class="is--gray">문항 정답 수<span>10개</span></p>
                    <p>총 문항 수<span>18개</span></p>
                  </div>
                </li>
                <li>
                  <font-awesome-icon icon="fa-regular fa-ballot-check"></font-awesome-icon>
                  <div>
                    <p class="is--gray">풀이 답안 정답 수<span>120개</span></p>
                    <p>총 풀이 답안 수<span>150개</span></p>
                  </div>
                </li>
                <li>
                  <font-awesome-icon icon="fa-regular fa-timer"></font-awesome-icon>
                  <div>
                    <p class="is--gray">소요 시간<span>59분 14초</span></p>
                    <p>기준 시간<span>40분 00초</span></p>
                  </div>
                </li>
                <li>
                  <font-awesome-icon icon="fa-regular fa-user"></font-awesome-icon>
                  <div>
                    <p class="is--gray">나의 점수<span>80점</span></p>
                    <p class="is--gray">나의 레벨<span>3레벨</span></p>
                  </div>
                </li>
              </ul>
            </div>
            <p>
              보다 자세한 내용은<br />
              진단평가 보고서를 통해 확인하세요.
            </p>
            <button class="btn--brand">보고서 보기</button>
          </div>
        </div>
      </div>

      <div class="frame col--sm">
        <div class="frame__head title_area">
          <h3 class="title">학기를 선택해주세요.</h3>
        </div>
        <div class="frame__body">
          <ul class="pretest__semester">
            <li>
              <h3>3학년 1학기</h3>
              <div class="row">
                <div class="">
                  <strong>평가 완료</strong>
                  <p> 2023-06-08 </p>
                </div>
                <button><font-awesome-icon icon="fa-regular fa-file-chart-column"></font-awesome-icon></button>
              </div>
            </li>
            <li>
              <h3>3학년 1학기</h3>
              <div class="row">
                <div class="">
                  <strong>평가 완료</strong>
                  <p> 2023-06-08 </p>
                </div>
                <button><font-awesome-icon icon="fa-regular fa-file-chart-column"></font-awesome-icon></button>
              </div>
            </li>
            <li class="current">
              <h3>3학년 1학기</h3>
              <!--              <div class="row">-->
              <!--                <div class="">-->
              <!--                  <strong>평가 완료</strong>-->
              <!--                  <p> 2023-06-08 </p>-->
              <!--                </div>-->
              <!--                <button><font-awesome-icon icon="fa-regular fa-file-chart-column"></font-awesome-icon></button>-->
              <!--              </div>-->
            </li>
          </ul>
        </div>
      </div>
    </div>
  </AppLayout>
  <!-- 진단평가 / 인트로 / 기본 (초등, 중등 구분 탭)  -->
  <AppLayout :theme-show="4">
    <div class="grid">
      <div class="frame col">
        <div class="frame__head title_area"> </div>
        <div class="frame__body">
          <div class="frame__container">
            <img src="@/assets/img/math/ico/pretest_intro.svg" alt="" />
            <h2>수리문해력 종합진단평가</h2>
            <p
              >평가는 현재 수학 학습 성취도 수준을 확인하는 것으로 학기별로 한 번씩만 응시 가능합니다. 본 평가 진행
              시간은 40분이고, 평가를 시작하면 도중에 중단할 수 없으니 충분한 시간을 확보한 후 편안한 마음가짐으로
              시작하시기 바랍니다.</p
            >
            <div class="frame__box">
              <dl>
                <dt>수학적역량별 진단</dt>
                <dd
                  >글 속에 숨어 있는 수학적 정보들을 찾아내어 문제상황을 해결하는 수리문해력뿐만 아니라 수학적 문제
                  해결력, 추론력, 계산력까지 평가할 수 있는 문항들로 구성하였습니다.</dd
                >
                <dt>내용영역별 진단</dt>
                <dd
                  >교육과정의 내용 범주를 기초로 수와 연산, 도형과 측정, 규칙성, 자료와 가능성의 각 계통별로 다양한
                  형태의 문항들로 구성하였습니다.</dd
                >
                <dt>단원별 진단</dt>
                <dd>교육과정의 내용 범주를 기초로 각 학기의 대단원별 문항들로 구성하였습니다.</dd>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <div class="frame col--sm">
        <div class="frame__head tab_area">
          <ul class="tabs">
            <li class="current">초등</li>
            <li class="">증등</li>
          </ul>
        </div>
        <div class="frame__body">
          <ul class="pretest__semester">
            <li>
              <h3>3학년 1학기</h3>
              <div class="row">
                <div class="">
                  <strong>평가 완료</strong>
                  <p> 2023-06-08 </p>
                </div>
                <button><font-awesome-icon icon="fa-regular fa-file-chart-column"></font-awesome-icon></button>
              </div>
            </li>
            <li>
              <h3>3학년 1학기</h3>
              <div class="row">
                <div class="">
                  <strong>평가 완료</strong>
                  <p> 2023-06-08 </p>
                </div>
                <button><font-awesome-icon icon="fa-regular fa-file-chart-column"></font-awesome-icon></button>
              </div>
            </li>
            <li class="current">
              <h3>3학년 1학기</h3>
              <!--              <div class="row">-->
              <!--                <div class="">-->
              <!--                  <strong>평가 완료</strong>-->
              <!--                  <p> 2023-06-08 </p>-->
              <!--                </div>-->
              <!--                <button><font-awesome-icon icon="fa-regular fa-file-chart-column"></font-awesome-icon></button>-->
              <!--              </div>-->
            </li>
          </ul>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
  import AppLayout from '@/html/layouts/AppLayout.vue';
  import AppBlanks from '@/html/components/app/AppBlanks.vue';
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
</script>
