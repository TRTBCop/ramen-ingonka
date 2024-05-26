import { ref, computed } from 'vue';
import { isEqual, isNil } from 'lodash';
import { OmrFocusInfo, useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
import { QuestionAnswerEnum } from '@/app/api/model/question';

const curriculumStore = useCurriculumStoreWithOut();

export function useQuestionBlank() {
  const omrPosition = ref<OmrFocusInfo>();

  const isSingleQuestion = computed(() => curriculumStore.getIsSingleQuestion);

  const isFinishedQuestion = computed(() => curriculumStore.getIsFinishedQuestion);

  const isFocused = computed(
    () => !isFinishedQuestion.value && isEqual(omrPosition.value?.position, curriculumStore.getOmrFocusInfo?.position),
  );

  /** 선지형 여부 */
  const isChoiceType = computed(() => {
    if (isNil(omrPosition.value)) return false;
    return curriculumStore.getOmrAnswerType(omrPosition.value.position.row) === QuestionAnswerEnum.Choice;
  });

  /** 기호형 여부 */
  const isChoiceSymbol = computed(() => {
    if (isNil(omrPosition.value)) return false;
    return curriculumStore.currentQuestion?.answers[omrPosition.value.position.row].choice_symbol;
  });

  /** 입력된 값 */
  const omrValue = computed(() => {
    if (isNil(omrPosition.value)) return '';

    if (isChoiceType.value) {
      // 선지형인 경우 제일 처음 선택한 선지를 노출
      const question = curriculumStore.getCurrentQuestion;
      const value = curriculumStore.getOmrValueTypeChoice(omrPosition.value.position.row)[0];
      if (isNil(question) || value === '') return '';
      const choices = question.answers[omrPosition.value.position.row].choices;

      return choices.find((choice) => choice.id === Number(value))?.choice;
    } else {
      // 입력일 경우는 그냥 노출
      return curriculumStore.getOmrValueTypeInput(omrPosition.value);
    }
  });

  /** 정답 값 */
  const originalOmrValue = computed(() => {
    if (isNil(omrPosition.value)) return false;

    const isChoiceType = curriculumStore.getOmrAnswerType(omrPosition.value.position.row) === QuestionAnswerEnum.Choice;
    if (isChoiceType) {
      // 선지형인 경우 제일 처음 선택한 선지를 노출
      const question = curriculumStore.getCurrentQuestion;
      const value = curriculumStore.getOriginalOmrValueTypeChoice(omrPosition.value.position.row)[0];
      if (isNil(question) || value === '') return '';
      const choices = question.answers[omrPosition.value.position.row].choices;
      return choices.find((choice) => choice.id === Number(value))?.choice;
    } else {
      // 입력일 경우는 그냥 노출
      return curriculumStore.getOriginalOmrValueTypeInput(omrPosition.value);
    }
  });

  /** 정답을 확인 했는지 여부 */
  const isChecked = computed(() => {
    return (
      curriculumStore.getReviewCheckedAnswers.findIndex((position) =>
        isEqual(position, omrPosition.value?.position),
      ) !== -1 && isFinishedQuestion.value
    );
  });

  /** 정답 여부 */
  const isCorrect = computed(
    () => !isChecked.value && isFinishedQuestion.value && omrValue.value === originalOmrValue.value,
  );

  /** 오답 여부 */
  const isWrong = computed(
    () => !isChecked.value && isFinishedQuestion.value && omrValue.value !== originalOmrValue.value,
  );

  function init(_omrPosition: OmrFocusInfo) {
    omrPosition.value = _omrPosition;
  }

  return {
    omrValue,
    isSingleQuestion,
    isChoiceSymbol,
    originalOmrValue,
    isFinishedQuestion,
    isChoiceType,
    isFocused,
    isCorrect,
    isWrong,
    isChecked,
    init,
  };
}
