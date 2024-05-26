<template>
  <div class="answers--choice">
    <button
      v-for="choice in choices"
      :key="choice.id"
      :class="{
        answer__check: isChecked(choice.id) && !isFinishedQuestion,
        answer__correct: isChecked(choice.id) && isCorrect(choice.id) && isFinishedQuestion,
        answer__wrong: isChecked(choice.id) && !isCorrect(choice.id) && isFinishedQuestion,
      }"
      @click="onClickChoice(choice)"
    >
      <i class="answer__number"></i>
      <span v-html="choice.choice"></span>
    </button>
  </div>
</template>

<script setup lang="ts">
  import { PropType, computed, onMounted } from 'vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { cloneDeep, isNil } from 'lodash';

  const props = defineProps({
    answerRow: {
      type: Number as PropType<number>,
      default: 0,
    },
  });

  interface Choice {
    id: number;
    choice: string;
  }

  const curriculumStore = useCurriculumStoreWithOut();

  const isFinishedQuestion = computed(() => curriculumStore.getIsFinishedQuestion);

  const originalOmrValue = computed(() => {
    return curriculumStore.getOriginalOmrValueTypeChoice(props.answerRow);
  });

  function isChecked(id: number) {
    return omrValue.value.findIndex((value) => value === String(id)) !== -1;
  }

  function isCorrect(id: number) {
    return originalOmrValue.value.findIndex((data) => data === String(id)) !== -1 && isFinishedQuestion.value;
  }

  const choices = computed(() => {
    const question = curriculumStore.getCurrentQuestion;

    if (isNil(question)) return [];

    return question.answers[props.answerRow].choices;
  });

  const omrValue = computed(() => curriculumStore.getOmrValueTypeChoice(props.answerRow));

  function onClickChoice(choice: Choice) {
    if (isFinishedQuestion.value) {
      // 기존 입력들 모두 빈칸으로 변경
      const omrValues = cloneDeep(curriculumStore.getOmrValueTypeChoice(props.answerRow));
      omrValues.forEach((value) => {
        curriculumStore.setOmrValueTypeChoice(String(value), props.answerRow);
      });

      // 정답 삽입
      originalOmrValue.value.forEach((value) => {
        curriculumStore.setOmrValueTypeChoice(String(value), props.answerRow);
      });
    } else {
      if (curriculumStore.getOmrFocusInfo?.location === 'default') {
        curriculumStore.setOmrFocusInfo(null);
      }

      curriculumStore.setOmrValueTypeChoice(String(choice.id), props.answerRow);
    }
  }

  onMounted(() => {
    const frameBodyIndex = curriculumStore.getOmrFocusInfo?.location === 'sidebar' ? 1 : 0;
    const targetFrameBody = document.querySelectorAll('.frame__body')[frameBodyIndex];
    if (!isNil(targetFrameBody)) {
      const target = targetFrameBody.querySelector('.answers--bubble');
      if (isNil(target)) return;

      const containerRect = targetFrameBody.getBoundingClientRect();

      const targetRect = target.getBoundingClientRect();
      // 타겟 요소가 컨테이너의 아래에 위치하면 스크롤합니다.
      if (targetRect.bottom > containerRect.bottom) {
        targetFrameBody.scrollTo({
          top: targetFrameBody.scrollTop + (targetRect.bottom - containerRect.bottom) + 50,
          behavior: 'smooth',
        });
      }
    }
  });
</script>
