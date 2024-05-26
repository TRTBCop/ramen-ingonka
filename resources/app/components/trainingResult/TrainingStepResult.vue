<template>
  <div class="frame__head title_area">
    <h3 class="title">
      <font-awesome-icon class="is--warning" icon="fa-regular fa-sparkles" />
      <strong>{{ stepName }} 완료!</strong>
    </h3>
  </div>
  <div class="frame__body">
    <div class="result__summary">
      <h2>{{ training.curriculum?.name }}</h2>
      <div :class="`result__progress result__progress--${getRankByScore(stepResult.correct_percent)}`">
        <i :class="`rank rank--${getRankByScore(stepResult.correct_percent)} rank--lg`"></i>
        <strong>{{ stepResult.correct_percent }}%</strong>
        <progress max="100" :value="stepResult.correct_percent"></progress>
      </div>
    </div>

    <template v-if="stepResult.key === 'texts'">
      <div class="result__box result__box--white">
        <div class="row">
          <h3>개념 요약</h3>
        </div>
        <ul class="lst__result">
          <li>
            <span>정답수</span>
            <p>{{ typeResults.summarizations.correct_answers }}</p>
          </li>
          <li>
            <span>문제수</span>
            <p>{{ typeResults.summarizations.total_answers }}</p>
          </li>
          <li class="lst__result__percent">
            <span>정답률</span>
            <p>{{ typeResults.summarizations.correct_percent }}%</p>
          </li>
        </ul>
      </div>

      <div class="result__box result__box--white">
        <h3>개념 다지기</h3>
        <ul class="lst__result">
          <li>
            <span>정답수</span>
            <p>{{ typeResults.reinforcements.correct_answers }}</p>
          </li>
          <li>
            <span>문제수</span>
            <p>{{ typeResults.reinforcements.total_answers }}</p>
          </li>
          <li class="lst__result__percent">
            <span>정답률</span>
            <p>{{ typeResults.reinforcements.correct_percent }}%</p>
          </li>
        </ul>
      </div>

      <div class="result__box result__box--gray">
        <h3>전체</h3>
        <ul class="lst__result">
          <li>
            <span>정답수</span>
            <p>{{ stepResult.correct_answers }}</p>
          </li>
          <li>
            <span>문제수</span>
            <p>{{ stepResult.total_answers }}</p>
          </li>
          <li class="lst__result__percent">
            <span>정답률</span>
            <p>{{ stepResult.correct_percent }}%</p>
          </li>
        </ul>
      </div>
    </template>
    <template v-else-if="training.stage === TrainingStage.STAGE_3">
      <div v-for="(question, i) in stepResult.questions" :key="i" class="result__box result__box--white">
        <div class="row">
          <i :class="`ico__${getQuestionStateClass(question.correct_percent)}`"></i>
          <h3>{{ i + 1 }}번</h3>
        </div>
        <ul class="lst__result">
          <li>
            <span>풀이답안 정답수</span>
            <p>{{ question.correct_answers }}</p>
          </li>
          <li>
            <span>풀이답안 문제수</span>
            <p>{{ question.total_answers }}</p>
          </li>
          <li class="lst__result__percent">
            <span>정답률</span>
            <p>{{ question.correct_percent }}%</p>
          </li>
        </ul>
      </div>
      <div class="result__box result__box--gray">
        <div class="row">
          <h3>전체</h3>
          <button
            v-if="stepResult.correct_percent !== 100"
            class="btn--gray"
            @click="goIncorrectExplanationPage(training, trainingResult, stepResult)"
            >해설보기</button
          >
        </div>
        <ul class="lst__result">
          <li>
            <span>정답수</span>
            <p>{{ stepResult.correct_answers }}</p>
          </li>
          <li>
            <span>문제수</span>
            <p>{{ stepResult.total_answers }}</p>
          </li>
          <li class="lst__result__percent">
            <span>정답률</span>
            <p>{{ stepResult.correct_percent }}%</p>
          </li>
        </ul>
      </div>
    </template>
    <template v-else>
      <div class="result__box result__box--white">
        <div class="row">
          <h3>{{ stepName }}</h3>
          <button
            v-if="stepResult.correct_percent !== 100 && training.stage !== 1"
            class="btn--gray"
            @click="goIncorrectExplanationPage(training, trainingResult, stepResult)"
            >해설보기</button
          >
        </div>

        <ul class="lst__result">
          <li>
            <span>정답수</span>
            <p>{{ stepResult.correct_answers }}</p>
          </li>
          <li>
            <span>문제수</span>
            <p>{{ stepResult.total_answers }}</p>
          </li>
          <li class="lst__result__percent">
            <span>정답률</span>
            <p>{{ stepResult.correct_percent }}%</p>
          </li>
        </ul>
        <ol class="result__score">
          <li
            v-for="(question, i) in stepResult.questions"
            :key="i"
            :class="getQuestionStateClass(question.correct_percent)"
            ><i></i
          ></li>
        </ol>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
  import { computed, PropType } from 'vue';
  import { getQuestionStateClass } from '@/app/pages/trainings/data';
  import { goIncorrectExplanationPage } from '@/app/core/helpers/routerHelper';
  import { getStepName, getRankByScore } from '@/app/core/helpers/trainingHelper';
  import { StepResult } from '@/app/api/model/stepResult';
  import { Training, TrainingStage } from '@/app/api/model/training';
  import { BaseResult } from '@/app/api/model/baseModel';
  import { TrainingResult } from '@/app/api/model/trainingResult';

  const props = defineProps({
    training: {
      type: Object as PropType<Training>,
      default: null,
    },
    trainingResult: {
      type: Object as PropType<TrainingResult>,
      default: null,
    },
    stepResult: {
      type: Object as PropType<StepResult>,
      default: null,
    },
  });

  const stepName = computed(() => getStepName(props.training.stage, props.stepResult.key));

  interface TypeResult {
    summarizations: BaseResult;
    reinforcements: BaseResult;
  }

  /** 개념 학습 결과 데이터 */
  const typeResults = computed<TypeResult>(() => {
    const types = ['summarizations', 'reinforcements'];

    const result: TypeResult = {
      summarizations: {
        total_answers: 0,
        correct_answers: 0,
        correct_percent: 0,
      },
      reinforcements: {
        total_answers: 0,
        correct_answers: 0,
        correct_percent: 0,
      },
    };

    types.forEach((type) => {
      const totalAnswers = props.stepResult.training_concept_texts.reduce(
        (prev, trainingConceptTextResult) => trainingConceptTextResult[type].total_answers + prev,
        0,
      );

      const correctAnswers = props.stepResult.training_concept_texts.reduce(
        (prev, trainingConceptTextResult) => trainingConceptTextResult[type].correct_answers + prev,
        0,
      );

      const correctPercent = Math.round((correctAnswers / totalAnswers) * 100);
      result[type].total_answers = totalAnswers;
      result[type].correct_answers = correctAnswers;
      result[type].correct_percent = correctPercent;
    });

    return result;
  });
</script>
