<template>
  <ul class="history__week">
    <li>
      <span>소단원 수</span>
      <strong>{{ getValueOrDash(weeklyAchievement.curriculum_count) }}</strong>
    </li>
    <li>
      <span>훈련수</span>
      <strong>{{ getValueOrDash(weeklyAchievement.training_count) }}</strong>
    </li>
    <li>
      <span>훈련시간(분)</span>
      <strong>{{ getValueOrDash(weeklyAchievement.total_timer_minutes) }}</strong>
    </li>
    <li>
      <span>총 정답수 / 문제수</span>
      <strong
        >{{ getValueOrDash(weeklyAchievement.correct_answers) }}/{{
          getValueOrDash(weeklyAchievement.total_answers)
        }}</strong
      >
    </li>
    <li>
      <span>정답률</span>
      <strong>{{ getValueOrDash(weeklyAchievement.correct_percent) }}%</strong>
    </li>
    <li>
      <span>성취도</span>
      <strong>{{ getValueOrDash(weeklyAchievement.score) }}</strong>
    </li>
  </ul>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';
  import { WeeklyAchievement } from '@/app/types/pageData';
  import { getValueOrDash } from '@/app/core/helpers/formattingHelper';

  defineProps({
    weeklyAchievement: {
      type: Object as PropType<WeeklyAchievement>,
      default: null,
    },
  });
</script>
