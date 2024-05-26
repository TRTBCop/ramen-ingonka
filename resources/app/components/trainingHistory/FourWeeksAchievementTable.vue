<template>
  <table class="tbl__history">
    <colgroup>
      <col />
    </colgroup>
    <thead>
      <tr>
        <th>구분</th>
        <th>소단원수</th>
        <th>훈련수</th>
        <th>훈련시간(분)</th>
        <th>총 정답수/문제수(정답률)</th>
        <th>성취도</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(item, i) in achievementOver4Weeks" :key="i">
        <td>{{ i === 0 ? '이번주' : `${i}주 전` }}</td>
        <td>{{ getValueOrDash(item.curriculum_count) }}</td>
        <td>{{ getValueOrDash(item.training_count) }}</td>
        <td>{{ getValueOrDash(item.total_timer_minutes) }}</td>
        <td>{{
          item.total_answers
            ? `${getValueOrDash(item.correct_answers)}/${getValueOrDash(item.total_answers)} (${getValueOrDash(
                item.correct_percent,
              )}%)`
            : '-'
        }}</td>
        <td>{{ getValueOrDash(item.score) }}</td>
      </tr>
    </tbody>
  </table>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';
  import { WeeklyAchievement } from '@/app/types/pageData';
  import { getValueOrDash } from '@/app/core/helpers/formattingHelper';

  defineProps({
    achievementOver4Weeks: {
      type: Array as PropType<WeeklyAchievement[]>,
      default: null,
    },
  });
</script>
