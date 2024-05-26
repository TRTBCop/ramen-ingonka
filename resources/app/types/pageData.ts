import { User } from './';
import { Curriculum } from '@/app/api/model/curriculum';
import { TrainingResult } from '@/app/api/model/trainingResult';
import { ConceptTextType, Training, TrainingConceptTextReading } from '@/app/api/model/training';
import { Question } from '@/app/api/model/question';
import { Test } from '@/app/api/model/tests';
import { StepResult } from '@/app/api/model/stepResult';

export interface PageConfig {
  dbcode: {
    curricula: {
      element: {
        [key in number]: string;
      };
    };
    students: {
      grade: {
        [grade in number]: string;
      };
      status: {
        [status in number]: string;
      };
    };
    payments: {
      status: {
        [status in number]: string;
      };
      method: {
        [method in string]: string;
      };
    };
    trainings: {
      stage: {
        [stage in number]: string;
      };
    };
  };
  training: {
    grade_group: {
      lower: number[];
      upper: number[];
    };
  };
  payment: {
    products: {
      [code in string]: {
        amount: { origin: 60000; sale: 60000 };
        day: number;
        month: number;
        name: string;
      };
    };
  };
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  auth: {
    user: User;
    csrf: string;
    is_guest: boolean;
    is_student: boolean;
    is_academy: boolean;
  };
  page: {
    title: string;
    active?: string;
  };
  flash: {
    message: string[];
  };
  route_name: string;
  config: PageConfig;
};

export interface TrainingContents {
  questions: {
    id: number;
  }[];
}

export interface TrainingPageProps<
  T =
    | {
        readings: TrainingConceptTextReading[];
        summarizations: TrainingContents;
      }
    | TrainingContents,
> extends PageProps {
  contents: T;
  questions: Question[];
  is_preview: boolean;
  training: Training;
  training_concept_text_id?: number;
  training_result?: TrainingResult;
  training_concept_text_ids?: number[];
  training_concept_text_type?: ConceptTextType;
  step_result?: StepResult;
  timer?: number;
  step: string | number;
}

export type Chapter = {
  [grade in string]: {
    [term in string]: {
      curriculums: Curriculum[];
      progress: number;
    };
  };
};

export type Element = {
  [element in number]: {
    chapter: Chapter;
    progress: number;
  };
};

export interface MainFreePageProps extends PageProps {
  curricula_map: {
    chapter: Chapter;
    element: Element;
  };
}

export type StageResult = {
  [stage in number]: TrainingResult;
};

export interface WeeklyAchievement {
  curriculum_count: number; // 소단원 수
  score: number; // 점수
  correct_percent: number; // 정답률
  training_count: number; // 훈련수
  correct_answers: number; // 정답수
  total_answers: number; // 문제수
  total_timer_minutes: number; // 훈련시간
}

export interface HistoryPageProps extends PageProps {
  start_date: string;
  end_date: string;
  // 4주간 학습 기록
  achievement_over_4weeks: WeeklyAchievement[];
  // 학습내용별 학습기록
  training_results_by_curriculum: {
    [curriculumId in number]: StageResult[];
  };
  // 학습일시별 학습기록
  training_results_by_date: {
    [date in string]: TrainingResult[];
  };
}

export interface TestPageProps extends PageProps {
  test: Test;
  questions: Question[];
  question: Question;
  curriculum_id_to_name: {
    [key: number]: {
      name: string;
      txt_element: string;
    };
  };
  timer: number;
  is_extend: boolean;
  is_preview: boolean;
}
