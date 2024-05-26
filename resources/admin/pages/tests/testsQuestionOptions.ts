import { QuestionDrawerOptions } from '@/admin/components/admin/QuestionDrawer/types';

export const testsQuestionOptions: QuestionDrawerOptions = {
  isDefault: true, // 기본정보
  isInquiry: true, // 발문
  isMultipleAnswer: true, // 답안 여러게
  isQuestion: true, // 문제풀이
  isOptions: false, // 보기
  isAction: true, // 행동영역
  isExplanation: false, // 해설
  isOrderMatching: false, // 순서맞추기
};
