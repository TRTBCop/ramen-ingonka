import { Question, QuestionAnswerEnum, QuestionColAnswer } from '@/app/api/model/question';
import { BaseResult } from '@/app/api/model/baseModel';

export interface QuestionResult extends BaseResult {
  id: number;
  question_id: number;
  question: Question;
  student_id: number;
  model_type: string;
  model_id: number;
  answers: {
    type: QuestionAnswerEnum;
    userAnswer: QuestionColAnswer;
    correctAnswer: QuestionColAnswer;
  }[];
  completed_at: null | string;
  created_at: string;
  updated_at: null | string;
}
