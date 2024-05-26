<template>
  <div class="grid">
    <div class="frame__full">
      <img src="@/assets/img/math/alien_layer_reading.svg" alt="" />
      <h2 class="is--size1">틀린 문제가 있어요.</h2>
      <p class="">틀린 문제 개념 확인에 도움이 되도록 관련된 이전 학습 확인 문제를 추가로 풀어보세요!</p>
      <button class="btn--gray" @click="startExtendQuestion">시작하기</button>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { useTestStoreWithOut } from '@/app/stores/modules/tests';

  const testStore = useTestStoreWithOut();

  function startExtendQuestion() {
    testStore.setIsStartExtend(true);
  }
</script>
