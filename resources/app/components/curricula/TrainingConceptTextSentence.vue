<template>
  <template v-if="reading.text === '[문단 나누기]'">
    <br />
  </template>
  <template v-else>
    <template v-for="(sentence, i) in splitedTextToBrTag" :key="i">
      <template v-if="sentence !== ''">
        <span
          v-if="isDefaultType || (isImageType && !isShow)"
          class="spl"
          :class="{
            show: isShow,
            current: isCurrent,
          }"
          v-html="sentence"
        >
        </span>

        <span
          v-else-if="isImageType"
          class="spl"
          :class="{
            show: isCurrent || isShow,
            link: isShow,
            link__pressed: !isCurrent && isCurrentImage,
            current: isCurrent,
          }"
          @click.stop="onClickImageType"
          v-html="sentence"
        >
        </span>
        <span
          v-else-if="isQuestionType"
          class="spl"
          :class="{
            show: isShow,
            current: isCurrent,
          }"
        >
          <template v-for="(text, j) in splitedInquiry" :key="text">
            <span v-if="text !== '||'" v-html="text"></span>
            <TrainingConceptTextSentenceBlank
              v-else
              :question="question"
              :is-focus="isCurrent && getAnswerIndex(j) === currentAnswerIndex"
              :is-show-correct="isSummarizations"
              :answer-index="getAnswerIndex(j)"
              @on-correct="onCorrectQuestion"
            />
          </template>
        </span>
      </template>
      <br v-if="splitedTextToBrTag.length > 1 && splitedTextToBrTag.length - 1 > i" />
    </template>
  </template>
</template>

<script setup lang="ts">
  import { ref, computed, PropType } from 'vue';
  import TrainingConceptTextSentenceBlank from './TrainingConceptTextSentenceBlank.vue';
  import { Question } from '@/app/api/model/question';
  import { TrainingPageProps } from '@/app/types/pageData';
  import {
    ConceptTextType,
    TrainingConceptTextReading,
    TrainingConceptTextReadingType,
    TrainingConceptText,
  } from '@/app/api/model/training';
  import { usePage } from '@inertiajs/vue3';

  const pageData = usePage<TrainingPageProps<TrainingConceptText[]>>();

  const props = defineProps({
    isShow: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    isCurrent: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    isCurrentImage: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    reading: {
      type: Object as PropType<TrainingConceptTextReading>,
      default: null,
    },
    question: {
      type: Object as PropType<Question>,
      default: null,
    },
  });

  const splitedTextToBrTag = computed(() => props.reading.text.split(/<br[^>]*>/));

  const emits = defineEmits(['onClickImageType', 'onCorrect']);

  const splitedInquiry = computed(() =>
    props.question?.inquiry
      .replace(/<br[^>]*>/g, '')
      .replace(/\[\]/g, '$||$')
      .split('$'),
  );

  const currentAnswerIndex = ref(0);

  const questionCount = computed(() => splitedInquiry.value.filter((data) => data === '||').length);

  const isLastQuestion = computed(() => currentAnswerIndex.value >= questionCount.value - 1);

  function onCorrectQuestion() {
    if (isLastQuestion.value) {
      emits('onCorrect');
    } else {
      nextQuestion();
    }
  }

  function nextQuestion() {
    setTimeout(() => {
      currentAnswerIndex.value++;
    }, 1000);
  }

  function getAnswerIndex(textIndex: number): number {
    const temp = [...splitedInquiry.value];
    return textIndex - temp.splice(0, textIndex - 1).filter((data) => data !== '').length - 1;
  }

  const isSummarizations = computed(() => pageData.props.training_concept_text_type === ConceptTextType.Summarizations);

  const isDefaultType = computed(() => props.reading.type === TrainingConceptTextReadingType.Default);
  const isImageType = computed(() => props.reading.type === TrainingConceptTextReadingType.Image);
  const isQuestionType = computed(() => props.reading.type === TrainingConceptTextReadingType.Question);

  function onClickImageType() {
    if (!props.isShow && !props.isCurrent) return;

    emits('onClickImageType', props.reading.image?.src);
  }
</script>

<style>
  .spl span {
    display: inline-flex;
    align-items: center;
  }
</style>
