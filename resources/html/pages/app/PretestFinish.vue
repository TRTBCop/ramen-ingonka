<template>
  <AppLayout :theme-show="4">
    <div class="container--sm">
      <div class="pretest__result">
        <h2 class="txt--center">4학년 2학기 <br />수리문해력 종합진단평가 결과</h2>
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
        <p class="txt--center"
          >보다 자세한 내용은 <br />
          진단평가 보고서를 통해 확인하세요.</p
        >
        <div class="btns">
          <button class="btn--brand">보고서 보기</button>
        </div>
        <ul class="links">
          <li>
            <a href="javascript:;"> <font-awesome-icon icon="fa-regular fa-share-nodes" /></a>
            <p>결과 공유</p>
          </li>
          <li>
            <a href="javascript:;"> <font-awesome-icon icon="fa-regular fa-file-magnifying-glass" /></a>
            <p
              >다른 학기 <br />
              진단</p
            >
          </li>
          <li>
            <a href="javascript:;"> <font-awesome-icon icon="fa-regular fa-layer-group" /></a>
            <p
              >수리문해력 <br />
              높이기</p
            >
          </li>
        </ul>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
  import AppLayout from '@/html/layouts/AppLayout.vue';
</script>

<!--<template>-->
<!--  <AppPretest page-name="addIntro">-->
<!--    <div class="info_page&#45;&#45;sub">-->
<!--      <div class="info__title">-->
<!--        <span class="is&#45;&#45;gray">김리딩 학생</span>-->
<!--        <p class="is&#45;&#45;size3"><strong>초등 5학년 1학기</strong>진단평가 결과</p>-->
<!--      </div>-->
<!--      <ul class="lst__result">-->
<!--        <li>-->
<!--          <font-awesome-icon icon="fa-regular fa-ballot-check" />-->
<!--          <dl>-->
<!--            <dt>문항 정답 수</dt>-->
<!--            <dd><span>10</span>개</dd>-->
<!--            <dt class="standard">총 문항 수</dt>-->
<!--            <dd class="standard"><span>18</span>개</dd>-->
<!--          </dl>-->
<!--        </li>-->
<!--        <li>-->
<!--          <font-awesome-icon icon="fa-regular fa-ballot-check" />-->
<!--          <dl>-->
<!--            <dt>풀이 답안 정답 수</dt>-->
<!--            <dd><span>120</span>개</dd>-->
<!--            <dt class="standard">총 풀이 답안 수</dt>-->
<!--            <dd class="standard"><span>150</span>개</dd>-->
<!--          </dl>-->
<!--        </li>-->
<!--        <li>-->
<!--          <font-awesome-icon icon="fa-regular fa-timer" />-->
<!--          <dl>-->
<!--            <dt>소요 시간</dt>-->
<!--            <dd><span>59분 14초</span></dd>-->
<!--            <dt class="standard">기준 시간</dt>-->
<!--            <dd class="standard"><span>40분 00초</span></dd>-->
<!--          </dl>-->
<!--        </li>-->
<!--        <li>-->
<!--          <font-awesome-icon icon="fa-regular fa-user" />-->
<!--          <dl>-->
<!--            <dt>나의 점수</dt>-->
<!--            <dd><span>80</span>점</dd>-->
<!--            <dt>나의 레벨</dt>-->
<!--            <dd><span>3</span>레벨</dd>-->
<!--          </dl>-->
<!--        </li>-->
<!--      </ul>-->

<!--      <div>-->
<!--        <p>보다 자세한 내용은 진단평가 보고서를 통해 확인하세요.</p>-->
<!--        <div class="btns mt-1">-->
<!--          <button class="btn&#45;&#45;primary">보고서 보기</button>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--  </AppPretest>-->
<!--</template>-->

<!--<script setup lang="ts">-->
<!--  import AppPretest from '@/html/layouts/AppPretest.vue';-->
<!--</script>-->
