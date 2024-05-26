<template>
  <h2>{{ test.title }} <br />수리문해력 종합진단평가</h2>
  <div class="frame__box">
    <ul class="pretest__info">
      <li>진단평가의 제한 시간은 40분입니다.</li>
      <li>한번 완료한 진단평가는 다시 볼 수 없습니다.</li>
      <li>진단평가는 시작 후 중단할 수 없으며 진행 중 강제 로그아웃 시 재시작 할 수 없습니다.</li>
    </ul>
  </div>
  <p
    >준비가 되었으면 아래 버튼을 눌러 <br />
    진단평가를 시작하세요.</p
  >
  <button class="btn--gray" @click="goTestPage(props.test.id)">시작하기</button>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';
  import { Test } from '@/app/api/model/tests';
  import { goTestPage } from '@/app/core/helpers/routerHelper';

  const props = defineProps({
    test: {
      type: Object as PropType<Test>,
      default: null,
    },
  });
</script>
