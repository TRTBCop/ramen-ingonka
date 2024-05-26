<template>
  <div
    v-if="isSidebar"
    :class="{
      answer__active: isFocused,
      answer__correct: isCorrect,
      answer__wrong: isWrong,
      answer__wrong__check: isChecked,
    }"
    @click="onClickBlank"
  >
    <i v-if="!isSingleQuestion || isChoiceSymbol" class="answer__sign">
      <template v-if="isChoiceSymbol">
        {{ String.fromCharCode(12896 + Number(omrPosition.position.col)) }}
      </template>
      <template v-else>({{ omrPosition.position.row + 1 }}-{{ Number(omrPosition.position.col) + 1 }})</template>
    </i>
    <span v-if="omrValue" v-html="omrValue"></span>
    <span v-else class="is--placeholder">답을 입력해주세요</span>
  </div>
  <em
    v-else
    class="blank"
    :class="{
      'is--placeholder': !omrValue,
      blank__on: omrValue,
      blank__focus: isFocused,
      blank__correct: isCorrect,
      blank__wrong: isWrong,
      blank__wrong__check: isChecked,
    }"
    @click="onClickBlank"
  >
    <bdi v-html="omrValue || `${omrPosition.position.row + 1}-${Number(omrPosition.position.col) + 1}`"></bdi>
  </em>
</template>

<script setup lang="ts">
  import { PropType, nextTick } from 'vue';
  import { OmrFocusInfo, useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { isNil } from 'lodash';
  import { useQuestionBlank } from '@/app/hooks/useQuestionBlank';

  const props = defineProps({
    omrPosition: {
      type: Object as PropType<OmrFocusInfo>,
      default: null,
    },
    isSidebar: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  const emits = defineEmits(['on-click']);

  const {
    omrValue,
    isFocused,
    originalOmrValue,
    isFinishedQuestion,
    isChoiceType,
    isCorrect,
    isChoiceSymbol,
    isWrong,
    isChecked,
    init,
    isSingleQuestion,
  } = useQuestionBlank();

  init(props.omrPosition);

  const curriculumStore = useCurriculumStoreWithOut();

  function onClickBlank() {
    if (isFinishedQuestion.value) {
      if (isCorrect.value) return;

      if (isWrong.value) {
        if (!isChecked.value) {
          curriculumStore.addReviewCheckedAnswers(props.omrPosition.position);
        }

        if (isChoiceType.value) {
          const value = curriculumStore.getOriginalOmrValueTypeChoice(props.omrPosition.position.row)[0];
          curriculumStore.setOmrValueTypeChoice(String(value), props.omrPosition.position.row);
        } else {
          curriculumStore.setOmrValueTypeInput(String(originalOmrValue.value), props.omrPosition);
        }
      }
    } else {
      curriculumStore.setOmrFocusInfo(props.omrPosition);
      emits('on-click');
    }

    nextTick(() => {
      const frameBodyIndex = props.omrPosition.location === 'default' ? 1 : 0;

      const targetFrameBody = document.querySelectorAll('.frame__body')[frameBodyIndex];
      if (!isNil(targetFrameBody)) {
        const target = targetFrameBody.querySelector('.blank__focus, .answer__active');
        if (isNil(target)) return;

        const containerRect = targetFrameBody.getBoundingClientRect();
        const targetRect = target.getBoundingClientRect();
        if (targetRect.bottom > containerRect.bottom) {
          // 타겟 요소가 컨테이너의 아래에 위치하면 스크롤합니다.
          targetFrameBody.scrollTo({
            top:
              targetFrameBody.scrollTop + (targetRect.bottom - containerRect.bottom) + targetFrameBody.clientHeight / 2,
            behavior: 'smooth',
          });
        } else if (targetRect.top < containerRect.top) {
          // 타겟 요소가 컨테이너의 위에 위치하면 스크롤합니다.
          targetFrameBody.scrollTo({
            top: targetFrameBody.scrollTop - (containerRect.top - targetRect.top) - targetFrameBody.clientHeight / 2,
            behavior: 'smooth',
          });
        }
      }
    });
  }
</script>
