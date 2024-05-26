<template>
  <span
    ref="childElement"
    :class="{
      on: true,
    }"
  >
    <em class="blank" :class="{ blank__focus: isFocus, blank__on: correctChoice }">
      <bdi v-html="correctChoice"></bdi>
    </em>
    <div v-if="isFocus" class="answers">
      <div class="answers--bubble show" :style="getFloatingStyle()">
        <div class="answers--choice">
          <button
            v-for="(choice, i) in answers.choices"
            :key="i"
            :class="{
              answer__correct: correctChoice === choice,
              answer__wrong: wrongChoices.findIndex((data) => data === String(choice)) !== -1,
            }"
            @click="onClickChoiceAnswer(String(choice))"
          >
            <i class="answer__number"></i>
            <span v-html="choice"></span>
          </button>
        </div>
      </div>
    </div>
  </span>
</template>

<script setup lang="ts">
  import { ref, computed, PropType } from 'vue';
  import { Question } from '@/app/api/model/question';
  import { isNil } from 'lodash';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';

  const props = defineProps({
    isFocus: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    question: {
      type: Object as PropType<Question>,
      default: null,
    },
    answerIndex: {
      type: Number as PropType<number>,
      default: 0,
    },
    isShowCorrect: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  const emits = defineEmits(['onCorrect']);

  const answers = computed(() => props.question.answers[0]);

  const curriculumStore = useCurriculumStoreWithOut();

  const correctChoice = ref(props.isShowCorrect ? answers.value.choices[Number(answers.value.answer) - 1] : '');

  /** 잘못 선택한 값들 */
  const wrongChoices = ref<string[]>([]);

  function onClickChoiceAnswer(choice: string) {
    // 이미 정답 체크가 됐으면 클릭 이벤트 받지 않음
    if (correctChoice.value) return;

    const answer = answers.value.choices[Number(answers.value.answer[props.answerIndex]) - 1];

    if (String(answer) === choice) {
      correctChoice.value = choice;
      emits('onCorrect');
    } else {
      wrongChoices.value.push(choice);
    }
  }

  const childElement = ref();

  function getFloatingStyle() {
    const _childElement = childElement.value?.parentElement;
    if (isNil(_childElement)) return {};

    return curriculumStore.getFloatingPositionStyle(_childElement);
  }
</script>
