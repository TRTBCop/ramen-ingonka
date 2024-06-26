<template>
  <!-- 커리큘럼 레이어 -->
  <div v-if="isShow" class="layer ly__help">
    <div class="inner">
      <div class="dots">
        <i
          v-for="(_, i) in items"
          :key="i"
          :class="{
            current: currentItemIndex === i,
          }"
        ></i>
      </div>
      <div class="lst__help">
        <div
          v-for="(item, i) in items"
          :key="i"
          class="help__slide"
          :class="{
            current: currentItemIndex === i,
          }"
        >
          <img :src="item.src" alt="도움말 이미지" />
          <h3>{{ item.title }}</h3>
          <p class="txt--center" v-html="item.content"></p>
        </div>

        <button class="btn__prev" @click="prevItem"><font-awesome-icon icon="fa-regular fa-chevron-left" /></button>
        <button class="btn__next" @click="nextItem"><font-awesome-icon icon="fa-regular fa-chevron-right" /></button>
      </div>
      <button class="btn--gray mt-1" @click="closeModal">그만 볼래요</button>
      <small class="mt-1"
        >도움말을 다시 보고 싶으면 <font-awesome-icon icon="fa-regular fa-circle-question"></font-awesome-icon>를
        누르세요</small
      >
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, PropType } from 'vue';
  import helpImg1 from '@/assets/img/math/help_img1.svg';
  import helpImg2 from '@/assets/img/math/help_img2.svg';
  import helpImg3 from '@/assets/img/math/help_img3.svg';
  import helpImg4 from '@/assets/img/math/help_img4.svg';
  import helpImg5 from '@/assets/img/math/help_img5.svg';

  defineProps({
    isShow: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  const emits = defineEmits(['update:isShow']);

  const currentItemIndex = ref(0);

  const items: {
    src: string;
    title: string;
    content: string;
  }[] = [
    {
      src: helpImg1,
      title: '기본 모드',
      content: `기본 모드는 처음 시작할 때 내가 설정한 학기의 첫 학습부터 순차적으로 진행하는 모드에요. <br />
            학기 행성의 대표 외계인이 해야 할 학습을 알려주기 때문에 외계인을 따라 차근차근 학습을 진행해 보세요.
            <br />
            (마이페이지에서 학기 변경 가능)`,
    },
    {
      src: helpImg2,
      title: '자유 모드',
      content: `자유 모드는 기본 모드에서 학습 중인 학기뿐 아니라 다른 모든 학기를 자유롭게 둘러볼 수 있는 모드에요.<br />
            학기와 계통을 기준으로 전체 학습을 한눈에 볼 수 있어서 원하는 학습이 있으면 직접 이동해서 학습을 진행해
            보세요.`,
    },
    {
      src: helpImg3,
      title: '자유 모드',
      content: `하나의 소단원은 개념 훈련, 유형 훈련, 서술형 훈련으로 이루어져 있어요. <br />
            각 훈련은 2~4단계로 구성되어 있으며, 모든 단계를 완료해야 해당 훈련이 완료돼요. <br />
            훈련은 총 3 번 중복 학습이 가능하며, 학습 횟수는 ROUND로 표시해요. <br />
            <span>* ROUND 1 : 최초 학습, ROUND 2 : 첫 번째 복습, ROUND 3 : 두 번째 복습</span>`,
    },
    {
      src: helpImg4,
      title: '점수',
      content: `훈련 점수는 1등급부터 4등급으로 분류되며, 등급에 따라 별점으로 표시돼요. <br />
            <span>* 1등급(무지개별) : 95~100점, 2등급(금별) : 85~94점, 3등급(은별) : 65~84점, 4등급(동별) : 0~64점</span
            ><br />
            훈련의 각 단계 점수도 4등급으로 분류되며, 등급에 따라 알파벳으로 표시돼요.<br />
            <span>* 1등급(S) : 95~100점, 2등급(A) : 85~94점, 3등급(B) : 65~84점, 4등급(C) : 0~64점</span>`,
    },
    {
      src: helpImg5,
      title: '행성 꾸미기',
      content: `리딩수학의 모든 학기는 각각 다른 행성으로 이루어져 있으며 모든 행성은 직접 꾸밀 수 있어요. <br />
            각 학기의 대단원을 완료할 때마다 행성을 꾸밀 수 있는 아이템을 획득할 수 있으니,<br />
            학습을 완료해서 모든 행성을 가득 꾸며 보세요.`,
    },
  ];

  function prevItem() {
    if (currentItemIndex.value <= 0) return;

    currentItemIndex.value--;
  }

  function nextItem() {
    if (items.length - 1 <= currentItemIndex.value) return;
    currentItemIndex.value++;
  }

  function closeModal() {
    emits('update:isShow', false);
  }
</script>
