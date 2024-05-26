<template>
  <AppResult :theme-show="2"> </AppResult>
  <div class="layer ly__result">
    <div class="inner">
      <div class="frame__head title_area">
        <h3 class="title">
          <font-awesome-icon class="is--warning" icon="fa-regular fa-sparkles"></font-awesome-icon>
          <strong>개념 학습 완료!</strong>
        </h3>
      </div>

      <!-- [D] 프로그래스 색상 : .result__progress--s/a/b/c-->
      <!-- [D] 랭크 : .rank.rank--s/a/b/c, 기본 사이즈 : .rank--lg 둘다 바뀌어야함-->
      <div class="frame__body">
        <div class="result__summary">
          <h2>분모가 같은 분수의 크기 비교, 단위분수의 크기 비교</h2>
          <div class="result__progress result__progress--s">
            <i class="rank rank--s rank--lg"></i>
            <strong>100%</strong>
            <progress max="100" value="80"></progress>
          </div>
        </div>

        <div class="result__box result__box--white">
          <div class="row">
            <i class="ico__correct"></i>
            <h3>개념 요약</h3>
          </div>
          <ul class="lst__result">
            <li>
              <span>정답수</span>
              <p>10</p>
            </li>
            <li>
              <span>문제수</span>
              <p>14</p>
            </li>
            <li class="lst__result__percent">
              <span>정답률</span>
              <p>4%</p>
            </li>
          </ul>
        </div>

        <div class="result__box result__box--white">
          <div class="row">
            <i class="ico__wrong"></i>
            <h3>개념 다지기</h3>
          </div>
          <ul class="lst__result">
            <li>
              <span>정답수</span>
              <p>4</p>
            </li>
            <li>
              <span>문제수</span>
              <p>4</p>
            </li>
            <li class="lst__result__percent">
              <span>정답률</span>
              <p>4</p>
            </li>
          </ul>
        </div>

        <div class="result__box result__box--white">
          <div class="row">
            <button class="btn_dropdown"></button>
            <h3>1차 유형 문제</h3>
            <button class="btn--gray"><i class=""></i>해설보기</button>
          </div>

          <ul class="lst__result">
            <li>
              <span>정답수</span>
              <p>4</p>
            </li>
            <li>
              <span>문제수</span>
              <p>4</p>
            </li>
            <li class="lst__result__percent">
              <span>정답률</span>
              <p>4</p>
            </li>
          </ul>
          <ol class="result__score">
            <li class="correct"><i></i></li>
            <li class="correct"><i></i></li>
            <li class="correct"><i></i></li>
            <li class="correct"><i></i></li>
            <li class="wrong"><i></i></li>
            <li class="wrong"><i></i></li>
            <li class="correct"><i></i></li>
            <li class="wrong"><i></i></li>
            <li class="correct"><i></i></li>
            <li class="wrong"><i></i></li>
            <li class="correct"><i></i></li>
            <li class="wrong"><i></i></li>
            <li class="correct"><i></i></li>
            <li class="wrong"><i></i></li>
          </ol>
        </div>

        <div class="result__box result__box--gray">
          <div class="row">
            <h3>전체</h3>
            <button class="btn--gray"><i class=""></i>해설보기</button>
          </div>
          <ul class="lst__result">
            <li>
              <span>정답수</span>
              <p>8</p>
            </li>
            <li>
              <span>문제수</span>
              <p>4</p>
            </li>
            <li class="lst__result__percent">
              <span>정답률</span>
              <p>70%</p>
            </li>
          </ul>
        </div>
      </div>
      <div class="btns btns--center">
        <button class="btn--sub" @click="router.get('/html/app-ResultTotal')">닫기</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import AppResult from '@/html/layouts/AppResult.vue';
  import { router } from '@inertiajs/vue3';
</script>
