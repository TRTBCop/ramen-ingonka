import { StepResult } from '@/app/api/model/stepResult';
import { TrainingConceptText } from '@/app/api/model/training';
import { BaseResult } from '@/app/api/model/baseModel';

export interface TrainingConceptTextResult extends BaseResult {
  id: number;
  student_id: number;
  step_result_id: number;
  step_result: StepResult;
  training_concept_text_id: number;
  training_concept_text: TrainingConceptText;
  is_reading_completed: boolean;
  summarizations: StepResult;
  reinforcements: StepResult;
  completed_at: null | string;
  created_at: string;
  updated_at: null | string;
}
