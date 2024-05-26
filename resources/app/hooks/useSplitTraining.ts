import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
import { isArray, isNil } from 'lodash';

const curriculumStore = useCurriculumStoreWithOut();

export function useSplitTraining() {
  const splitedSentences = computed(() => {
    const splitedQuestionSentences = curriculumStore.getSplitedQuestionSentences;

    return isOrdering.value ? splitedQuestionSentences.ordering : splitedQuestionSentences.default;
  });

  /** 현재 퀴즈: 순서 맞추기 */
  const isOrdering = ref(false);

  const currentSplitIndex = ref(0);
  const currentAnswerRowIndex = ref(0);
  const currentAnswerColIndex = ref(0);

  const wrongClickedChoices = ref<string[]>([]);

  const isLoading = ref(false);

  const isShowChoices = computed(() => {
    if (!isSplitQuestionType.value) return false;

    return !isLastSentence.value || !(isCompltedQuestion.value && !isCorrectAnim.value);
  });

  /** 현재 문제에 맞는 선지 */
  const currentChoices = computed(() => {
    const currentQuestion = curriculumStore.getCurrentQuestion;
    if (isNil(currentQuestion)) return [];

    return currentQuestion.answers[currentAnswerRowIndex.value]?.choices;
  });

  const isCompltedQuestion = ref(false);

  const isSplitQuestionType = computed(() => {
    if (splitedSentences.value.length === 0) return false;

    return (
      splitedSentences.value[currentSplitIndex.value]?.reduce(
        (prev, current) => [...prev, ...current.filter((data) => /\[.*\]/.test(data))],
        [],
      ).length > 0
    );
  });

  const isLastSentence = computed(
    () => currentSplitIndex.value >= splitedSentences.value.length - 1 && !isSplitQuestionType.value,
  );

  function getCorrectAnswer(answerRowIndex: number, answerColIndex: number) {
    const currentQuestion = curriculumStore.getCurrentQuestion;

    const correctAnswer = curriculumStore.getOmrValueTypeInput({
      location: 'default',
      position: {
        row: answerRowIndex,
        col: answerColIndex,
        blank: null,
      },
    });

    if (isNil(currentQuestion) || isNil(correctAnswer) || correctAnswer === '') return null;

    return currentQuestion.answers[answerRowIndex].choices.find(
      (choice) => String(choice.id) === String(correctAnswer),
    );
  }

  async function onClickChoice(id: number, showCorrectAlways = false) {
    if (isCorrectAnim.value || isLoading.value) return;

    // 이미 오답 처리 된 선지 일경우 클릭 막음.
    if (wrongClickedChoices.value.findIndex((_id) => _id === String(id)) !== -1) return;

    isLoading.value = true;

    await submit(String(id), showCorrectAlways);

    isLoading.value = false;
  }

  async function onClickOrderingChoice(answer: number) {
    if (isCorrectAnim.value || isLoading.value) return;

    curriculumStore.setOmrValueTypeOdering(String(answer), currentAnswerRowIndex.value);

    // 빈칸 다 채웠으면 결과 전송
    if (curriculumStore.getOmr[currentAnswerRowIndex.value].filter((_answer) => _answer === '').length === 0) {
      isLoading.value = true;

      await submit(curriculumStore.getOmrValueTypeChoice(currentAnswerRowIndex.value) as string[]);

      isLoading.value = false;
    }
  }

  function nextSplitSentence() {
    // 문제 풀 차례인데 문제를 풀지 않은 경우 다음으로 못 넘어감.
    if (isLastSentence.value || isCorrectAnim.value || (isSplitQuestionType.value && !isCompltedQuestion.value)) {
      return;
    }

    isCompltedQuestion.value = false;
    currentSplitIndex.value++;
    nextTick(() => {
      scrollToCurrentSentence();
    });
  }

  function scrollToCurrentSentence() {
    const layerBodyElem = document.querySelector('.layer__body');
    const frameBodyElem = document.querySelector('.frame__body');

    const bodyElem = layerBodyElem || frameBodyElem;
    if (!isNil(bodyElem)) {
      const target = bodyElem.querySelector('.spl.current');
      if (isNil(target)) return;

      const containerRect = bodyElem.getBoundingClientRect();

      const targetRect = target.getBoundingClientRect();
      // 타겟 요소가 컨테이너의 아래에 위치하면 스크롤합니다.
      if (targetRect.bottom > containerRect.bottom) {
        bodyElem.scrollTo({
          top: bodyElem.scrollTop + (targetRect.bottom - containerRect.bottom) + 50,
          behavior: 'smooth',
        });
      }
    }
  }

  function setOrderingType() {
    isOrdering.value = true;
    currentSplitIndex.value = 0;
    isCompltedQuestion.value = false;
  }

  /**  스페이스바 이벤트 등록 */
  function spacebarEventHandler(event: KeyboardEvent) {
    const spacebarKeyCode = 32;
    if (event.keyCode === spacebarKeyCode) {
      event.preventDefault();
      nextSplitSentence();
    }
  }

  /** 스페이스바 이벤트 취소 */
  function cancelSpacebarEvent() {
    document.removeEventListener('keydown', spacebarEventHandler);
  }

  let isRequesting = false;
  async function submit(answer: string | string[], showCorrectAlways = false) {
    try {
      if (isRequesting) return;
      isRequesting = true;
      await curriculumStore.submitResult(
        {
          answers: answer,
          answer_row_index: currentAnswerRowIndex.value,
          answer_col_index: isOrdering.value ? null : currentAnswerColIndex.value,
          timer: curriculumStore.getTimer,
        },
        {
          onSuccess(result) {
            if (isOrdering.value) {
              if (result.is_correct) {
                completeQuestion(result.correct_answers as string[]);
              } else {
                curriculumStore.setOmrValueTypeOdering(result.correct_answers || [], currentAnswerRowIndex.value);
              }
            } else if (result.is_correct || showCorrectAlways) {
              completeQuestion(result.correct_answers as string);
            } else {
              wrongClickedChoices.value.push(answer as string);
            }
          },
        },
      );

      isRequesting = false;
    } catch (err) {
      console.log(err);
    }
  }

  function completeQuestion(correctAnswer: string | string[]) {
    if (isArray(correctAnswer)) {
      curriculumStore.setOmrValueTypeOdering(correctAnswer, currentAnswerRowIndex.value);
    } else {
      curriculumStore.setOmrValueTypeChoice(
        String(correctAnswer),
        currentAnswerRowIndex.value,
        currentAnswerColIndex.value,
      );
    }
    isCompltedQuestion.value = true;

    startCorrectAnim();
  }

  const isCorrectAnim = ref(false);
  const correctAnimDuration = 500;

  function startCorrectAnim() {
    isCorrectAnim.value = true;

    setTimeout(() => {
      isCorrectAnim.value = false;

      if (
        !isOrdering.value &&
        curriculumStore.getOmr[currentAnswerRowIndex.value].length - 1 > currentAnswerColIndex.value
      ) {
        currentAnswerColIndex.value++;
        wrongClickedChoices.value = [];
      } else {
        wrongClickedChoices.value = [];
        currentAnswerRowIndex.value++;
        currentAnswerColIndex.value = 0;
        let result = true;
        splitedSentences.value[currentSplitIndex.value].forEach((splitedTextToBrTag) => {
          splitedTextToBrTag.forEach((sentence) => {
            if (
              getIsBlankType(sentence) &&
              curriculumStore.getOmr[getAnswerRowIndex(sentence)].filter((data) => data).length <= 0
            ) {
              result = false;
            }
          });
        });
        if (result) {
          nextSplitSentence();
        }
      }
    }, correctAnimDuration);
  }

  function getIsBlankType(text: string) {
    return /\[(.*)\]/.test(text);
  }

  function getAnswerRowIndex(text: string) {
    return Number(text.match(/\d+/)) - 1;
  }

  onMounted(() => {
    document.addEventListener('keydown', spacebarEventHandler);
  });

  onBeforeUnmount(() => {
    cancelSpacebarEvent();
  });

  return {
    splitedSentences,
    currentSplitIndex,
    nextSplitSentence,
    isLastSentence,
    currentAnswerRowIndex,
    currentAnswerColIndex,
    isCompltedQuestion,
    getCorrectAnswer,
    isSplitQuestionType,
    isOrdering,
    isLoading,
    setOrderingType,
    submit,
    wrongClickedChoices,
    onClickChoice,
    onClickOrderingChoice,
    currentChoices,
    isShowChoices,
    getIsBlankType,
    getAnswerRowIndex,
  };
}
