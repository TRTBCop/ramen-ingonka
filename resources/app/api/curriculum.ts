import ApiService from '@/app/core/services/ApiService';
import { Result } from '@/app/api/model/baseModel';
import { Question } from '@/app/api/model/question';
import { StepResult } from '@/app/api/model/stepResult';
import { Training } from '@/admin/api/model/training';

export interface TrainingResultParams {
  step_result_id?: number;
  question_id: number;
  answer_row_index?: number | null;
  answer_col_index?: number | null;
  answers?: string | any[];
  timer: number;
}

export interface TrainingResultResponse {
  correct_answers?: string | string[];
  question?: Question;
  is_correct: boolean;
  step_result: StepResult;
}

export function storeTrainingResult(training: Training, params: TrainingResultParams) {
  return ApiService.post<Result<TrainingResultResponse>>(route('app.trainings.submit', training), params);
}
