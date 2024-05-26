<template>
  <swiper
    class="lst__semester"
    :loop="true"
    :slides-per-view="3"
    :centered-slides="true"
    :allow-touch-move="false"
    @swiper="onSwiper"
  >
    <swiper-slide v-for="(item, i) in slideItems" :key="i" v-slot="{ isActive, isPrev, isNext }">
      <a :class="`semester ${isActive ? 'semester--on' : ''}`" @click="onClickItem(item, isActive, isPrev, isNext)">
        <i :class="`semester__thmb ${getThumbThemeClassName(item)}`"></i>
        <span>
          <slot name="item-title" :row="item" />
        </span>
        <slot v-if="isActive" name="item-progress" :row="item" />
      </a>
    </swiper-slide>
  </swiper>

  <div class="slide">
    <button class="prev" @click="swiperRef.slidePrev()"
      ><font-awesome-icon icon="fa-regular fa-chevron-left"></font-awesome-icon
    ></button>
    <button class="next" @click="swiperRef.slideNext()"
      ><font-awesome-icon icon="fa-regular fa-chevron-right"></font-awesome-icon
    ></button>
  </div>
</template>

<script setup lang="ts">
  import { ref, PropType } from 'vue';
  import { Swiper, SwiperSlide } from 'swiper/vue';
  import 'swiper/css';
  import { isNil } from 'lodash';

  defineProps({
    slideItems: {
      type: Array as PropType<any[]>,
      default: null,
    },
  });

  // options based
  const emits = defineEmits<{
    (e: 'onClickActiveItem', item: any): void;
  }>();

  const swiperRef = ref();

  function onSwiper(swiper) {
    swiperRef.value = swiper;
  }

  function onClickItem(item: any, isActive: boolean, isPrev: boolean, isNext: boolean) {
    if (isNext) {
      swiperRef.value.slideNext();
    } else if (isPrev) {
      swiperRef.value.slidePrev();
    } else if (isActive) {
      emits('onClickActiveItem', item);
    }
  }

  /**
   * 행성 클래스명
   */
  function getThumbThemeClassName(item: any) {
    if (!isNil(item?.element)) {
      return `semester--${item.element / 100}`;
    } else {
      return `semester--${item.grade}-${item.term}`;
    }
  }
</script>

<style>
  .swiper-wrapper {
    align-items: center;
  }
</style>
