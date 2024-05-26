import { QuestionResult } from '@/app/api/model/questionResult';
import { TrainingConceptTextResult } from '@/app/api/model/trainingConceptTextResult';
import { BaseResult } from '@/app/api/model/baseModel';

export interface StepResult extends BaseResult {
  id: number;
  student_id: number;
  key: string | number;
  model_type: string;
  model_id: number;
  completed_at: null | string;
  created_at: string;
  updated_at: null | string;
  questions: QuestionResult[];
  training_concept_texts: TrainingConceptTextResult[];
}
