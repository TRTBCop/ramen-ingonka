import { Result } from '@/app/api/model/baseModel';
import ApiService from '@/app/core/services/ApiService';

/**
 * 탈퇴
 * @returns
 */
export async function postWithDraw() {
  return ApiService.post<Result>(route('app.my.withdraw.update'), {});
}
