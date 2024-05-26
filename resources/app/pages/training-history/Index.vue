<template>
  <BoardLayout page-name="학습 기록">
    <div class="history">
      <div class="grid">
        <div class="col--sm">
          <h3 class="pt-0">주간 선택</h3>
          <HistoryCalendar />

          <h3 @click="toggleShowHelpBox"
            >도움말 <font-awesome-icon icon="fa-regular fa-circle-info"></font-awesome-icon
          ></h3>
          <ul v-show="isShowHelpBox" class="box__history history__info">
            <li>
              학습기록은 상단에 표시된 1주일 기간 동안의 모든 학습기록을 실시간으로 확인할 수 있으며, 기본 설정 기간은
              오늘 포함 1주일입니다.
            </li>
            <li> 학습기록 기간의 종료일은 재설정이 가능하며, 재설정한 기간으로 학습기록이 자동 변경됩니다.</li>
            <li>
              소단원은 3가지 훈련(개념/유형/서술형)으로 구성되어 있으며 모든 훈련을 완료해야 해당 소단원 결과를 볼 수
              있습니다.
            </li>
          </ul>
        </div>
        <div class="col">
          <template v-if="!isEmpty(trainingResultsByDate)">
            <h3 class="pt-0">주차 성취도</h3>
            <WeeklyAchievement :weekly-achievement="achievementOver4Weeks[0]" />

            <h3>4주간 성취도</h3>
            <p>
              <WeeklyDataChart
                :score-list="achievementOver4Weeks.map((data) => data.score).reverse()"
                :total-answers-list="achievementOver4Weeks.map((data) => data.total_answers).reverse()"
              />
            </p>
            <FourWeeksAchievementTable :achievement-over4-weeks="achievementOver4Weeks" />

            <h3>학습내용별 학습기록</h3>
            <TrainingResultsByCurriculumItem
              v-for="(data, curriculumId) in trainingResultsByCurriculum"
              :key="curriculumId"
              :stage-results="data"
            />

            <h3>학습일시별 학습기록</h3>
            <TrainingResultsByDateItem
              v-for="(trainingResults, date) in trainingResultsByDate"
              :key="date"
              :training-results="trainingResults"
              :date="date"
            />
          </template>
          <div v-else class="data_area data__none">
            <img src="@/assets/img/math/no_data.svg" alt="" />
            <p>학습기록이 없습니다.</p>
          </div>
        </div>
      </div>
    </div>
  </BoardLayout>
</template>

<script setup lang="ts">
  import { ref, computed } from 'vue';
  import BoardLayout from '@/app/layouts/BoardLayout.vue';
  import { HistoryPageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import WeeklyDataChart from '@/app/components/trainingHistory/WeeklyDataChart.vue';
  import TrainingResultsByCurriculumItem from '@/app/components/trainingHistory/TrainingResultsByCurriculumItem.vue';
  import TrainingResultsByDateItem from '@/app/components/trainingHistory/TrainingResultsByDateItem.vue';
  import WeeklyAchievement from '@/app/components/trainingHistory/WeeklyAchievement.vue';
  import FourWeeksAchievementTable from '@/app/components/trainingHistory/FourWeeksAchievementTable.vue';
  import HistoryCalendar from '@/app/components/trainingHistory/HistoryCalendar.vue';
  import { isEmpty } from 'lodash';

  const pageData = usePage<HistoryPageProps>();

  /** 4주간 학습 기록 */
  const achievementOver4Weeks = computed(() => pageData.props.achievement_over_4weeks);

  /** 학습 내용별 학습 기록 */
  const trainingResultsByCurriculum = computed(() => pageData.props.training_results_by_curriculum);

  /** 학습 일시별 학습 기록 */
  const trainingResultsByDate = computed(() => pageData.props.training_results_by_date);

  const isShowHelpBox = ref(true);

  function toggleShowHelpBox() {
    isShowHelpBox.value = !isShowHelpBox.value;
  }
</script>
