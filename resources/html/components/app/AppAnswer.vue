<template>
  <div class="answers--bubble">
    <div class="answers">
      <button>
        <i class="answer__number"></i>
        <span>기본</span>
      </button>
      <button class="answer__correct">
        <i class="answer__number"></i>
        <span>안녕하세요 저는 정답입니다</span>
      </button>
      <button class="answer__wrong">
        <i class="answer__number"></i>
        <span>오답</span>
      </button>
      <button class="answer__check">
        <i class="answer__number"></i>
        <span>다중선택: 여러개를 선택해야하는 경우</span>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';

  const props = defineProps({
    toast: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });
</script>
