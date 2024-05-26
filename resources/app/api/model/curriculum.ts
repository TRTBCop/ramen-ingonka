import { Training } from './training';
import { TrainingResult } from './trainingResult';

export interface CurriculumNode {
  id: number;
  label: string;
  children?: CurriculumNode[];
}

export interface Curriculum {
  id: number;
  element: number;
  name: string;
  type: string;
  _lft: number;
  _rgt: number;
  parent_id: number;
  created_at: string;
  updated_at: string;
  txt_element: string;
  children?: Curriculum[];
  trainings: Training[];
  training_results: TrainingResult[];
  ancestors?: Curriculum[];
}
