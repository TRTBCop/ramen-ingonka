import { Result } from '@/app/api/model/baseModel';
import ApiService from '@/app/core/services/ApiService';

/**
 * 아이디 중복 검사 api
 * @param access_id 입력 아이디
 * @returns
 */
export function getCheckAccount(access_id: string) {
  return ApiService.get<Result>(route('app.register.check-account', { access_id }));
}

/**
 * 핸드폰 인증 문자 발송
 * @param parents_phone 코드를 보낼 핸드폰 번호
 * @returns
 */
export function postVerificationCodeSend(phone: string) {
  return ApiService.post<Result>(route('app.register.verification-code-send'), { phone });
}

export function postVerificationCodeCheck(phone: string, code: string) {
  return ApiService.post<Result<{ student_phone_id: number }>>(route('app.register.verification-code-check'), {
    phone,
    code,
  });
}
