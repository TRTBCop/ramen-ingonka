import { defineStore } from 'pinia';
import { store } from '@/app/stores';
import { isNil } from 'lodash';
import { TestPageProps } from '@/app/types/pageData';
import { router, usePage } from '@inertiajs/vue3';
import { useCurriculumStoreWithOut } from './curriculum';

const pageData = usePage<TestPageProps>();

interface TestState {
  testingTime: number | null;
  timeLimitSecond: number;
  isStartExtend: boolean;
  isMetaTooltip: boolean;
}

let testingTimeIncrementInterval: number | null = null;

export const useTestStore = defineStore({
  id: 'app-test',
  state: (): TestState => ({
    testingTime: 0,
    timeLimitSecond: 2400, // 2400초 === 40분
    isStartExtend: false,
    isMetaTooltip: false,
  }),
  getters: {
    getIsOverTime(): boolean {
      if (isNil(this.testingTime)) return false;

      return this.testingTime > this.timeLimitSecond;
    },
    getTestingTime(): number {
      if (isNil(this.testingTime)) return 0;

      return this.testingTime;
    },
    getTimeLimitSecond(): number {
      return this.timeLimitSecond;
    },
    getIsStartExtend(): boolean {
      return this.isStartExtend;
    },
    getIsMetaTooltip(): boolean {
      return this.isMetaTooltip;
    },
  },
  actions: {
    initTestingTimeIncrement(initTime: number) {
      this.testingTime = initTime;
      testingTimeIncrementInterval = Number(setInterval(this.incrementTestingTime, 1000));
    },
    stopTestingTimeIncrement() {
      if (testingTimeIncrementInterval) {
        clearInterval(testingTimeIncrementInterval);
      }
    },
    incrementTestingTime() {
      if (isNil(this.testingTime)) return;

      const maxTimeSecond = 86400; // 24시간이후에는 초를 멈춤
      this.testingTime = Math.min(maxTimeSecond, this.testingTime + 1);
    },
    setIsStartExtend(state: boolean) {
      this.isStartExtend = state;
    },
    setIsMetaTooltip(state: boolean) {
      this.isMetaTooltip = state;
    },
    /**
     * 추가문제는 meta_cognition이 없음
     * @param metaCognition 0: 확실해요, 1: 정답일 것 같아요. 2: 모르겠어요
     */
    submitAnswer(meta_cognition?: number) {
      const curriculumStore = useCurriculumStoreWithOut();

      router.post(route('app.tests.store', pageData.props.test.id), {
        question_id: pageData.props.question.id,
        answers: curriculumStore.getOmr,
        timer: Math.round(this.getTestingTime),
        meta_cognition,
      });
    },
  },
});

export function useTestStoreWithOut() {
  return useTestStore(store);
}
