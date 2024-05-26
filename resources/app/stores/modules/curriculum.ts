import { StyleValue } from 'vue';
import { defineStore } from 'pinia';
import { store } from '@/app/stores';
import { cloneDeep, get, isArray, isEqual, isNil, set } from 'lodash';
import { Question, QuestionAnswerEnum, QuestionColAnswer } from '@/app/api/model/question';
import { TrainingResultParams, TrainingResultResponse, storeTrainingResult } from '@/app/api/curriculum';
import { TrainingPageProps } from '@/app/types/pageData';
import { usePage } from '@inertiajs/vue3';
import { removeByDoubleSlash, splitByDoubleSlash } from '@/app/core/helpers/trainingHelper';
import { useSystemStoreWithOut } from '@/app/stores/modules/system';
import { StepResult } from '@/app/api/model/stepResult';
import { params } from '@/app/pages/payments/data';

const pageData = usePage<TrainingPageProps>();

const systemStore = useSystemStoreWithOut();

export type OmrLocation = 'default' | 'sidebar';

/** 결과 중복 요청 방지 */
let isRequesting = false;

export interface OmrPosition {
  row: number;
  col: number | null;
  blank: number | null;
}
export interface OmrFocusInfo {
  location: OmrLocation;
  position: OmrPosition;
}

export type AnswerIndexCounting = {
  [answerRow in number]: { blankLength: number }[];
};

interface CurriculumState {
  omr: QuestionColAnswer[];
  omrFocusInfo: OmrFocusInfo | null;
  questions: Question[];
  currentQuestion: Question | null;
  originalQuestions: Question[];
  isFinishedQuestion: boolean;
  stepResult: StepResult | null;
  reviewCheckedQuestions: number[];
  reviewCheckedAnswers: OmrPosition[];
  timer: number;
  isStopTimer: boolean;
  timerInterval?: number | null;
}

export const useCurriculumStore = defineStore({
  id: 'app-curriculum',
  state: (): CurriculumState => ({
    questions: [],
    omr: [],
    omrFocusInfo: null,
    currentQuestion: null,
    originalQuestions: [],
    isFinishedQuestion: false,
    stepResult: null,
    reviewCheckedQuestions: [],
    reviewCheckedAnswers: [],
    timer: 0,
    isStopTimer: true,
    timerInterval: null,
  }),
  getters: {
    getQuestions(): Question[] {
      return this.questions;
    },
    getOmrFocusInfo(): OmrFocusInfo | null {
      return this.omrFocusInfo;
    },
    getCurrentQuestion(): Question | null {
      return this.currentQuestion;
    },
    getOriginalQuestions(): Question[] {
      return this.originalQuestions;
    },
    getOmr(): QuestionColAnswer[] {
      return this.omr;
    },
    getIsFinishedQuestion(): boolean {
      return this.isFinishedQuestion;
    },
    getCurrentQuestionIndex(): number {
      const currentQuestion = this.getCurrentQuestion;

      if (isNil(currentQuestion)) return 0;

      return this.getQuestions.findIndex((question) => question.id === currentQuestion.id);
    },
    getIsLastQuestion(): boolean {
      return this.getQuestions.length - 1 <= this.getCurrentQuestionIndex;
    },
    getIsSingleQuestion(): boolean {
      const currentQuestion = this.getCurrentQuestion;

      // 진단평가인지 여부
      const isTest = /test/.test(String(route().current()));

      if (isNil(currentQuestion)) return false;

      return isTest ? currentQuestion.answers.length === 1 : !currentQuestion?.question;
    },
    /** 모든 정답을 입력했는지 여부 */
    getIsAllAnswersEntered(): boolean {
      if (this.getOmr.length == 0) return false;

      let result = true;
      this.getOmr.forEach((colAnswer) => {
        colAnswer.forEach((rowAnswer) => {
          if (isArray(rowAnswer)) {
            rowAnswer.forEach((blankAnswer) => {
              if (blankAnswer === '') {
                result = false;
              }
            });
          } else {
            if (rowAnswer === '') {
              result = false;
            }
          }
        });
      });
      return result;
    },
    getSplitedQuestionSentences(): {
      default: string[][][];
      ordering: string[][][];
    } {
      const currentQuestion = this.getCurrentQuestion;

      const result: {
        default: string[][][];
        ordering: string[][][];
      } = {
        default: [],
        ordering: [],
      };

      if (isNil(currentQuestion) || isNil(currentQuestion.question)) return result;

      function splitString(str: string) {
        return (
          splitByDoubleSlash(str)
            .map((data) => data.split(/<br[^>]*>/))
            .map((data) =>
              data.map((str) =>
                str
                  .replace(/\${(.*?)}/gi, '§$1§')
                  .split('§')
                  .filter((data) => data !== ''),
              ),
            ) || []
        );
      }

      const splitToHrTag = currentQuestion.question.split('<hr>');

      if (splitToHrTag[0]) {
        result.default = splitString(splitToHrTag[0]);
      }

      if (splitToHrTag[1]) {
        result.ordering = splitString(splitToHrTag[1]);
      }

      return result;
    },
    geStepResult(): StepResult | null {
      return this.stepResult;
    },
    getTimer(): number {
      return this.timer;
    },
    getIsStopTimer(): boolean {
      return this.isStopTimer;
    },
    getReviewCheckedQuestions(): number[] {
      return this.reviewCheckedQuestions;
    },
    getReviewCheckedAnswers(): OmrPosition[] {
      return this.reviewCheckedAnswers;
    },
  },
  actions: {
    setQuestions(questions: Question[]) {
      this.questions = questions;
    },
    addReviewCheckedQuestions(questionId: number) {
      this.reviewCheckedQuestions.push(questionId);
      this.setOmrValueCorrect(questionId);
    },
    addReviewCheckedAnswers(position: OmrPosition) {
      this.reviewCheckedAnswers.push(position);
    },
    resetReviewCheckedAnswers() {
      this.reviewCheckedAnswers = [];
    },
    initOmr() {
      if (isNil(this.getCurrentQuestion)) return;

      this.omr = cloneDeep(this.getCurrentQuestion.answers.map((answer) => answer.answer));
    },
    setOmrValueTypeChoice(value: string, row: number, col?: number) {
      if (!isNil(col)) {
        this.omr[row][col] = value;
        return;
      }

      const findIndex = this.omr[row].findIndex((answer) => answer === value);

      if (findIndex !== -1) {
        // 이미 선택한 정답일 경우 해당 인덱스 삭제 하고 빈 값 push
        this.omr[row].splice(findIndex, 1);
        this.omr[row].push('');
      } else {
        const maxCount = this.omr[row].length;

        if (maxCount === 1) {
          // 최대 정답 갯수가 1일 경우 값 교체
          this.omr[row] = [value];
        } else {
          // 비어있는 곳에 정답 삽입
          // 비어있는 곳이 없으면 아무 작업 하지 않음
          const emptyIndex = this.omr[row].findIndex((answer) => answer === '');

          if (emptyIndex === -1) return;
          this.omr[row][emptyIndex] = value;
        }
      }
    },
    setOmrValueTypeInput(value: string, omrFocusInfo: OmrFocusInfo | null) {
      if (isNil(omrFocusInfo)) return;

      const path = Object.values(omrFocusInfo.position).filter((data) => !isNil(data));

      set(this.omr, path, value);
    },
    setOmrValueTypeOdering(value: string | string[], row: number) {
      if (isArray(value)) {
        this.omr[row] = value;
      } else {
        const findIndex = this.omr[row].findIndex((_answer) => _answer === '');
        if (findIndex === -1) return;

        this.omr[row][findIndex] = value;
      }
    },
    setOmrValueCorrect(questionId: number) {
      this.omr = this.originalQuestions
        .find((question) => question.id === questionId)
        ?.answers.map((answer) => answer.answer) as any;
    },
    getOmrValueTypeChoice(row: number) {
      return this.omr[row];
    },
    getOmrValueTypeInput(omrFocusInfo: OmrFocusInfo | null) {
      if (isNil(omrFocusInfo)) return '';

      const path = Object.values(omrFocusInfo.position).filter((data) => !isNil(data));

      const value = get(this.omr, path);

      return value ? String(value) : '';
    },
    setOmrFocusInfo(omrFocusInfo: OmrFocusInfo | null) {
      if (isEqual(this.omrFocusInfo, omrFocusInfo)) {
        this.omrFocusInfo = null;
      } else {
        this.omrFocusInfo = omrFocusInfo;
      }
    },
    setCurrentQuestion(question: Question | null) {
      this.currentQuestion = question;

      if (isNil(question)) return;

      this.resetReviewCheckedAnswers();

      // 문제풀이가 없는 문제인 경우 첫 번째 문제에 자동 포커스
      this.setOmrFocusInfo(null);
      if (this.getIsSingleQuestion && !this.isFinishedQuestion) {
        this.setOmrFocusInfo({
          location: 'sidebar',
          position: {
            row: 0,
            col: question.answers[0].type === QuestionAnswerEnum.Choice ? null : 0,
            blank: isArray(question.answers[0].answer[0]) ? 0 : null,
          },
        });
      }

      this.initOmr();
    },
    getOmrAnswerType(row: number): QuestionAnswerEnum {
      return this.getCurrentQuestion?.answers[row]?.type || QuestionAnswerEnum.Input;
    },
    setOriginalQuestions(questions: Question[]) {
      this.originalQuestions = questions;
    },
    addOriginalQuestions(question: Question) {
      const findIndex = this.originalQuestions.findIndex((_question) => _question.id === question.id);
      // 이미 추가되어 있다면 추가 하지 않음
      if (findIndex !== -1) return;

      this.originalQuestions.push(question);
    },
    getOriginalOmrValueTypeChoice(row: number) {
      const question = this.getCurrentQuestion;

      if (isNil(question)) return [''];

      const originalQuestion = this.getOriginalQuestions.find((_question) => _question.id === question.id);

      if (isNil(originalQuestion)) return [''];

      return originalQuestion.answers[row].answer;
    },
    getOriginalOmrValueTypeInput(omrFocusInfo: OmrFocusInfo | null) {
      const question = this.getCurrentQuestion;

      if (isNil(question) || isNil(omrFocusInfo)) return '';

      const originalQuestion = this.getOriginalQuestions.find((_question) => _question.id === question.id);

      if (isNil(originalQuestion)) return '';

      const path = Object.values(omrFocusInfo.position).filter((data, index) => index !== 0 && !isNil(data));

      const value = get(originalQuestion.answers[omrFocusInfo.position.row]?.answer, path);

      return value ? String(value) : '';
    },
    setIsFinishedQuestion(state: boolean) {
      this.isFinishedQuestion = state;
    },
    getFloatingPositionStyle(childElement: HTMLElement): StyleValue {
      const parentElement = document.querySelector('.frame__body') as HTMLElement;
      const style: Partial<StyleValue> = {};

      if (isNil(parentElement) || isNil(childElement)) return style;

      const parentRect = parentElement.getBoundingClientRect();
      const childRect = childElement.getBoundingClientRect();

      const findPosition = (parentSize: number, childPosition: number) => {
        const partition = parentSize / 3;
        if (childPosition <= partition) return 'start';
        if (childPosition <= partition * 2) return 'middle';
        return 'end';
      };

      switch (findPosition(parentRect.width, childRect.left + childRect.width)) {
        case 'start':
          style.left = '0';
          break;
        case 'middle':
          style.left = '50%';
          style.transform = 'translateX(-50%)';
          break;
        case 'end':
          style.right = '0';
          break;
      }

      const isFraction = !isNil(this.getOmrFocusInfo?.position.blank);

      const margin = isFraction ? '6.5rem' : '3rem';

      switch (findPosition(parentRect.height, childRect.top + childRect.height)) {
        case 'start':
        case 'middle':
          style.top = margin;
          break;
        case 'end':
          style.top = 'auto';
          style.bottom = margin;
          break;
      }

      return style;
    },
    /**
     * 처음 한번 학습 세팅
     * @param questions 학습에 사용할 문제 배열
     * @param stepResult 학습 결과 - 몇번 째 문제부터 시작할지 지정
     */
    initTraining(questions: Question[], stepResult?: StepResult) {
      this.setIsFinishedQuestion(false);
      this.resetReviewCheckedAnswers();
      this.setQuestions(questions);

      this.timer = isNil(pageData.props.timer) ? 0 : pageData.props.timer;
      this.startTimer();

      if (this.getQuestions.length < 0) {
        alert('등록된 문제가 없습니다.');
        window.close();
        return;
      }

      let question = this.getQuestions[0];

      const urlQuestionId = (route() as any).params.question;

      // 특정 문제 미리 보기일 때 문제 세팅
      if (!isNil(urlQuestionId)) {
        const urlQuestion = this.getQuestions.find((_question) => _question.id === Number(urlQuestionId));
        if (isNil(urlQuestion)) {
          alert('문제를 찾지 못했습니다.');
          window.close();
          return;
        }

        question = urlQuestion;
      }

      // 학습 결과가 넘어오면 알맞는 문제 세팅
      if (!isNil(stepResult)) {
        this.stepResult = stepResult;

        let questionId: undefined | number = undefined;

        // 학습 결과가 비어있으면 제일 첫번째 문제
        if (this.stepResult.questions.length === 0) {
          questionId = this.getQuestions[0].id;
        } else {
          if (
            this.stepResult.questions.length === this.getQuestions.length &&
            this.stepResult[this.stepResult.questions.length - 1]?.completed_at
          ) {
            alert('이미 모든 문제를 완료 했습니다.');
            return;
          }

          questionId = this.stepResult.questions[this.stepResult.questions.length - 1]?.completed_at
            ? this.getQuestions[this.stepResult.questions.length].id
            : this.stepResult.questions.find((questionResult) => isNil(questionResult.completed_at))?.question_id;
        }

        // 비완료 문제를 찾지 못했을 경우
        if (isNil(questionId)) {
          alert('이미 모든 문제를 완료 했습니다.');
          return;
        }

        const foundQuestion = this.getQuestions.find((_question) => _question.id === questionId);

        if (isNil(foundQuestion)) {
          // alert('문제를 찾지 못했습니다.');
          return;
        }

        question = foundQuestion;
      }

      this.setCurrentQuestion(question);
    },
    /**
     * 처음 한번 학습 세팅
     * @param questions 학습에 사용할 문제 배열
     */
    initReviewTraining(questions: Question[]) {
      this.reviewCheckedQuestions = [];
      this.resetReviewCheckedAnswers();
      this.setOmrFocusInfo(null);
      this.setIsFinishedQuestion(true);
      this.setQuestions(questions);
      if (!isNil(pageData.props.timer)) {
        this.timer = pageData.props.timer;
      }

      const stepResult = pageData.props.step_result;
      if (this.getQuestions.length < 0 || isNil(stepResult)) {
        alert('등록된 문제가 없습니다.');
        window.close();
        return;
      }

      this.stepResult = stepResult;

      // 정답 세팅
      stepResult.questions.forEach((question, i) => {
        question.answers.forEach((answers, answerColIndex) => {
          this.questions[i].answers[answerColIndex].answer = answers.correctAnswer;
        });
      });

      const firstWrongQuestion = stepResult.questions.filter((question) => question.correct_percent !== 100)[0];

      if (isNil(firstWrongQuestion)) {
        alert('오답 문제가 없습니다.');
        window.close();
        return;
      } else {
        this.setCurrentQuestion(
          this.questions.find((question) => question.id === firstWrongQuestion.question_id) as Question,
        );
        this.omr = firstWrongQuestion.answers.map((answers) => answers.userAnswer);
      }

      this.originalQuestions = this.questions;
    },

    /**
     * 다음 문제로 넘어가기
     */
    moveToNextQuestion(lastQuestionCallback: () => void) {
      const urlQuestionId = (route() as any).params.questionId;
      if (!isNil(urlQuestionId)) {
        if (confirm('특정 문제 미리보기입니다. 창을 닫으시겠습니까?')) {
          window.close();
        }
        return;
      }

      if (this.getIsLastQuestion) {
        lastQuestionCallback();
        return;
      }
      this.setIsFinishedQuestion(false);
      this.setCurrentQuestion(this.getQuestions[this.getCurrentQuestionIndex + 1]);
    },
    /**
     * 다음 틀린 문제로 넘어가기
     */
    moveNextReviewQuestion() {
      this.resetReviewCheckedAnswers();
      const filteredQuestions = this.stepResult?.questions.filter(
        (questionResult) =>
          questionResult.correct_percent < 100 &&
          this.getReviewCheckedQuestions.findIndex((questionId) => questionId === questionResult.question_id) === -1,
      );

      if (filteredQuestions?.length === 0 || isNil(filteredQuestions)) {
        //
      } else {
        this.currentQuestion = this.questions.find(
          (question) => question.id === filteredQuestions[0].question_id,
        ) as Question;

        this.omr = filteredQuestions[0].answers.map((answers) => answers.userAnswer);
      }
    },
    /**
     * 문제 결과 전송
     */
    async submitResult(
      _params: Omit<TrainingResultParams, 'question_id'>,
      options?: {
        onSuccess?: (result: TrainingResultResponse) => void;
      },
    ) {
      try {
        if (isRequesting) return;

        const currentQuestion = this.getCurrentQuestion;
        if (isNil(currentQuestion)) return;

        // 문제 풀이가 있는 경우에는 포커스 제거
        if (!this.getIsSingleQuestion) {
          this.setOmrFocusInfo(null);
        }

        const stepResult = pageData.props.step_result;

        let params: TrainingResultParams = {
          step_result_id: stepResult?.id,
          question_id: currentQuestion.id,
          timer: _params.timer,
        };

        params = {
          ...params,
          ..._params,
        };

        isRequesting = true;
        const { data } = await storeTrainingResult(pageData.props.training, params);
        isRequesting = false;

        if (data.data?.step_result) {
          this.stepResult = data.data.step_result;
        }

        if (!isNil(data.data.question)) {
          this.addOriginalQuestions(data.data.question);
        }

        if (!isNil(options?.onSuccess)) {
          options?.onSuccess(data.data);
        }
      } catch (err: any) {
        isRequesting = false;
        systemStore.setModalState({
          show: true,
          message: err.message,
        });
      }
    },
    setTimer(timer: number) {
      this.timer = timer;
    },
    startTimer() {
      if (isNil(this.timer)) {
        this.timer = 0;
      }
      this.stopTimer();

      this.isStopTimer = false;

      this.timerInterval = Number(
        setInterval(() => {
          this.timer++;
        }, 1000),
      );
    },
    stopTimer() {
      if (!isNil(this.timerInterval)) {
        this.isStopTimer = true;
        clearInterval(this.timerInterval);
      }
    },
    /**
     * 정리된 발문을 반환 ('//': 제거, '** **': 두껍게)
     */
    getInquiry(inquiry: string): string {
      return removeByDoubleSlash(inquiry).replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
    },
    /**
     * 정답이 채워진 문제풀이를 반환 함.
     * 서술형 2단계의 경우 순서 맞추기 부분을 제거하고 반환.
     */
    getFilledCorrectQuestion(question: Question) {
      // n 번째 문제에 빈칸 문제가 몇개 있는지 기록
      const answerColCount: {
        [answerRow in number]: number;
      } = {};

      return removeByDoubleSlash(question.question?.split('<hr>')[0] || '')
        .replace(/\${(\[?.*?]?)}/g, (match: string, capturedValue: string) => {
          const answerRow = Number(capturedValue.replace(/-/g, '').match(/\d+/)) - 1;

          if (isNil(answerColCount[answerRow])) {
            answerColCount[answerRow] = 0;
          } else {
            answerColCount;
            answerColCount[answerRow]++;
          }

          const answerCol = answerColCount[answerRow];

          const type = question.answers[answerRow].type;
          const answer = question.answers[answerRow].answer[answerCol] as string;

          return type === QuestionAnswerEnum.Choice
            ? String(question.answers[answerRow].choices[Number(answer) - 1])
            : answer;
        })
        .replace(/#{\[(\d+)-(\d+)\]}/g, (match: string, answerRow: string, answerCol: string) => {
          const answerRowIndex = Number(answerRow) - 1;
          const answerColIndex = Number(answerCol) - 1;
          const type = question.answers[answerRowIndex].type;
          const answer = question.answers[answerRowIndex].answer[answerColIndex] as string;

          return type === QuestionAnswerEnum.Choice
            ? String(question.answers[answerRowIndex].choices[Number(answer) - 1])
            : answer;
        });
    },
  },
});

export function useCurriculumStoreWithOut() {
  return useCurriculumStore(store);
}
