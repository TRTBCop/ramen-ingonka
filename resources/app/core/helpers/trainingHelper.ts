import { Curriculum } from '@/app/api/model/curriculum';
import { TrainingStage } from '@/app/api/model/training';
import { StageResult } from '@/app/types/pageData';
import { isNil, values } from 'lodash';

/**
 * 훈련 이름 반환
 */
export function getStageName(stage: TrainingStage) {
  switch (stage) {
    case TrainingStage.STAGE_1:
      return '개념';
    case TrainingStage.STAGE_2:
      return '유형';
    case TrainingStage.STAGE_3:
      return '서술형';
  }
}

/**
 * 훈련 단계 이름 반환
 */
export function getStepName(stage: TrainingStage, stepKey: string | number) {
  switch (stage) {
    case TrainingStage.STAGE_1:
      switch (stepKey) {
        case 'texts':
          return '개념 학습';
        case 'operations':
          return '기초 연산';
      }
      break;
    case TrainingStage.STAGE_2:
      stepKey = Number(stepKey);

      switch (stepKey) {
        case 0:
          return '1차 유형 문제';
        case 1:
          return '1차 유사 문제';
        case 2:
          return '2차 유형 문제';
        case 3:
          return '2차 유사 문제';
      }
      break;
    case TrainingStage.STAGE_3:
      stepKey = Number(stepKey);
      switch (stepKey) {
        case 0:
          return '1단계 빈칸채우기';
        case 1:
          return '2단계 기본 문제';
        case 2:
          return '3단계 쌍둥이 문제';
      }
      break;
  }

  return '';
}

/**
 * 훈련에 포함된 단계들의 key 값을 반환
 */
export function getStepList(stage: TrainingStage) {
  switch (stage) {
    case TrainingStage.STAGE_1:
      return ['texts', 'operations'];
    case TrainingStage.STAGE_2:
      return [0, 1, 2, 3];
    case TrainingStage.STAGE_3:
      return [0, 1, 2];
  }
}

/**
 * 개념훈련, 유형훈련, 서술형훈련의 평균 점수를 반환
 * 모두 완료하지 않았으면 null을 반환
 */
export function getStageScore(stageResult: StageResult) {
  const completedTrainigResults = values(stageResult).filter((trainingResult) => !isNil(trainingResult.completed_at));

  // 모두 완료 안했으면 null 반환
  if (completedTrainigResults.length !== 3) return null;

  return Math.round(
    completedTrainigResults.reduce((prev, current) => prev + current.score, 0) / completedTrainigResults.length,
  );
}

/**
 * '//'를 기준으로 문자열을 분리한다.
 */
export function splitByDoubleSlash(str: string) {
  return str
    .replace(/<svg[^>]*>.*?<\/svg>/gs, (match) => match.replace(/\/\//g, '%%%%'))
    .replace(/<(\/?span|\/?p)[^>]*>/g, '')
    .split(/(?<!https?:)\/\/+/)
    .map((part) => part.replace(/%%%%/g, '//'))
    .filter((data) => data !== '');
}

/**
 * 문자열에 있는 '//'를 제거 한다.
 */
export function removeByDoubleSlash(str: string) {
  return str
    .replace(/<svg[^>]*>.*?<\/svg>/gs, (match) => match.replace(/\/\//g, '%%%%'))
    .replace(/(?<!https?:)\/\/+/g, '')
    .replace(/%%%%/g, '//');
}

/**
 * 소단원을 받아 대단원을 반환하는 메서드
 * 대단원을 못찾으면 null 반환
 * @returns
 */
export function getChapterByCurriculum(curriculum: Curriculum) {
  const ancestors = curriculum?.ancestors;

  if (isNil(ancestors)) return null;

  return ancestors[2];
}

/**
 * 점수에 맞는 랭크를 반환
 * @param score 점수
 * @param isLower 대/소문자
 * @returns string
 */
export function getRankByScore(score: number, isLower = true) {
  let rank = 'C';

  if (score >= 95) {
    rank = 'S';
  } else if (score >= 85) {
    rank = 'A';
  } else if (score >= 65) {
    rank = 'B';
  }

  return isLower ? rank.toLocaleLowerCase() : rank;
}

export function getThemeTypeClass(stage: TrainingStage) {
  switch (stage) {
    case 1:
      return 'theme--textbook';
    case 2:
      return 'theme--question';
    case 3:
      return 'theme--descriptive';
  }
}
