export interface Training<T = unknown> {
  id: number;
  contents: T;
  created_at?: string;
  curriculum_id?: number;
  stage?: number;
  updated_at?: string;
  published_at: string | null;
}

export interface ConceptBasicOperations {
  questions: { id: number }[];
}
export interface Training3Contents {
  sheets: TrainingContentSheet[][];
}

export interface TextReading {
  type: TrainingWordType;
  text: string;
  image?: {
    src: string;
    last: boolean;
  };
  question?: {
    id: number;
  };
}

export enum TrainingWordType {
  default = 0,
  image,
  question,
}

export interface TrainingQuestion {
  id: number;
  type: TrainingQuestionType;
}

export enum TrainingQuestionType {
  row = 0, // 가로형
  column, // 세로형
}

export interface TrainingContentSheet {
  text: string;
  type: 0 | 1 | 2;
  question?: {
    answer: number[];
    choices: string[];
  };
}

export enum TrainingSheetType {
  default = 0, // 지문
  selectQuestion, // 정답 고르기
  indexQuestion, // 순서 맞추기
}
