<template>
  <div v-if="currentQuestion" class="grid">
    <div class="frame col">
      <div class="frame__head tab_area">
        <ul class="tabs tabs--count">
          <li v-for="question in questions" :key="question.id" :class="getQuestionStateClass(question.id)"></li>
        </ul>
      </div>
      <div class="frame__body">
        <div class="question_area">
          <div v-if="currentQuestion.inquiry" class="question__box">
            <div v-if="isInquiryToggle" class="question__box--toggle">
              <h3>문제</h3>
            </div>
            <div class="articles">
              <article class="fr-view" v-html="curriculumStore.getInquiry(currentQuestion.inquiry)"></article>
            </div>

            <div v-if="currentQuestion.options" class="question__box">
              <span class="badge--lightgray"><i class="fa-regular fa-list-ul"></i>보기</span>
              <p class="fr-view" v-html="currentQuestion.options"></p>
            </div>
          </div>
          <div v-if="!curriculumStore.getIsSingleQuestion" class="question__box question__box--explain">
            <span v-if="isShowQuestiontLabel" class="badge--lightgray"
              ><i class="fa-regular fa-scroll-old"></i>문제 풀이</span
            >
            <p class="fr-view">
              <ReplaceTextIntoBlankComponent :key="currentQuestion.id" :value="setAnswerColQuestions" />
            </p>
          </div>
          <div v-if="currentQuestion.explanation && showReview" class="question__box">
            <span class="badge--lightgray"><i class="fa-regular fa-key-skeleton"></i>오답 해설</span>
            <p class="fr-view" v-html="currentQuestion.explanation"></p>
          </div>
        </div>
      </div>
    </div>

    <TabSidebar v-if="currentQuestion.question" />
    <DefaultSidebar v-else />
  </div>
</template>

<script setup lang="ts">
  import { onMounted, computed, PropType } from 'vue';
  import ReplaceTextIntoBlankComponent from '@/app/components/question/ReplaceTextIntoBlankComponent.vue';
  import DefaultSidebar from '@/app/components/question/sidebar/DefaultSidebar.vue';
  import TabSidebar from '@/app/components/question/sidebar/TabSidebar.vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { countBy, isNil, set } from 'lodash';

  const props = defineProps({
    questions: {
      type: Array as PropType<{ id: number }[]>,
      default: null,
    },
    isShowQuestionResult: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    isInquiryToggle: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    // '문제 풀이' 타이틀 여부
    isShowQuestiontLabel: {
      type: Boolean as PropType<boolean>,
      default: true,
    },
  });

  const curriculumStore = useCurriculumStoreWithOut();

  const isFinishedQuestion = computed(() => curriculumStore.getIsFinishedQuestion);

  const showReview = computed(() => isFinishedQuestion.value && props.isShowQuestionResult);

  const currentQuestion = computed(() => curriculumStore.getCurrentQuestion);

  const questionResults = computed(() => curriculumStore.stepResult?.questions || []);

  function getQuestionStateClass(questionId: number) {
    const foundQuestionResult = questionResults.value.find(
      (questionResult) => questionResult.question_id === questionId,
    );

    let result = '';

    if (currentQuestion.value?.id !== questionId && isNil(foundQuestionResult)) {
      return result;
    }

    if (currentQuestion.value?.id === questionId) {
      result = 'current ';
    }

    if (isNil(foundQuestionResult)) return result;

    if (props.isShowQuestionResult) {
      if (curriculumStore.getReviewCheckedQuestions.findIndex((id) => id === questionId) !== -1) {
        result += 'check';
      } else if (foundQuestionResult.correct_percent === 100) {
        result += 'correct';
      } else if (foundQuestionResult.correct_percent >= 70) {
        result += 'triangle';
      } else {
        result += 'wrong';
      }
    }

    return result;
  }

  /**
   * ${[n]}와 #{[n-n]}을 ${n:n:n} 형식으로 변경한다
   * (row)?(col)?(blankLength) 순서
   */
  const setAnswerColQuestions = computed(() => {
    if (isNil(currentQuestion.value)) return ' ';
    const answerColCount: {
      [answerRow in number]: number;
    } = {};
    const replacedTexts: {
      [answerRow in number]: {
        [answerCol in number]: string;
      };
    } = {};
    return currentQuestion.value.question
      ?.replace(/\${(\[?.*?]?)}/g, (match: string, capturedValue: string) => {
        const answerRow = Number(capturedValue.match(/\d+/)) - 1;
        const blankList = capturedValue.split('/');
        const blankStrLength = (countBy(blankList[0])['-'] || 0) + 1;
        if (isNil(answerColCount[answerRow])) {
          answerColCount[answerRow] = 0;
        } else {
          answerColCount[answerRow]++;
        }
        const result = match
          .replace(/-/g, '')
          .replace(/(\d+)/g, `$1:${answerColCount[answerRow] + 1}:${blankStrLength}`);
        set(replacedTexts, [answerRow, answerColCount[answerRow]], result);
        return result;
      })
      .replace(/#{\[(\d+)-(\d+)\]}/g, (match: string, answerRow: string, answerCol: string) => {
        return replacedTexts[Number(answerRow) - 1][Number(answerCol) - 1];
      });
  });

  onMounted(() => {
    document.querySelectorAll('.frame__body').forEach((elem) =>
      elem.scrollTo({
        top: 0,
        behavior: 'smooth',
      }),
    );
  });
</script>
