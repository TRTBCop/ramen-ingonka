import { Question } from './question';

export interface Test {
  id: number;
  title: string;
  published_at: string | null;
  created_at: string;
  updated_at: string;
  contents: TestContents;
  result: any | null;
}

export interface TestContents {
  questions: TestQuestion[];
}

export interface TestQuestion {
  id: number;
  is_extend: number | boolean;
  question: Question[];
}

export interface TestQuestionLevel {
  1: '하';
  2: '중하';
  3: '중';
  4: '증상';
  5: '상';
}
