<template>
  <AppLayout :is-show-header="!isShowMainIntro">
    <MainIntro v-if="isShowMainIntro" />

    <div v-else :class="`main theme--${userSchoolInfo?.grade}-${userSchoolInfo?.term}`">
      <div class="main__head">
        <MainModeButtons />

        <div class="mode mode__sub">
          <strong>{{ userSchoolInfo?.grade }}학년 {{ userSchoolInfo?.term }}학기 행성</strong>
        </div>
      </div>

      <div class="main__body">
        <div class="main__hills main__objects">
          <div class="main__hills__inner">
            <div class="object--2"><i :style="`--w: ${progressByGradeTermCurriculums[1]}%`"></i></div>
            <div class="object--1"><i :style="`--w: ${progressByGradeTermCurriculums[0]}%`"></i></div>
            <div class="object--3"><i :style="`--w: ${progressByGradeTermCurriculums[2]}%`"></i></div>
          </div>
        </div>
        <div class="main__sky main__objects">
          <div class="main__sky__inner">
            <div class="object--6"><i :style="`--w: ${progressByGradeTermCurriculums[5]}%`"></i></div>
            <div class="object--4"><i :style="`--w: ${progressByGradeTermCurriculums[3]}%`"></i></div>
            <div class="object--5"><i :style="`--w: ${progressByGradeTermCurriculums[4]}%`"></i></div>
          </div>
        </div>

        <a class="object--alien" href="javascript:;" @click="onClickAlien">
          <p class="bubble" v-html="mainAlienComment"> </p>
          <i class="alien"></i>
        </a>
      </div>

      <div v-if="isAllCompleted" class="btn__floating__right">
        <button class="btn--gray" @click="updateGradeTerm">
          <font-awesome-icon icon="fa-regular fa-arrow-right"></font-awesome-icon>다음 행성으로
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
  import { onMounted, ref, computed, onBeforeUnmount } from 'vue';
  import AppLayout from '@/app/layouts/AppLayout.vue';
  import { router, usePage } from '@inertiajs/vue3';
  import { PageProps } from '@/app/types/pageData';
  import { Training } from '@/app/api/model/training';
  import { getUserSchoolInfo } from '@/app/core/helpers/userHelper';
  import MainModeButtons from '@/app/components/main/MainModeButtons.vue';
  import MainIntro from '@/app/components/main/MainIntro.vue';
  import { isNil } from 'lodash';
  import { getStageName } from '@/app/core/helpers/trainingHelper';
  import { goTrainingMainPage } from '@/app/core/helpers/routerHelper';

  interface Page extends PageProps {
    progress_by_grade_term_curriculums: number[];
    next_training: Training | null;
  }

  const pageData = usePage<Page>();

  const userSchoolInfo = computed(() => getUserSchoolInfo());

  const isShowMainIntro = ref(false);

  const nextTraining = computed(() => pageData.props.next_training);

  const progressByGradeTermCurriculums = computed(() => pageData.props.progress_by_grade_term_curriculums);

  /** 모든 단원 학습 완료 여부 */
  const isAllCompleted = computed(
    () =>
      progressByGradeTermCurriculums.value.reduce((prev, current) => prev && current === 100, true) ||
      isNil(nextTraining.value),
  );

  const mainAlienComment = computed(() => {
    if (isAllCompleted.value || isNil(nextTraining.value)) {
      return `${userSchoolInfo.value?.grade}학년 ${userSchoolInfo.value?.term}학기 행성의<br />모든 훈련을 완료했어요!`;
    } else {
      return `<strong>${nextTraining.value?.curriculum?.name}</strong
              >의<br /><strong>${getStageName(nextTraining.value.stage)}훈련</strong
              >을 시작해 보세요!`;
    }
  });

  function showMainIntro() {
    isShowMainIntro.value = true;
    // 인트로 시간
    const hideLoadingTime = 1500;

    // 일정시간 이후에 인트로 꺼짐
    loadingTimeoutId = setTimeout(() => {
      hideMainIntro();
    }, hideLoadingTime);
  }

  function hideMainIntro() {
    isShowMainIntro.value = false;
  }

  function onClickAlien() {
    // 모든 훈련을 완료한 경우 아무 액션 없음
    if (isNil(nextTraining.value)) return;

    goTrainingMainPage(nextTraining.value);
  }

  /**
   * 다음 학년 학기로 이동
   */
  function updateGradeTerm() {
    // 6학년 2학기일 경우 이동 제한
    if (userSchoolInfo.value?.grade == 6 && userSchoolInfo.value?.term == 2) return;

    router.patch(
      route('app.my.grade-term.update'),
      {
        grade: userSchoolInfo.value?.term === 1 ? userSchoolInfo.value?.grade : Number(userSchoolInfo.value?.grade) + 1,
        term: userSchoolInfo.value?.term === 1 ? 2 : 1,
      },
      {
        onFinish() {
          showMainIntro();
        },
      },
    );
  }

  let loadingTimeoutId: ReturnType<typeof setTimeout>;

  onMounted(() => {
    showMainIntro();
  });

  onBeforeUnmount(() => {
    if (!isNil(loadingTimeoutId)) {
      clearTimeout(loadingTimeoutId);
    }
  });
</script>
