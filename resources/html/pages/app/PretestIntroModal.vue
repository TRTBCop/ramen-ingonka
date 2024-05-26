<template>
  <AppPretest page-name="addIntro" :is-show="true">
    <div class="info_page">
      <div>
        <img src="../../../assets/img/math/info_logo.svg" alt="" />
        <h2>수리문해력 종합진단평가</h2>
        <p class="">
          평가는 현재 수학 학습 성취도 수준을 확인하는 것으로 학기별로 한 번씩만 응시 가능합니다. <br />
          본 평가 진행 시간은 40분이고, 평가를 시작하면 도중에 중단할 수 없으니<br />
          충분한 시간을 확보한 후 편안한 마음가짐으로 시작하시기 바랍니다.
        </p>
      </div>

      <div class="box__result">
        <dl>
          <dt>수학적역량별 진단</dt>
          <dd class="mb-1">
            글 속에 숨어 있는 수학적 정보들을 찾아내어 문제상황을 해결하는 수리문해력뿐만 아니라 <br />수학적 문제
            해결력, 추론력, 계산력까지 평가할 수 있는 문항들로 구성하였습니다.
          </dd>
          <dt>내용영역별 진단</dt>
          <dd class="mb-1">
            교육과정의 내용 범주를 기초로 수와 연산, 도형과 측정, 규칙성, 자료와 가능성의 각 계통별로 <br />다양한
            형태의 문항들로 구성하였습니다.
          </dd>
          <dt>단원별 진단</dt>
          <dd> 교육과정의 내용 범주를 기초로 각 학기의 대단원별 문항들로 구성하였습니다. </dd>
        </dl>
      </div>

      <ul class="tabs">
        <li class="current">초등</li>
        <li>중등</li>
      </ul>

      <div class="tab_content show">
        <ul class="level_wrap">
          <li class="result">
            <a href="javascript:;">
              <strong>3학년 1학기</strong>
              <span>2023-06-08</span>
            </a>
          </li>
          <li class="result">
            <a href="javascript:;">
              <strong>3학년 2학기</strong>
              <span>2023-06-08</span>
            </a>
          </li>
          <li class="result">
            <a href="javascript:;">
              <strong>4학년 1학기</strong>
              <span>2023-06-08</span>
            </a>
          </li>
          <li>
            <strong>
              <a href="javascript:;">4학년 2학기</a>
            </strong>
          </li>
          <li>
            <strong><a href="javascript:;">5학년 1학기</a></strong>
          </li>
          <li>
            <strong><a href="javascript:;">5학년 2학기</a></strong>
          </li>
          <li>
            <strong><a href="javascript:;">6학년 1학기</a></strong>
          </li>
          <li>
            <strong><a href="javascript:;">6학년 2학기</a></strong>
          </li>
        </ul>
      </div>
    </div>

    <div class="dim">
      <div class="modal modal--md">
        <div class="ly_head">
          <button class="btn--close">
            <font-awesome-icon icon="fa-regular fa-xmark" />
          </button>
        </div>
        <div class="ly_body">
          <strong class="is--size4">4학년 2학기 수리문해력 종합진단평가</strong>
          <ul class="mt-1">
            <li>진단평가의 제한 시간은 40분입니다.</li>
            <li>한번 완료한 진단평가는 다시 볼 수 없습니다.</li>
            <li>진단평가는 시작 후 중단할 수 없으며 진행 중 강제 로그아웃 시 재시작 할 수 없습니다.</li>
          </ul>
          <p class="mt-1">준비가 되었으면 아래 버튼을 눌러 진단평가를 시작하세요.</p>
          <div class="btns">
            <button class="btn--brand">시작하기</button>
          </div>
        </div>
      </div>
    </div>
  </AppPretest>
</template>

<script setup lang="ts">
  import AppPretest from '@/html/layouts/AppPretest.vue';
</script>
