import { Result } from '@/app/api/model/baseModel';
import ApiService from '@/app/core/services/ApiService';

export function updateTestTimer(testId: number, timer: number) {
  return ApiService.put<Result>(route('app.tests.timer.update', testId), {
    timer,
  });
}
