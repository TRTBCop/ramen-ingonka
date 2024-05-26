<template>
  <header class="navbar">
    <div class="container">
      <div class="col">
        <a href="/" class="logo" title="logo"> <i class=""></i> 리딩수학</a>
        <ol class="breadcrumb">
          <li><small class="badge--lightgray">중등 3-1</small></li>
          <li> <strong>진단 평가</strong></li>
        </ol>
      </div>

      <template v-if="isShowOption">
        <div class="counter">
          <strong>1</strong>/
          <span>18</span>
        </div>

        <!-- [D] 시간지나면 .is--danger-->
        <div class="timer">
          <span class="time timeover"><font-awesome-icon icon="fa-regular fa-timer" />1:06:30</span>/
          <span>40:00</span>
        </div>
      </template>
    </div>
  </header>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';

  const props = defineProps({
    isShowOption: {
      type: Boolean as PropType<boolean>,
      default: true,
    },
  });
</script>
