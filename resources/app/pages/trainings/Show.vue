<template>
  <AppHeader />
  <div
    class="main main_training"
    :class="`theme--${getUserData().grade}-${getUserData().term} ${getThemeTypeClass(training.stage)}`"
  >
    <div class="grid">
      <div class="frame col">
        <div class="training__head">
          <div class="training__subject">
            <button class="btn__layer" @click="showLayer"><i class="fa-regular fa-indent"></i></button>
            <div class="training__unit">
              <h3 v-if="pageData.props.curriculum.ancestors">{{ pageData.props.curriculum.ancestors[2].name }}</h3>
              <span class="">{{ pageData.props.curriculum.name }}</span>
            </div>
          </div>

          <a class="btn__training__info">
            <span
              >{{ getStageName(training.stage) }} 훈련
              <font-awesome-icon icon="fa-regular fa-circle-info" @click="showHelpLayer"
            /></span>
            <ol class="training__progress">
              <li
                v-for="otherTraining in pageData.props.curriculum.trainings"
                :key="otherTraining.id"
                :class="{
                  current: otherTraining.stage === training.stage,
                  done: getStageCompleted(otherTraining.stage),
                }"
                @click="goTrainingMainPage(otherTraining)"
              ></li>
            </ol>
          </a>

          <!-- 도움말 레이어 -->
          <div v-show="isShowHelpLayer" class="modal__training__info">
            <div class="btns btns--right">
              <button @click="closeHelpLayer"><i class="fa-regular fa-close"></i></button>
            </div>
            <p>
              {{ getStageIntroComment(pageData.props.training.stage) }}
            </p>
          </div>
        </div>

        <div class="training__body">
          <ul class="training__round">
            <li v-for="trainingResult in pageData.props.training.results" :key="trainingResult.id">
              <span>
                ROUND {{ trainingResult.round + 1 }} <br />
                <div v-if="pageData.props.stage !== 1" class="timer">
                  <span
                    class="time time-pause"
                    :class="{
                      timeover: trainingResult.timer > 3600,
                    }"
                    >{{ secondsToMinutesSeconds(trainingResult.timer) }}</span
                  >
                </div></span
              >
              <div class="training__round__btns">
                <div v-for="step in trainingResult.steps" :key="step.id">
                  <small>{{ getStepName(training.stage, step.key) }}</small>

                  <button v-if="step.completed_at"
                    ><i :class="`rank rank--${getRankByScore(step.correct_percent)} rank--sm`"></i
                  ></button>
                  <button v-else class="btn__done" @click="showTrainingConfirmModal(step.key)">
                    {{ step.total_answers > 0 ? '학습중' : '학습하기' }}
                  </button>
                </div>
              </div>
              <i
                class="training_star"
                :class="trainingResult.completed_at ? `training_star--${getRankByScore(trainingResult.score)}` : ''"
              ></i>
            </li>
          </ul>
        </div>
      </div>
      <div class="frame col col--sm">
        <div class="training__object">
          <a href="javascript:;" @click="goMainPage">
            <i class="alien"></i>
            <span>
              <font-awesome-icon icon="fa-regular fa-planet-ringed"></font-awesome-icon>
              행성 메인으로
            </span>
          </a>
        </div>
      </div>
    </div>

    <div class="frame__bottom">
      <button
        class="btn--gray"
        :class="{
          invisible: pageData.props.stage == 1,
        }"
        @click="goPrevTraining"
      >
        <font-awesome-icon icon="fa-regular fa-arrow-left" />이전 훈련
      </button>
      <button
        class="btn--gray"
        :class="{
          invisible: pageData.props.stage == 3,
        }"
        @click="goNextTraining"
      >
        <font-awesome-icon icon="fa-regular fa-arrow-right" />다음 훈련
      </button>
    </div>
  </div>

  <CurriculumLayer
    v-if="pageData.props.curriculum.ancestors"
    v-model:is-show="isShowLayer"
    :curriculums="pageData.props.curriculum.ancestors[1].children"
  />

  <ConfirmModal v-model:modal-state="modalState" />
</template>
<script setup lang="ts">
  import { ref, computed } from 'vue';
  import { Training, TrainingStage } from '@/app/api/model/training';
  import AppHeader from '@/app/components/headers/AppHeader.vue';
  import { PageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import { getStageIntroComment } from './data';
  import { Curriculum } from '@/app/api/model/curriculum';
  import CurriculumLayer from '@/app/components/layers/CurriculumLayer.vue';
  import { secondsToMinutesSeconds } from '@/app/core/helpers/formattingHelper';
  import { getUserData } from '@/app/core/helpers/userHelper';
  import { ModalState } from '@/app/types/modals';
  import ConfirmModal from '@/app/components/modals/ConfirmModal.vue';
  import { useI18n } from 'vue-i18n';
  import { goMainPage, goTrainingByStep, goTrainingMainPage } from '@/app/core/helpers/routerHelper';
  import { isNil } from 'lodash';
  import { getRankByScore, getStageName, getThemeTypeClass, getStepName } from '@/app/core/helpers/trainingHelper';

  const { t } = useI18n();

  interface Page extends PageProps {
    curriculum: Curriculum;
    training: Training;
  }

  const pageData = usePage<Page>();

  const training = computed(() => pageData.props.training);

  const modalState = ref<ModalState>({
    show: false,
    message: '',
    confirmEvent: null,
  });

  function showTrainingConfirmModal(step: string | number) {
    modalState.value.show = true;
    modalState.value.message = t('confirm.training.start');
    modalState.value.confirmEvent = () => {
      goTrainingByStep(training.value, step);
    };
  }

  const isShowLayer = ref(false);

  function showLayer() {
    isShowLayer.value = true;
  }

  const isShowHelpLayer = ref(true);

  function showHelpLayer() {
    isShowHelpLayer.value = true;
  }

  function closeHelpLayer() {
    isShowHelpLayer.value = false;
  }

  /** 해당 훈련의 최초 학습을 완료했는지 여부를 반환 하는 메서드 */
  function getStageCompleted(stage: TrainingStage) {
    return (
      pageData.props.training.results.findIndex(
        (trainingResult) =>
          trainingResult.training?.stage == stage && trainingResult.completed_at && trainingResult.round === 0,
      ) !== -1
    );
  }

  function goNextTraining() {
    if (pageData.props.stage === 3) return;

    const foundTraining = pageData.props.curriculum.trainings.find(
      (training) => training.stage === pageData.props.training.stage + 1,
    );
    if (isNil(foundTraining)) return;

    goTrainingMainPage(foundTraining);
  }

  function goPrevTraining() {
    if (pageData.props.stage === 1) return;

    const foundTraining = pageData.props.curriculum.trainings.find(
      (training) => training.stage === pageData.props.training.stage - 1,
    );
    if (isNil(foundTraining)) return;

    goTrainingMainPage(foundTraining);
  }
</script>
