import { Training } from '@/admin/api/model/training';
import { StepResult } from '@/app/api/model/stepResult';
import { TrainingResult } from '@/app/api/model/trainingResult';
import { router } from '@inertiajs/vue3';

/**
 * 브랜드
 */
export function goBrandPage() {
  window.location.href = '/';
}

/**
 * 로그인: 기본
 */
export function goLoginPage() {
  router.get(route('app.auth.create'));
}

/**
 * 로그인: 아이디 로그인
 */
export function goOtherLoginPage() {
  router.get(route('app.auth.other.create'));
}

/**
 * 회원가입
 */
export function goRegisterPage() {
  router.get(route('app.register.create'));
}

/**
 * 아이디 찾기
 */
export function goFindAccountPage() {
  router.get(route('app.register.find-account'));
}

/**
 * 비밀번호 찾기
 */
export function goFindPasswordPage() {
  router.get(route('app.register.find-password'));
}

/**
 * 메인: 기본모드
 */
export function goMainPage() {
  router.get(route('app.main'));
}

/**
 * 메인: 자유모드
 */
export function goFreeMainPage() {
  router.get(route('app.main.free'));
}

/**
 * 공지사항: 목록
 */
export function goBoardNoticesPage(page = 1) {
  router.get(
    route('app.board-notices.index', {
      page,
    }),
  );
}

/**
 * 공지사항: 상세
 */
export function goBoardNoticeDetailPage(boardId: number) {
  router.get(route('app.board-notices.show', boardId));
}

/**
 * 훈련: 메인
 */
export function goTrainingMainPage(training: Training) {
  router.get(route('app.trainings.show', training));
}

/**
 * 훈련 진행
 * @param training 훈련
 * @param step 단계
 */
export function goTrainingByStep(training: Training, step: string | number) {
  switch (training.stage) {
    case 1:
      switch (step) {
        case 'texts':
          router.get(
            route('app.trainings.stage1.texts.show', {
              training: training.id,
            }),
          );
          break;
        case 'operations':
          router.get(
            route('app.trainings.stage1.operations.show', {
              training: training.id,
            }),
          );
          break;
      }
      break;
    case 2:
      router.get(
        route('app.trainings.stage2.show', {
          training: training.id,
          step,
        }),
      );
      break;
    case 3:
      router.get(
        route('app.trainings.stage3.show', {
          training: training.id,
          step,
        }),
      );
      break;
  }
}

/**
 * 훈련: 단계 완료
 */
export function goStepResultPage(training: Training, trainingResult: TrainingResult, stepResult: StepResult) {
  router.get(
    route('app.trainings.results.steps.show', {
      training,
      trainingResult,
      stepResult,
    }),
  );
}

/**
 * 훈련: 완료 요약
 */
export function goTrainingResultSummaryPage(training: Training, trainingResult: TrainingResult) {
  router.get(
    route('app.trainings.results.summary.show', {
      training,
      trainingResult,
    }),
  );
}

/**
 * 훈련: 완료
 */
export function goTrainingResultPage(training: Training, trainingResult: TrainingResult) {
  router.get(
    route('app.trainings.results.show', {
      training,
      trainingResult,
    }),
  );
}

/**
 * 이용권: 결제
 */
export function goPaymentPage() {
  router.get(route('app.payments.create'));
}

/**
 * 마이페이지: 내 정보
 */
export function goMyPage() {
  router.get(route('app.my.profile.show'));
}

/**
 * 마이페이지: 이용권 정보 - 목록
 */
export function goMyPaymentPage() {
  router.get(route('app.my.payments.index'));
}

/**
 * 마이페이지: 이용권 정보 - 상세
 */
export function goPaymentDetailPage(paymentId: number) {
  router.get(route('app.my.payments.show', paymentId));
}

/**
 * 마이페이지: 회원 탈퇴
 */
export function goWithdrawPage() {
  router.get(route('app.my.withdraw.show'));
}

/**
 * 오답 노트: 목록
 */
export function goIncorrectNotePage(page = 1, filters?: { stage?: number; parent_curriculum_id?: number }) {
  router.get(
    route('app.incorrect-note.index', {
      page,
      filters,
    } as any),
  );
}

/**
 * 진단평가 메인
 */
export function goTestMainPage() {
  router.get(route('app.tests.index'));
}

/**
 * 진단평가 학습
 */
export function goTestPage(testId: number) {
  router.get(route('app.tests.show', testId));
}

/**
 * 학습 기록
 */
export function goTrainingHistoryPage(date?: string) {
  router.get(route('app.training-history.index', date));
}

/**
 * 오답 해설
 */
export function goIncorrectExplanationPage(training: Training, trainingResult: TrainingResult, stepResult: StepResult) {
  router.get(
    route('app.trainings.results.steps.explanation.show', {
      training,
      trainingResult,
      stepResult,
    }),
  );
}

/**
 * 준비중
 */
export function goCominSoonPage() {
  router.get(route('app.coming-soon.show'));
}
