<template>
  <AppLayout :theme-bottom="0" :theme-show="2">
    <div class="container">
      <div class="result__intro">
        <div class="result__title">
          <h3>개념 훈련을 완료했어요!</h3>
          <p>십의 자리에서 올림이 있는 (몇십몇)x(몇), 일의 자리에서 올림이 있는 (몇십몇)x(몇)의 서술형 훈련</p>
          <i class="star star--c star--lg"></i>
          <button class="btn--brand"
            ><font-awesome-icon icon="fa-regular fa-arrow-right"></font-awesome-icon>자세히 보기</button
          >
        </div>
        <div class="result__character">
          <i class="character__face"></i>
          <i class="character__hand"></i>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
  import AppLayout from '@/html/layouts/AppLayout.vue';
  import AppBlanks from '@/html/components/app/AppBlanks.vue';
</script>
