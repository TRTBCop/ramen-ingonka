import { TrainingStage } from '@/app/api/model/training';

export function getQuestionStateClass(score: number) {
  if (score === 100) {
    return 'correct';
  } else if (score >= 70) {
    return 'triangle';
  } else {
    return 'wrong';
  }
}

const trainingDoneComment = {
  1: {
    low: '개념 훈련을 다시 학습하는 것을 권장합니다. 개념을 꼼꼼히 읽은 뒤 핵심 개념을 다시 확인하고 기초 연산을 풀어보세요.',
    middle:
      '개념을 전반적으로 알고 있지만 깊은 이해가 부족합니다. 핵심 개념을 스스로 정리해 본 뒤 실수가 없도록 집중하여 기초 연산을 다시 풀어보세요.',
    high: '개념을 잘 이해하고 있고, 기초적인 연산 능력도 우수합니다.',
  },
  2: {
    low: '개념 훈련을 다시 학습한 후 유형 훈련을 재도전 하는 것을 권장합니다. 개념을 정확하게 이해해야 다양한 유형의 문제를 해결할 수 있습니다.',
    middle:
      '문제해결을 위해 계획한 전략이 맞는지 한번 더 생각해보세요. 문제를 해결하는 과정에서 연산 실수는 없는지 확인해보세요.',
    high: '개념의 이해도도 높고 학습한 개념을 문제에 적용하는 능력도 우수합니다. ',
  },
  3: {
    low: '문제를 꼼꼼히 읽고 바르게 이해한 뒤 문제를 풀어보는 습관을 들여보세요.',
    middle:
      '문제를 해결할 때 집중력이 흐트러지지 않도록 주의하세요. 무엇을 구하는지, 선택한 해결전략이 무엇인지 문제해결과정에서 흔들리지 않도록 주의하세요.',
    high: '개념을 잘 이해하고 이를 이용하여 문제를 해결하는 능력도 탁월합니다. 해결한 문제를 다른 방법으로 해결할 수 있는지도 고민하는 기회를 가져보세요.',
  },
};

export function getTrainingDontComment(stage: TrainingStage, score: number) {
  if (score >= 95) {
    return trainingDoneComment[stage].high;
  } else if (score >= 65) {
    return trainingDoneComment[stage].middle;
  } else {
    return trainingDoneComment[stage].low;
  }
}

export function getStageIntroComment(stage: TrainingStage) {
  switch (stage) {
    case 1:
      return `개념 훈련은 소단원의 개념을 이해하기 위한 첫 번째 훈련입니다. 지문을 끊어 읽으며 개념을 익힌 뒤 핵심 개념을 정리하고, 이해한 내용을 개념 다지기 문제로 확인하고, 다시 한번 지문과 핵심 개념을 확인해 봅니다. 마지막으로 학습한 개념을 바탕으로 기초 연산 문제를 풀이하면 개념 훈련이 종료됩니다.`;
    case 2:
      return `유형 훈련은 다양한 유형의 문제를 풀며 학습한 개념을 문제에 적용해보는 훈련입니다. 각각 10문제씩 총 4회차의 문제를 모두 해결하면 유형 훈련이 종료됩니다. 모든 문제를 [1차 유형 문제]와 [1차 유사 문제], [2차 유형 문제]와 [2차 유사 문제]는 비슷한 유형의 문제가 출제되고 모든 문제를 풀이한 시간이 60분이 초과되면 최종 훈련 결과에 10점이 감점됩니다.`;
    case 3:
      return `서술형 훈련은 긴 문장으로 된 문제를 끊어 읽고 해결하는 법을 익히는 마지막 훈련입니다. 빈칸을 채우고 문장을 바르게 배열하는 등 3단계의 총 8문제를 풀면 서술형 훈련이 종료됩니다. 모든 문제를 풀이한 시간이 60분이 초과되면 최종 훈련 결과에 10점이 감점됩니다.`;
  }
}
