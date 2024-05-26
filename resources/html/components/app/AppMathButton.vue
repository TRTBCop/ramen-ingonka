<template>
  <div class="answers" :class="toast ? 'toasts' : ''">
    <button class="btn--correct"><i class="answer__number"></i><span>정ss답</span></button>
    <button class="btn--wrong" disabled><i class="answer__number"></i><span>오답</span></button>
    <!--      <button class=""><span>다중선택</span></button>-->
    <button class="btn--check" disabled><i class="answer__number"></i><span>다중선택</span></button>
    <button><i class="answer__number" disabled></i><span>작아지므로작아지므로작아지므로</span></button>
    <!--    <button><span>＋</span></button>-->
    <!--    <button><span>÷</span></button>-->
    <!--    <button><span>×</span></button>-->
    <!--    <button><span>－</span></button>-->
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
