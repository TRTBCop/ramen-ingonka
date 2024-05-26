<template>
  <swiper
    :autoplay="{
      delay: autoPlayDelay,
    }"
    :modules="[Autoplay, Pagination]"
    :pagination="{
      clickable: true,
    }"
    wrapper-tag="ul"
    class="login_slide"
  >
    <swiper-slide v-for="(item, i) in loginSlideItems" :key="i" tag="li">
      <img :src="item.src" alt="" />
      <p>{{ item.text }}</p>
    </swiper-slide>
    <template #container-end><span>Container end</span></template>
  </swiper>
</template>

<script setup lang="ts">
  import { Swiper, SwiperSlide } from 'swiper/vue';
  import { Autoplay, Pagination } from 'swiper/modules';
  import 'swiper/css';
  import 'swiper/css/pagination';
  import loginSlideImg01 from '@/assets/img/math/login_slide_img01.svg';
  import loginSlideImg02 from '@/assets/img/math/login_slide_img02.svg';
  import loginSlideImg03 from '@/assets/img/math/login_slide_img03.svg';
  import loginSlideImg04 from '@/assets/img/math/login_slide_img04.svg';
  import loginSlideImg05 from '@/assets/img/math/login_slide_img05.svg';

  /** 자동 재생 시간 */
  const autoPlayDelay = 2500;

  const loginSlideItems = [
    {
      src: loginSlideImg01,
      text: '지문을 끊어 읽으며 핵심 개념을 정리해보아요',
    },
    {
      src: loginSlideImg02,
      text: '다양한 유형의 문제를 풀며 학습한 개념을 문제에 적용해봐요',
    },
    {
      src: loginSlideImg03,
      text: '긴 문장으로 된 문제를 끊어 읽고 해결하는 법을 익혀요',
    },
    {
      src: loginSlideImg04,
      text: '학습을 진행하며 행성을 꾸며봐요',
    },
    {
      src: loginSlideImg05,
      text: '학습 기록과 보고서로 나의 학습 정보를 확인해요',
    },
  ];
</script>
