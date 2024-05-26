<template>
  <p>
    <template v-for="(splitedTextToBrTag, i) in splitedSentences" :key="i">
      <template v-for="(sentence, j) in splitedTextToBrTag" :key="j">
        <template v-if="sentence.length !== 0">
          <span
            class="spl"
            :class="{
              show: currentSplitIndex >= i,
              current: currentSplitIndex == i,
            }"
          >
            <template v-for="(data, k) in sentence" :key="k">
              <span v-if="getIsBlankType(data)" :class="getBlankLengthClass(data)" @click="onClickSplit(data)">
                <em
                  v-if="getIsOderingType(getAnswerRowIndex(data))"
                  class="order__blank"
                  :class="{
                    order__blank__on: curriculumStore.getOmr[getAnswerRowIndex(data)][getAnswerColIndex(i, j, k)],
                    order__blank__focus: getIsFocus(data, i, j, k),
                    order__blank__correct: getIsCorrect(data, i, j, k),
                    order__blank__wrong: getIsWrong(data, i, j, k),
                    order__blank__wrong__check: getIsCheckedWrong(data, i, j, k),
                  }"
                  @click="
                    isReview
                      ? onClickBlank(data, i, j, k)
                      : resetOrderingBlank(getAnswerRowIndex(data), getAnswerColIndex(i, j, k))
                  "
                >
                  <bdi v-html="getCorrectAnswer(getAnswerRowIndex(data), getAnswerColIndex(i, j, k))?.choice"></bdi>
                </em>
                <em
                  v-else
                  class="blank blank__on"
                  :class="{
                    blank__focus:
                      currentAnswerRowIndex === getAnswerRowIndex(data) &&
                      currentAnswerColIndex == getAnswerColIndex(i, j, k),
                    blank__correct: getIsCorrect(data, i, j, k),
                    blank__wrong: getIsWrong(data, i, j, k),
                    blank__wrong__check: getIsCheckedWrong(data, i, j, k),
                  }"
                  @click="onClickBlank(data, i, j, k)"
                >
                  <bdi v-html="getCorrectAnswer(getAnswerRowIndex(data), getAnswerColIndex(i, j, k))?.choice"></bdi>
                </em>
              </span>
              <span v-else v-html="data"></span>
            </template>
          </span>
        </template>
        <br v-if="splitedTextToBrTag.length > 1 && splitedTextToBrTag.length - 1 > j" />
      </template>
    </template>
  </p>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { useSplitTraining } from '@/app/hooks/useSplitTraining';
  import { isNil } from 'lodash';
  import { QuestionAnswerEnum } from '@/app/api/model/question';

  const props = defineProps({
    splitedSentences: {
      type: Array as PropType<string[][][]>,
      default: null,
    },
    currentSplitIndex: {
      type: Number as PropType<number>,
      default: 0,
    },
    currentAnswerRowIndex: {
      type: Number as PropType<number>,
      default: 0,
    },
    currentAnswerColIndex: {
      type: Number as PropType<number>,
      default: 0,
    },
    isReview: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  const curriculumStore = useCurriculumStoreWithOut();

  const { getCorrectAnswer, getIsBlankType, getAnswerRowIndex } = useSplitTraining();

  // 정답 확인 했으면 여기에 push 됨
  const reviewCheckList: { row: number; col: number }[] = [];

  function getIsOderingType(answerRowIndex: number) {
    const currentQuestion = curriculumStore.getCurrentQuestion;

    if (isNil(currentQuestion)) return false;

    return currentQuestion.answers[answerRowIndex]?.type === QuestionAnswerEnum.OrderMatching;
  }

  function resetOrderingBlank(rowIndex: number, colIndex: number) {
    // 현재 풀고 있는 문제가 아니면 클릭 안되게
    if (props.currentAnswerRowIndex !== rowIndex) return;

    curriculumStore.setOmrValueTypeChoice('', rowIndex, colIndex);
  }
  function getAnswerColIndex(i: number, j: number, k: number) {
    const matched = props.splitedSentences[i][j][k].match(/\[(.*?)\]/);

    if (!matched) return 0;

    const rowIndex = matched[1];

    const regex = new RegExp(`\\[${rowIndex}\\]`, 'g');

    let colIndex = 0;

    props.splitedSentences[i]
      .filter((_, index) => index < j)
      .forEach((sentence) => (colIndex = colIndex + sentence.filter((data) => regex.test(data)).length));

    return (
      colIndex + props.splitedSentences[i][j].filter((_, index) => k > index).filter((data) => regex.test(data)).length
    );
  }

  function getIsFocus(text: string, i: number, j: number, k: number) {
    if (props.currentSplitIndex !== i) return false;

    const foundEmptyTextIndex = curriculumStore.getOmr[getAnswerRowIndex(text)].findIndex((answer) => answer === '');

    const targetAnswerColIndex = getAnswerColIndex(i, j, k);

    return foundEmptyTextIndex === targetAnswerColIndex;
  }

  function getIsCorrect(text: string, i: number, j: number, k: number) {
    return (
      curriculumStore.getOmr[getAnswerRowIndex(text)][getAnswerColIndex(i, j, k)] ===
        curriculumStore.getOriginalOmrValueTypeChoice(getAnswerRowIndex(text))[getAnswerColIndex(i, j, k)] &&
      props.isReview
    );
  }

  function getIsWrong(text: string, i: number, j: number, k: number) {
    return (
      curriculumStore.getOmr[getAnswerRowIndex(text)][getAnswerColIndex(i, j, k)] !==
        curriculumStore.getOriginalOmrValueTypeChoice(getAnswerRowIndex(text))[getAnswerColIndex(i, j, k)] &&
      props.isReview
    );
  }

  function getIsCheckedWrong(text: string, i: number, j: number, k: number) {
    if (!props.isReview) return false;
    const answerRowIndex = getAnswerRowIndex(text);
    const answerColIndex = getAnswerColIndex(i, j, k);

    const foundIndex = reviewCheckList.findIndex((data) => data.row === answerRowIndex && data.col === answerColIndex);
    return foundIndex !== -1;
  }

  function onClickBlank(text: string, i: number, j: number, k: number) {
    if (!props.isReview) return;

    if (getIsCorrect(text, i, j, k) || getIsCheckedWrong(text, i, j, k)) return;

    const answerRowIndex = getAnswerRowIndex(text);
    const answerColIndex = getAnswerColIndex(i, j, k);

    if (getIsWrong(text, i, j, k)) {
      const value = curriculumStore.getOriginalOmrValueTypeChoice(answerRowIndex)[answerColIndex];
      curriculumStore.setOmrValueTypeChoice(String(value), answerRowIndex, answerColIndex);
      reviewCheckList.push({
        row: answerRowIndex,
        col: answerColIndex,
      });
    }
  }

  function getBlankLengthClass(text: string) {
    return `blanks__type${(text.match(/-/g) || []).length + 1}`;
  }

  function onClickSplit(text: string) {
    if (!props.isReview) return;
    const answerRowIndex = getAnswerRowIndex(text);
    curriculumStore.getOmr[answerRowIndex].forEach((answer, answerColIndex) => {
      const correctAnswer = curriculumStore.getOriginalOmrValueTypeChoice(answerRowIndex)[answerColIndex];
      const isCorrect = correctAnswer === answer;
      if (!isCorrect) {
        curriculumStore.setOmrValueTypeChoice(String(correctAnswer), answerRowIndex, answerColIndex);
        reviewCheckList.push({
          row: answerRowIndex,
          col: answerColIndex,
        });
      }
    });
  }
</script>
