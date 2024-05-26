import { Result } from '@/app/api/model/baseModel';
import ApiService from '@/app/core/services/ApiService';

// 결제 데이터 암호화
export function encryptParams<T>(request: object) {
  return ApiService.post<Result<T>>(route('app.payments.encrypt-params'), request);
}

// 결제 취소
export function cancelPayment<T>(id: number) {
  return ApiService.post<Result<T>>(route('app.payments.cancel', id), {});
}
