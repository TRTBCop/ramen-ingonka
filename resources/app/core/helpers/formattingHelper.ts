import { dayjs } from 'element-plus';
import { isNil } from 'lodash';

/**
 * 초를 받아서 1시간 이상이면 "h:mm:ss"로, 1시간 미만이면 "m:ss"로 반환하는 함수
 * @param seconds
 * @returns
 */
export function formatTime(seconds: number): string {
  const hours = Math.floor(seconds / 3600);
  const minutes = Math.floor((seconds % 3600) / 60);
  const remainingSeconds = seconds % 60;

  const formattedHours = hours > 0 ? hours.toString().padStart(2, '0') : '';
  const formattedMinutes = minutes.toString().padStart(2, '0');
  const formattedSeconds = remainingSeconds.toString().padStart(2, '0');

  return `${formattedHours}${formattedHours ? ':' : ''}${formattedMinutes}:${formattedSeconds}`;
}

export function formatDate(date: string): string {
  return dayjs(date).format('YYYY-MM-DD');
}

/** 값이 있으면 값을 리턴하고 없으면 '-'를 반환하는 메서드 */
export function getValueOrDash(value: unknown) {
  if (isNil(value) || value === 0) {
    return '-';
  } else {
    return value;
  }
}

export function secondsToMinutesSeconds(seconds: number) {
  const minutes = Math.floor(seconds / 60);
  const remainingSeconds = seconds % 60;

  const formattedTime = minutes.toString().padStart(2, '0') + ':' + remainingSeconds.toString().padStart(2, '0');

  return formattedTime;
}

/**
 * 1~9까지의 숫자를 받아서 중등 상태이면 6을 빼고 반환하는 메서드
 */
export function subtractSixIfMiddleSchool(grade: number) {
  return grade > 6 ? grade - 6 : grade;
}

/** 천단위로 콤마를 찍는 메서드 */
export function numberWithCommas(x: number) {
  return x.toLocaleString('en-US');
}
