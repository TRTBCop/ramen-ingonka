<template>
  <header class="note__navbar only_screen">
    <a href="javascript:;" @click="goBack"><FontAwesomeIcon icon="fa-regular fa-arrow-left" />미리보기/인쇄</a>

    <!-- [D] 인쇄하기 버튼-->
    <div class="btns">
      <button class="btn--gray" @click="toggleIsShowIncorrectCountLayer">
        <FontAwesomeIcon :icon="`fa-regular ${isShowIncorrectCountLayer ? 'fa-chevron-up' : 'fa-chevron-down'}`" />
        오답 문제수
      </button>
      <button class="btn--gray" @click="toggleIsShowCorrect">
        <FontAwesomeIcon :icon="`fa-regular ${isShowCorrect ? 'fa-eye-slash' : 'fa-eye'}`" />정답
        {{ isShowCorrect ? '가리기' : '보기' }}</button
      >
      <button class="btn--white" onclick="window.print()"><i class="fa-regular fa-print"></i>인쇄하기</button>

      <div
        class="note__navbar--layer"
        :class="{
          show: isShowIncorrectCountLayer,
        }"
      >
        <p v-for="step in stepList" :key="step">
          {{ getStepName(training.stage, step) }}:
          <span>{{ Object.values(incorrectQuestion[step]).length }}문제</span>
        </p>
      </div>
    </div>
  </header>
  <div class="note_wrap">
    <div class="A4">
      <div class="count">
        <section class="page">
          <div class="note__head">
            <h2><img src="@/assets/img/math/ico_logo.svg" />리딩수학 오답노트</h2>
            <div class="userinfo">
              <strong
                >학생명 <span>{{ userData.name }}</span></strong
              >
              <strong>
                학습 일시
                <span>{{ dayjs(trainingResult.completed_at).format('YYYY-MM-DD HH:MM') }}</span>
              </strong>
            </div>
          </div>

          <div class="note__info">
            <template v-if="curriculum.ancestors">
              <!-- 제일 첫 커리큘럼은 뺌 (수학, 영어) -->
              <span v-for="(data, i) in curriculum.ancestors.filter((_, i) => i > 0)" :key="i">{{ data.name }}</span>
            </template>
            <span>{{ curriculum.name }}</span>
            <span>{{ getStageName(training.stage) }} 훈련</span>
            <strong class="is--brand">ROUND {{ trainingResult.round + 1 }}</strong>
          </div>

          <template v-for="(questions, stepKey) in incorrectQuestion" :key="stepKey">
            <template v-if="training.stage === 2">
              <Stage2Item
                v-for="(question, key) in questions"
                :key="question.id"
                :step-key="stepKey"
                :question-index="Number(key)"
                :question-result="question"
                :is-show-correct="isShowCorrect"
                :training="training"
              />
            </template>
            <template v-else-if="training.stage === 3">
              <Stage3Item
                v-for="(question, key) in questions"
                :key="question.id"
                :step-key="stepKey"
                :question-index="Number(key)"
                :question-result="question"
                :is-show-correct="isShowCorrect"
              />
            </template>
          </template>
        </section>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, computed } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import { PageProps } from '@/app/types/pageData';
  import { TrainingResult } from '@/app/api/model/trainingResult';
  import Stage2Item from '@/app/components/incorrectNote/Stage2Item.vue';
  import Stage3Item from '@/app/components/incorrectNote/Stage3Item.vue';
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import { getUserData } from '@/app/core/helpers/userHelper';
  import { dayjs } from 'element-plus';
  import { getStageName, getStepList, getStepName } from '@/app/core/helpers/trainingHelper';
  import { QuestionResult } from '@/app/api/model/questionResult';

  interface Page extends PageProps {
    training_result: TrainingResult;
    incorrect_questions: QuestionResult[][];
  }

  const pageData = usePage<Page>();

  console.log(pageData.props);

  const trainingResult = computed(() => pageData.props.training_result);
  const curriculum = computed(() => trainingResult.value.curriculum);
  const training = computed(() => trainingResult.value.training);

  const userData = computed(() => getUserData());

  const incorrectQuestion = computed(() => pageData.props.incorrect_questions);

  const stepList = computed(() => getStepList(training.value.stage));

  const isShowCorrect = ref(false);
  function toggleIsShowCorrect() {
    isShowCorrect.value = !isShowCorrect.value;
  }

  const isShowIncorrectCountLayer = ref(false);
  function toggleIsShowIncorrectCountLayer() {
    isShowIncorrectCountLayer.value = !isShowIncorrectCountLayer.value;
  }

  function goBack() {
    window.close();
  }
</script>
