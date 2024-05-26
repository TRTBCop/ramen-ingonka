import { Training } from '@/app/api/model/training';
import { Curriculum } from '@/app/api/model/curriculum';
import { StepResult } from '@/app/api/model/stepResult';
import { BaseResult } from '@/app/api/model/baseModel';

export interface TrainingResult extends BaseResult {
  id: number;
  curriculum_id: number;
  student_id: number;
  training_id: number;
  timer: number;
  round: number;
  score: number;
  completed_at: null | string;
  created_at: string;
  updated_at: null | string;
  training: Training;
  curriculum: Curriculum;
  steps: StepResult[];
}
