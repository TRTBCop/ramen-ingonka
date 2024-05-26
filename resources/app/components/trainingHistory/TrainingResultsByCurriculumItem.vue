<template>
  <div class="box__history">
    <div class="box__history__head">
      <div class="box__history__title">
        <em
          ><span>{{ getCurriclum(stageResults).ancestors[1].name }}</span
          >{{ getCurriclum(stageResults).ancestors[2].name }}</em
        >
        <strong>{{ getCurriclum(stageResults).name }}</strong>
      </div>
    </div>
    <div class="box__history__body">
      <div v-for="(stageResult, round) in stageResults" :key="round" class="history__round">
        <span>ROUND {{ round + 1 }}</span>
        <div class="history__round__btns">
          <template v-for="stage in 3" :key="stage">
            <button v-if="isNil(stageResult[stage]) && getIsRestudy(round)" class="invisible"> </button>
            <button
              v-else
              @click="onClickStageButton(stage as TrainingStage, getCurriclum(stageResults), stageResult[stage])"
              >{{ getStageName(stage as TrainingStage) }}
              <strong v-if="isNil(stageResult[stage])">학습하기</strong>
              <strong v-else-if="isNil(stageResult[stage].completed_at)">학습중</strong>
              <template v-else>
                <span class="stars"
                  ><i :class="`star star--sm star--${getRankByScore(stageResult[stage].score)}`"></i
                  >{{ stageResult[stage].score }}</span
                >
                <i class="fa-regular fa-magnifying-glass"></i>
              </template>
            </button>
          </template>
          <div class="history__round__score" :class="getRoundItemClass(stageResult)">{{
            getStageScore(stageResult)
          }}</div>
        </div>
      </div>
    </div>

    <TrainingResultLayer
      v-if="isShowTrainingResultLayer"
      v-model:is-show="isShowTrainingResultLayer"
      :training-result="trainingResultLayerValue"
    />
    <TrainingStartConfirmModal
      v-model:is-show="isShowTrainingStartConfirmModal"
      :curriculum="trainingStartConfirmModalCurriclum"
      :training-result="trainingStartConfirmModalTrainingResult"
      :stage="trainingStartConfirmModalStage"
    />
  </div>
</template>

<script setup lang="ts">
  import { ref, PropType } from 'vue';
  import { TrainingStage } from '@/app/api/model/training';
  import { getRankByScore, getStageName, getStageScore } from '@/app/core/helpers/trainingHelper';
  import { StageResult } from '@/app/types/pageData';
  import { isNil } from 'lodash';
  import { TrainingResult } from '@/app/api/model/trainingResult';
  import TrainingStartConfirmModal from '@/app/components/modals/TrainingStartConfirmModal.vue';
  import { Curriculum } from '@/app/api/model/curriculum';
  import TrainingResultLayer from '@/app/components/layers/TrainingResultLayer.vue';

  defineProps({
    stageResults: {
      type: Object as PropType<StageResult[]>,
      default: null,
    },
  });

  const isShowTrainingResultLayer = ref(false);
  const trainingResultLayerValue = ref<TrainingResult>();

  const isShowTrainingStartConfirmModal = ref(false);
  const trainingStartConfirmModalTrainingResult = ref<TrainingResult | null | undefined>(null);
  const trainingStartConfirmModalCurriclum = ref<Curriculum | null | undefined>(null);
  const trainingStartConfirmModalStage = ref<TrainingStage>(1);

  /**
   * 학습 결과 데이터의 round 필드를 받아 해당 학습 결과가 복습인지 아닌지 여부 반환하는 메서드
   */
  function getIsRestudy(round: number) {
    return round !== 0;
  }

  /**
   * 소단원 curriculum 정보를 stageResult의 첫번째 학습 기록의 데이터를 참고해 반환하는 메서드
   */
  function getCurriclum(value: StageResult[]) {
    const firstKey = Object.keys(value[0])[0];
    const firstValue = value[0][firstKey];

    return firstValue.curriculum;
  }

  function onClickStageButton(stage: TrainingStage, curriculum?: Curriculum, trainingResult?: TrainingResult) {
    if (isNil(trainingResult) || isNil(trainingResult.completed_at)) {
      // 복습 하거나 학습 하기 모달 띄우기
      showTrainingStartConfirmModal(stage, curriculum, trainingResult);
    } else {
      // 학습 결과 레이어 띄우기
      showTrainingResultLayer(trainingResult);
    }
  }

  function showTrainingStartConfirmModal(
    stage: TrainingStage,
    curriculum?: Curriculum,
    trainingResult?: TrainingResult,
  ) {
    isShowTrainingStartConfirmModal.value = true;
    trainingStartConfirmModalTrainingResult.value = trainingResult;
    trainingStartConfirmModalCurriclum.value = curriculum;
    trainingStartConfirmModalStage.value = stage;
  }

  function getRoundItemClass(stageResult: StageResult) {
    const result = getStageScore(stageResult);
    if (isNil(result)) {
      return 'disabled';
    } else if (result > 95) {
      return 'high';
    } else if (result > 70) {
      return '';
    } else {
      return 'low';
    }
  }

  function showTrainingResultLayer(trainingResult: TrainingResult) {
    isShowTrainingResultLayer.value = true;
    trainingResultLayerValue.value = trainingResult;
  }
</script>
