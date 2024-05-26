<template>
  <header class="navbar">
    <div class="col">
      <a class="logo" @click="showStopConfirmModal"><img src="@/assets/img/math/ico_logo.svg" /></a>
      <ul class="breadcrumb">
        <li v-if="curriculum.ancestors"
          ><strong>{{ curriculum.ancestors[1].name }}</strong></li
        >
        <li
          ><span>{{ curriculum.name }}</span>
          <font-awesome-icon class="ico_chevron" icon="fa-regular fa-chevron-right" />
        </li>
        <li
          ><span>{{ stageName }} 훈련</span>
          <font-awesome-icon v-if="stepName" class="ico_chevron" icon="fa-regular fa-chevron-right" />
        </li>
        <li>
          <span>{{ stepName }}</span>
        </li>
      </ul>
    </div>

    <div class="col">
      <template v-if="isShowTimer">
        <div class="timer">
          <span class="badge--lightgray"><i class="fa-regular fa-bell"></i>60분 안에 문제를 모두 풀어보세요!</span>
        </div>
        <div class="timer">
          <span
            class="time"
            :class="{
              timeover: isTimeOver,
              'time-pause': curriculumStore.getIsStopTimer,
            }"
            >{{ secondsToMinutesSeconds(currentTime) }}</span
          >
        </div>
      </template>

      <div class="btns">
        <button title="오류신고" @click="errorReport">
          <font-awesome-icon icon="fa-regular fa-light-emergency-on" />
        </button>
        <button title="나가기" @click="showStopConfirmModal">
          <font-awesome-icon icon="fa-regular fa-right-from-bracket" />
        </button>
      </div>
    </div>
  </header>

  <ConfirmModal v-model:modal-state="errorModalState" />
</template>

<script setup lang="ts">
  import { ref, computed } from 'vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { TrainingPageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import ConfirmModal from '@/app/components/modals/ConfirmModal.vue';
  import { updateTrainingResultTimer } from '@/app/api/trainingResult';
  import { isNil } from 'lodash';
  import { secondsToMinutesSeconds } from '@/app/core/helpers/formattingHelper';
  import { ModalState } from '@/app/types/modals';
  import { useI18n } from 'vue-i18n';
  import Gleap from 'gleap';
  import { getUserData } from '@/app/core/helpers/userHelper';
  import { goTrainingMainPage } from '@/app/core/helpers/routerHelper';
  import { getStageName, getStepName } from '@/app/core/helpers/trainingHelper';

  const { t } = useI18n();

  const pageData = usePage<TrainingPageProps>();
  const userData = computed(() => getUserData());

  const curriculum = computed(() => pageData.props.training.curriculum);
  const training = computed(() => pageData.props.training);

  const curriculumStore = useCurriculumStoreWithOut();

  const isShowTimer = computed(() => {
    if (pageData.props.isPreview) {
      return false;
    }

    if (pageData.props.stage === 1) {
      return false;
    }

    return true;
  });

  const currentTime = computed(() => curriculumStore.getTimer);

  const isTimeOver = computed(() => currentTime.value > 3600);

  const errorModalState = ref<ModalState>({ show: false, message: '', confirmEvent: null });

  function showStopConfirmModal() {
    errorModalState.value.show = true;
    errorModalState.value.message = t('confirm.training.stop');
    errorModalState.value.confirmEvent = saveTimeAndTrainingMainPage;
  }

  const stageName = computed(() => getStageName(training.value.stage));

  const stepName = computed(() => getStepName(training.value.stage, pageData.props.step));

  /** 시간 저장 후 메인 페이지로 */
  async function saveTimeAndTrainingMainPage() {
    try {
      const trainingResult = pageData.props.training_result;
      if (!isNil(trainingResult)) {
        await updateTrainingResultTimer(trainingResult.id, curriculumStore.getTimer);
      }

      goTrainingMainPage(pageData.props.training);
    } catch (err) {
      console.log(err);
    }
  }

  /**
   * gleap 유저 오류신고
   */
  const errorReport = () => {
    if (!Gleap.isOpened()) {
      Gleap.open();
    } else {
      Gleap.close();
    }
  };

  //gleap 프론트 피드백 설정
  Gleap.identify(String(userData.value.id), {
    name: userData.value.name,
    email: userData.value.email,
  });
</script>
