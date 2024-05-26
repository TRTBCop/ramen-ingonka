<template>
  <div id="wrap">
    <div class="login_wrap">
      <header>
        <a href="/html/brand-Index" class="logo col" title="리딩수학">
          <img src="../../assets/img/math/logo.svg" alt="" />
        </a>
      </header>
      <div class="grid pv-0">
        <div class="frame col">
          <div class="container--sm">
            <slot></slot>
          </div>
        </div>

        <div class="frame col bg--gray">
          <h2 class="brand_logo"></h2>
          <ul class="login_slide">
            <!--            <li>-->
            <!--              <img src="../../assets/img/math/login_slide_img01.svg" alt="" />-->
            <!--              <p>지문을 끊어 읽으며 핵심 개념을 정리해보아요</p>-->
            <!--            </li>-->
            <!--            <li>-->
            <!--              <img src="../../assets/img/math/login_slide_img02.svg" alt="" />-->
            <!--              <p>다양한 유형의 문제를 풀며 학습한 개념을 문제에 적용해봐요</p>-->
            <!--            </li>-->
            <!--            <li>-->
            <!--              <img src="../../assets/img/math/login_slide_img03.svg" alt="" />-->
            <!--              <p>긴 문장으로 된 문제를 끊어 읽고 해결하는 법을 익혀요</p>-->
            <!--            </li>-->
            <li>
              <img src="../../assets/img/math/login_slide_img04.svg" alt="" />
              <p>학습을 진행하며 행성을 꾸며봐요</p>
            </li>
            <!--            <li>-->
            <!--              <img src="../../assets/img/math/login_slide_img05.svg" alt="" />-->
            <!--              <p>학습 기록과 보고서로 나의 학습 정보를 확인해요</p>-->
            <!--            </li>-->
          </ul>
          <ol class="login_slide__breadcrumb row">
            <li class="on">1</li>
            <li class="">2</li>
            <li class="">3</li>
            <li class="">4</li>
            <li class="">5</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  document.querySelector('meta[name=viewport]')?.setAttribute('content', 'width=device-width,initial-scale=1');
</script>
