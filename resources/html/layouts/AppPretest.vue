<template>
  <div id="wrap" class="pretest_wrap">
    <AppPretestHeader :is-show-option="pageName === 'default'" />

    <div class="pretest" :class="isShow ? 'bg--white' : ''">
      <div class="container">
        <slot> </slot>
      </div>

      <div v-if="pageName === 'default'" class="learn__bottom">
        <div class="tooltip">
          <span>스페이스 바를 눌러 끊어 읽기를 시작하세요.</span>
          <button class="btn--gray" disabled>확인</button>
          <button class="btn--gray">다음으로</button>
        </div>
      </div>
      <div v-if="pageName === 'default'" class="learn__bottom">
        <div class="tooltip">
          <div class="meta__help">
            <b>정답이 확실해요?</b>
            <p>정확한 평가를 위하여 솔직하게 클릭해 주세요.</p>
          </div>
          <button class="btn--gray btn--lg" disabled>확실해요</button>
          <button class="btn--gray btn--lg">정답일 것 같아요.</button>
          <button class="btn--gray btn--lg">모르겠어요.</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import AppPretestHeader from '@/html/components/app/AppPretestHeader.vue';
  import { PropType } from 'vue';

  defineProps({
    pageName: {
      type: String as PropType<'default' | 'addIntro'>,
      default: 'default',
    },
    isShow: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });
</script>
