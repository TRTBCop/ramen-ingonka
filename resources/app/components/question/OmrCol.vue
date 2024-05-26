<template>
  <!-- 사이드바에서 호출한 경우 (진분수, 대분수일 때 사이드바에서 호출) -->
  <template v-if="isSidebar">
    <div>
      <i v-if="!curriculumStore.getIsSingleQuestion" class="answer__sign">
        {{ answerRow + 1 }}-{{ Number(answerCol) + 1 }}
      </i>
      <span :class="`blanks__type${blankLength} ${blankTypeClass}`">
        <OmrBlank
          v-for="blank in blankCount"
          :key="blank"
          :omr-position="{
            location: 'sidebar',
            position: {
              row: answerRow,
              col: isInputType ? answerCol : null,
              blank: isInputType && Number(blankCount) > 1 ? blank - 1 : null,
            },
          }"
          @on-click="isShowInputModal = true"
        />
      </span>
    </div>

    <div v-if="isShowInputModal" class="answers--bubble show">
      <AnswerChoices v-if="isChoiceType" :answer-row="answerRow" />
      <AnswerKeypad v-else :answer-row="answerRow" :answer-col="answerCol" :is-sidebar="isSidebar" />
    </div>
  </template>
  <template v-else>
    <span ref="answersRef" :class="`blanks__type${blankLength} ${blankTypeClass}`" :data-row="answerRow">
      <OmrBlank
        v-for="blank in blankCount"
        :key="blank"
        :omr-position="{
          location: 'default',
          position: {
            row: answerRow,
            col: isInputType ? answerCol : null,
            blank: isInputType && Number(blankCount) > 1 ? blank - 1 : null,
          },
        }"
        @on-click="isShowInputModal = true"
      />
      <div v-if="isShowInputModal" class="answers--bubble show" :style="getFloatingStyle()">
        <AnswerChoices v-if="isChoiceType" :answer-row="answerRow" />
        <AnswerKeypad v-else :answer-row="answerRow" :answer-col="answerCol" :is-sidebar="isSidebar" />
      </div>
    </span>
  </template>
</template>

<script setup lang="ts">
  import { ref, computed, PropType, watch } from 'vue';
  import { QuestionAnswerEnum } from '@/app/api/model/question';
  import { isArray, isNil } from 'lodash';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import OmrBlank from '@/app/components/question/OmrBlank.vue';
  import AnswerChoices from '@/app/components/question/AnswerChoices.vue';
  import AnswerKeypad from '@/app/components/question/AnswerKeypad.vue';

  const props = defineProps({
    blankLength: {
      type: Number as PropType<number>,
      default: 1,
    },
    answerRow: {
      type: Number as PropType<number>,
      default: 0,
    },
    answerCol: {
      type: Number as PropType<number | null>,
      default: null,
    },
    isSidebar: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  const curriculumStore = useCurriculumStoreWithOut();

  const omrPosition = computed(() => curriculumStore.getOmrFocusInfo);

  // 포커스가 풀리면 인풋 모달도 사라지게 처리
  watch(
    () => omrPosition.value,
    (newVal) => {
      if (
        isNil(newVal) ||
        (omrPosition.value?.location === 'sidebar' && !props.isSidebar) ||
        (omrPosition.value?.location === 'default' && props.isSidebar) ||
        Number(omrPosition.value?.position.col) !== Number(props.answerCol) ||
        omrPosition.value?.position.row !== props.answerRow
      ) {
        return (isShowInputModal.value = false);
      }
    },
  );

  const isInputType = computed(() => curriculumStore.getOmrAnswerType(props.answerRow) === QuestionAnswerEnum.Input);

  const isChoiceType = computed(() => curriculumStore.getOmrAnswerType(props.answerRow) === QuestionAnswerEnum.Choice);

  const answersRef = ref();

  const isShowInputModal = ref(curriculumStore.getIsSingleQuestion ? true : false);

  const blankCount = computed(() => {
    const currentQuesion = curriculumStore.getCurrentQuestion;

    if (!currentQuesion) return;

    if (!isInputType.value) return 1;

    if (isNil(props.answerCol)) return 1;

    const blankAnswer = currentQuesion.answers[props.answerRow]?.answer[props.answerCol];

    if (isArray(blankAnswer)) {
      return blankAnswer.length;
    }

    return 1;
  });

  const blankTypeClass = computed<'' | 'proper' | 'improper'>(() => {
    if (blankCount.value === 2) return 'proper';
    else if (blankCount.value === 3) return 'improper';

    return '';
  });

  function getFloatingStyle() {
    if (isNil(answersRef.value)) return {};

    return curriculumStore.getFloatingPositionStyle(answersRef.value);
  }
</script>
