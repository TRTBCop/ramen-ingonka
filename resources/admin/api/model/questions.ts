import { AppTag } from '@/admin/types';
import { Curriculum } from './curriculum';

export interface Question {
  id: number;
  inquiry: string; // 발문
  type: QuestionAnswerEnum; // 1: 입력형, 2: 선지형
  answers: QuestionAnswer[]; // 문제
  question?: string; // 문제풀이
  options?: string; // 보기
  curriculum?: Curriculum;
  curriculum_full_name?: string;
  curriculum_id?: number;
  explanation?: string; // 해설
  level?: number; // 문제 레벨
  tags: AppTag[];
  created_at: string;
  deleted_at?: string;
  published_at?: string;
  test_questions: unknown[]; // 뭔지 모르겠다.
  trainings: unknown[]; // 뭔지 모르겠다.
  pivot: {
    extra: {
      type: string;
      is_vertical: number;
    };
  };
}

export interface QuestionAnswer {
  type: QuestionAnswerEnum; // 1: 입력형, 2: 선지형
  action: number;
  choices: string[]; // 선지 타입2인경우 필수
  choice_symbol: boolean;
  answer: (string | string[])[];
}

export enum QuestionAnswerEnum {
  Input = 1,
  Choice,
  OrderMatching,
}
