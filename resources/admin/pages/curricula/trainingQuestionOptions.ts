import { QuestionDrawerOptions } from '@/admin/components/admin/QuestionDrawer/types';

export const training1OperationsOptions: QuestionDrawerOptions = {
  isDefault: false, // 기본정보
  isInquiry: true, // 발문
  isMultipleAnswer: false, // 답안 여러게
  isQuestion: false, // 문제풀이
  isOptions: true, // 보기
  isAction: false, // 행동영역
  isExplanation: false, // 해설
  isOrderMatching: false, // 순서맞추기
};

export const training1TextReadingOptions: QuestionDrawerOptions = {
  isDefault: false, // 기본정보
  isInquiry: true, // 발문
  isMultipleAnswer: false, // 답안 여러게
  isQuestion: false, // 문제풀이
  isOptions: false, // 보기
  isAction: false, // 행동영역
  isExplanation: false, // 해설
  isOrderMatching: false, // 순서맞추기
};

export const training1TextReinforcementOptions: QuestionDrawerOptions = {
  isDefault: false, // 기본정보
  isInquiry: true, // 발문
  isMultipleAnswer: true, // 답안 여러게
  isQuestion: true, // 문제풀이
  isOptions: false, // 보기
  isAction: false, // 행동영역
  isExplanation: false, // 해설
  isOrderMatching: false, // 순서맞추기
};

export const training1TextSummarizationOptions: QuestionDrawerOptions = {
  isDefault: false, // 기본정보
  isInquiry: false, // 발문
  isMultipleAnswer: true, // 답안 여러게
  isQuestion: true, // 문제풀이
  isOptions: false, // 보기
  isAction: false, // 행동영역
  isExplanation: false, // 해설
  isOrderMatching: false, // 순서맞추기
};

export const training2Options: QuestionDrawerOptions = {
  isDefault: false, // 기본정보
  isInquiry: true, // 발문
  isMultipleAnswer: false, // 답안 여러게
  isQuestion: false, // 문제풀이
  isOptions: true, // 보기
  isAction: false, // 행동영역
  isExplanation: true, // 해설
  isOrderMatching: false, // 순서맞추기
};

export const training3Options: QuestionDrawerOptions = {
  isDefault: true, // 기본정보
  isInquiry: true, // 발문
  isMultipleAnswer: true, // 답안 여러게
  isQuestion: true, // 문제풀이
  isOptions: true, // 보기
  isAction: true, // 행동영역
  isExplanation: true, // 해설
  isOrderMatching: false, // 순서맞추기
};

export const training3DefaultQuestionOptions: QuestionDrawerOptions = {
  isDefault: true, // 기본정보
  isInquiry: true, // 발문
  isMultipleAnswer: true, // 답안 여러게
  isQuestion: true, // 문제풀이
  isOptions: false, // 보기
  isAction: true, // 행동영역
  isExplanation: false, // 해설
  isOrderMatching: true, // 순서맞추기
};
