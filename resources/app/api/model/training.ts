import { Curriculum } from './curriculum';
import { TrainingResult } from './trainingResult';

export interface Training<T = unknown> {
  id: number;
  contents: T;
  created_at: string;
  curriculum_id: number;
  curriculum: Curriculum;
  stage: TrainingStage;
  updated_at: string;
  results: TrainingResult[];
  published_at: string | null;
}
export enum TrainingStage {
  STAGE_1 = 1,
  STAGE_2,
  STAGE_3,
}

export interface TrainingConceptText {
  id: number;
  training_id: number;
  readings: TrainingConceptTextReading[];
  summarizations: {
    questions: {
      id: number;
    }[];
  };
  reinforcements: {
    questions: {
      id: number;
    }[];
  };
  created_at: string;
  updated_at: string;
}

export interface TrainingConceptTextReading {
  text: string;
  type: TrainingConceptTextReadingType;
  image?: {
    src: string;
    last: boolean;
  };
  question?: {
    id: number;
  };
}

export enum TrainingConceptTextReadingType {
  Default = 0,
  Image,
  Question,
}

export enum ConceptTextType {
  Readings = 'readings',
  Summarizations = 'summarizations',
  Reinforcements = 'reinforcements',
}
