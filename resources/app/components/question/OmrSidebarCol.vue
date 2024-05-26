<template>
  <OmrBlank
    v-for="blank in blankCount"
    :key="blank + Number(curriculumStore.getCurrentQuestion?.id)"
    :is-sidebar="true"
    :omr-position="{
      location: 'sidebar',
      position: {
        row: answerRow,
        col: answerCol,
        blank: isInputType && Number(blankCount) > 1 ? blank - 1 : null,
      },
    }"
  />
  <div v-if="isFocused" class="answers--bubble show">
    <AnswerKeypad v-if="isInputType" :answer-row="answerRow" :answer-col="answerCol" :is-sidebar="true" />
    <AnswerChoices v-else :answer-row="answerRow" />
  </div>
</template>

<script setup lang="ts">
  import { computed, PropType } from 'vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { QuestionAnswerEnum } from '@/app/api/model/question';
  import { isArray, isNil } from 'lodash';
  import AnswerChoices from '@/app/components/question/AnswerChoices.vue';
  import AnswerKeypad from '@/app/components/question/AnswerKeypad.vue';
  import OmrBlank from '@/app/components/question/OmrBlank.vue';

  const props = defineProps({
    answerRow: {
      type: Number as PropType<number>,
      default: 0,
    },
    answerCol: {
      type: Number as PropType<number | null>,
      default: 0,
    },
  });

  const curriculumStore = useCurriculumStoreWithOut();

  const omrPosition = computed(() => curriculumStore.getOmrFocusInfo);

  const isFocused = computed(() => {
    if (isNil(omrPosition.value)) return false;

    return (
      props.answerRow === omrPosition.value.position.row &&
      props.answerCol === omrPosition.value.position.col &&
      omrPosition.value.location === 'sidebar'
    );
  });

  const isInputType = computed(() => curriculumStore.getOmrAnswerType(props.answerRow) === QuestionAnswerEnum.Input);

  const blankCount = computed(() => {
    const currentQuesion = curriculumStore.getCurrentQuestion;

    if (!currentQuesion) return;

    if (!isInputType.value) return 1;

    if (isNil(props.answerCol)) return 1;

    const blankAnswer = currentQuesion.answers[props.answerRow].answer[props.answerCol];

    if (isArray(blankAnswer)) {
      return blankAnswer.length;
    }

    return 1;
  });
</script>
