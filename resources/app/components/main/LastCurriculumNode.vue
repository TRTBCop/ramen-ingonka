<template>
  <li
    :class="{
      done: getIsCompletedCurriculum(curriculum),
      current: pageData.props.curriculum?.id === curriculum.id,
    }"
  >
    <div class="row" @click="toggleShowSub">
      <span>{{ curriculum.name }}</span>
      <div class="stars">
        <i
          v-for="training in curriculum.trainings"
          :key="training.id"
          class="star star--sm"
          :class="getStarClass(training)"
        ></i>
      </div>
    </div>
    <ul
      class="curriculum__sub__detail"
      :class="{
        show: isShowSub,
      }"
    >
      <li v-for="training in curriculum.trainings" :key="training.id" @click="goTrainingMainPage(training)">
        <i class="star star--md" :class="getStarClass(training)"></i>
        <strong>{{ getStageName(training.stage) }} 훈련</strong>
        <span>ROUND{{ training.results.length }}</span>
      </li>
    </ul>
  </li>
</template>

<script setup lang="ts">
  import { ref, PropType } from 'vue';
  import { Curriculum } from '@/app/api/model/curriculum';
  import { usePage } from '@inertiajs/vue3';
  import { PageProps } from '@/app/types/pageData';
  import { TrainingResult } from '@/app/api/model/trainingResult';
  import { getRankByScore, getStageName } from '@/app/core/helpers/trainingHelper';
  import { isNil, last } from 'lodash';
  import { Training } from '@/app/api/model/training';
  import { goTrainingMainPage } from '@/app/core/helpers/routerHelper';

  defineProps({
    curriculum: {
      type: Object as PropType<Curriculum>,
      default: null,
    },
  });

  interface Page extends PageProps {
    training_results: TrainingResult[];
    curriculum?: Curriculum;
  }

  const pageData = usePage<Page>();

  const isShowSub = ref(false);

  function toggleShowSub() {
    isShowSub.value = !isShowSub.value;
  }

  function getIsCompletedCurriculum(curriculum: Curriculum) {
    return curriculum.trainings.reduce((prev, current) => current.results.length > 0 && prev, true);
  }

  /** 훈련별 마지막 학습의 점수에 해당 하는 별 클래스명을 반환하는 메서드 */
  function getStarClass(training: Training) {
    const lastTrainingResult = last(training.results);
    if (isNil(lastTrainingResult)) return '';

    const scoreClass = `star--${getRankByScore(lastTrainingResult.score)}`;

    const reviewClass = `review--${training.results.length}`;

    return `${scoreClass} ${reviewClass}`;
  }
</script>
