import { Result } from '@/admin/api/model/baseModel';
import ApiService from '@/admin/core/services/ApiService';

// 결제 취소
export function cancelPayment<T>(id: number) {
  return ApiService.post<Result<T>>(route('admin.payments.cancel', id), {});
}
