import { PageProps } from '@/app/types/pageData';
import { usePage } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { isNil } from 'lodash';

/** 로그인한 유저의 정보를 반환함. */
export function getUserData() {
  return usePage<PageProps>().props.auth.user;
}

/**
 * 유저의 학교 관련 정보들이 담겨있다
 * @returns step: 초등 | 중등, grade: grade | middleGrade, term: 학기, gradeTerm: {grade}-{term}
 */
export function getUserSchoolInfo() {
  const userData = getUserData();

  if (!userData.grade) return null;

  if (userData.grade <= 6) {
    return {
      step: '초등',
      grade: userData.grade,
      term: userData.term,
      gradeTerm: `${userData.grade}-${userData.term}`,
    };
  } else {
    const middleSchoolGrade = userData.grade - 6;
    return {
      step: '중등',
      grade: middleSchoolGrade,
      term: userData.term,
      gradeTerm: `${middleSchoolGrade}-${userData.term}`,
    };
  }
}

/** b2c 유저인지 여부 */
export function isB2cUser() {
  return getUserData().academy_id === null;
}

/**
 * 이미지 타입 숫자로 받아 클래스명에 사용 가능한 문자열로 치환
 * @param initVal 초기화값, 없으면 로그인 유저의 데이터 사용
 * @returns
 */
export function getUserProfileType(initVal?: number) {
  const type = initVal || getUserData().profile_img_type;

  return `type${type.toString().padStart(2, '0')}`;
}

/**
 * 유저의 이용기간이 끝났는지 여부
 */
export function isUserServiceEnded() {
  // b2b는 서비스 종료 기간이 없음
  if (!isB2cUser()) {
    return false;
  }

  const userData = getUserData();

  // 오늘 날짜
  const today = dayjs();
  const targetDate = dayjs(userData.service_end_date);

  return today.isAfter(targetDate);
}

/** 유저가 무료체험 학생인지 여부 */
export function isFreeUser() {
  return !isNil(getUserData().extra?.free_trial);
}

/** 무료 체험 기간 종료 여부  */
export function isFreeExpired() {
  const userData = getUserData().extra?.free_trial;
  if (isNil(userData)) {
    return false;
  }

  return freeDaysDiffFromToday() < 0;
}

/** 무료체험 기간 몇일 남았는지 반환하는 메서드 */
export function freeDaysDiffFromToday() {
  if (isNil(getUserData().extra?.free_trial)) {
    return 0;
  }
  const diffInDays = dayjs(getUserData()?.extra?.free_trial?.end_date).diff(dayjs().format('YYYY-MM-DD'), 'day');

  return diffInDays;
}

/**
 * 파라미터 가져오기
 */
export function getUrlParams() {
  const segments = usePage<PageProps>().url.split('/');

  return {
    curriculum: parseInt(segments[3]),
    stage: parseInt(segments[4]),
    num: segments[6] ? parseInt(segments[6]) : null,
  };
}

/** 프로필 이미지 타입들 */
export const profileImgTypes = [1, 2, 3, 4, 5, 6, 7, 8];
