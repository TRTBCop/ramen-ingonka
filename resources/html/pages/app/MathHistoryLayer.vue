<template>
  <div class="layer ly__history">
    <div class="inner">
      <div class="layer__head">
        <h2>개념 훈련 결과</h2>
        <ul class="row">
          <li>
            <h4>훈련 완료 일시</h4>
            <strong>2024-01-15 17:48</strong>
          </li>
          <li>
            <h4>소단원</h4>
            <strong>비례식과 비례배분 <small class="badge--lightgray">복습1</small></strong>
          </li>
        </ul>
      </div>
      <div class="layer__body">
        <div class="result_wrap">
          <div class="result__body">
            <div class="result__rank__total">
              <div class="star__card star__card--a">
                <i class="star star--a"></i>
              </div>
            </div>
            <table class="tbl__result">
              <colgroup>
                <col style="width: 30%" />
                <col style="width: 20%" />
                <col style="width: 20%" />
                <col style="width: 30%" />
              </colgroup>
              <thead>
                <tr>
                  <th>학습 구분</th>
                  <th>정답수</th>
                  <th>문제수</th>
                  <th>성취도</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th>개념 학습</th>
                  <td>10</td>
                  <td>10</td>
                  <td>100%</td>
                </tr>
                <tr>
                  <th>기초 연산</th>
                  <td>10</td>
                  <td>10</td>
                  <td>100%</td>
                </tr>
                <tr>
                  <th>개념 학습</th>
                  <td>10</td>
                  <td>10</td>
                  <td>100%</td>
                </tr>
                <tr>
                  <th>개념 학습</th>
                  <td>10</td>
                  <td>10</td>
                  <td>100%</td>
                </tr>
                <tr>
                  <th>전체</th>
                  <td>10</td>
                  <td>10</td>
                  <td>100%</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="btns btns--center">
        <button class="btn--sub">닫기</button>
        <button class="btn--brand">복습하기</button>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
  import AppHeader from '@/html/components/app/AppHeader.vue';
  import { router } from '@inertiajs/vue3';
</script>
