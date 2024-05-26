import { Result } from '@/app/api/model/baseModel';
import ApiService from '@/app/core/services/ApiService';

export function updateTrainingResultTimer(trainingResultId: number, timer: number) {
  return ApiService.put<Result>(route('app.training-results.timer.update', { trainingResult: trainingResultId }), {
    timer,
  });
}
