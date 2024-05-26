import { StudentStatus } from './student';
import { TestQuestionLevel } from './tests';
import { PaymentMethodModel, PaymentStatusModel } from './payment';

export interface Dbcode {
  academies: {
    unpaid_status: string[];
    service_status: {
      [key: string]: string;
    };
    status: {
      [key: string]: string;
    };
  };
  students: {
    grade: { [grade: number]: string };
    status: StudentStatus;
  };
  curricula: {
    element: {
      [key: number]: string;
    };
  };
  questions: {
    type: string[];
  };
  payments: {
    status: PaymentStatusModel;
    method: PaymentMethodModel;
  };
  test: {
    questions: {
      answers: {
        action: {
          1: '문해';
          2: '계산';
          3: '추론';
          4: '문제해결';
        };
        type: {
          1: '입력형';
          2: '선지형';
        };
      };
      level: TestQuestionLevel;
    };
  };
  trainings: {
    stage: {
      1: '개념';
      2: '유형';
      3: '서술형';
    };
  };
  training_results: {
    round: {
      0: '1R';
      1: '2R';
      2: '3R';
    };
  };
}
