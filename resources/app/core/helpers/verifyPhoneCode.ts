import { postVerificationCodeCheck, postVerificationCodeSend } from '@/app/api/register';
import { useSystemStoreWithOut } from '@/app/stores/modules/system';
import { computed, onBeforeUnmount, ref } from 'vue';
import { useI18n } from 'vue-i18n';

/**
 * 핸드폰 인증할 때 쓰이는 로직들
 * @returns
 */
export function useVerifyPhoneCode() {
  const { t } = useI18n();
  const systemStore = useSystemStoreWithOut();

  /** 핸드폰 인증 제한 시간 - 초단위 (기본 3분) */
  const basePhoneTime = 180;
  const phoneTimer = ref(0);
  let phoneTimeInterval: number | null = null;

  /** 핸드폰 인증 번호 전송 여부 */
  const isSendVerificationCode = ref(false);

  /** 핸드폰 인증 남은 초 */
  const phoneLeftSecond = computed(() => {
    const seconds = Math.floor(phoneTimer.value % 60);

    return seconds < 10 ? '0' + seconds : String(seconds);
  });

  /** 핸드폰 인증 남은 분 */
  const phoneLeftMinutes = computed(() => {
    const timeDiff = Math.floor(phoneTimer.value / 60);

    const minutes = timeDiff % 60;

    return minutes < 10 ? '0' + minutes : String(minutes);
  });

  /**
   * 핸드폰이 인증 번호를 전송하고 결과 값을 반환
   * @param phoneNumber 문자를 보낼 번호
   * @returns
   */
  const handleVerificationCodeSend = async (phoneNumber: string) => {
    try {
      isSendVerificationCode.value = false;
      const { data } = await postVerificationCodeSend(phoneNumber);

      if (!data?.success) throw new Error();

      isSendVerificationCode.value = true;

      if (phoneTimeInterval) {
        clearInterval(phoneTimeInterval);
      }
      phoneTimer.value = basePhoneTime;

      // 3분 시간 잼
      phoneTimeInterval = Number(
        setInterval(() => {
          // 0초면 핸드폰 전송 초기화 및 interval 제거
          if (phoneTimer.value === 0 && phoneTimeInterval) {
            isSendVerificationCode.value = false;
            clearInterval(phoneTimeInterval);
            return;
          }
          phoneTimer.value--;
        }, 1000),
      );

      // 성공 모달
      systemStore.setModalState({
        show: true,
        message: t('system.verificationCodeSend.success'),
      });
    } catch (err: any) {
      // 실패 모달
      systemStore.setModalState({
        show: true,
        message: t('system.verificationCodeSend.failed'),
      });
    }
  };

  const handleVerificationCodeCheck = async (
    phoneNumber: string,
    code: string,
    callback: (success: boolean, student_pohne_id: number | null) => void,
  ) => {
    try {
      const { data } = await postVerificationCodeCheck(phoneNumber, code);

      if (!data.success) throw new Error();

      // 인증번호 제한시간 interval 제거
      if (phoneTimeInterval) {
        clearInterval(phoneTimeInterval);
      }

      phoneTimer.value = basePhoneTime;

      callback(true, data.data.student_phone_id);
    } catch (err) {
      console.log(err);
      callback(false, null);
    }
  };

  onBeforeUnmount(() => {
    if (phoneTimeInterval) {
      clearInterval(phoneTimeInterval);
    }
  });

  return {
    isSendVerificationCode,
    handleVerificationCodeSend,
    handleVerificationCodeCheck,
    phoneLeftSecond,
    phoneLeftMinutes,
    phoneTimer,
  };
}
