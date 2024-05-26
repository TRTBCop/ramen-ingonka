import { useI18n } from 'vue-i18n';
import { map, sum } from 'lodash';
import { dayjs } from 'element-plus';

/**
 * form에서 검사할 항목들을 모아둔 곳
 * @returns
 */
export function useAppFormRules() {
  const { t } = useI18n();

  function accessIdRules(scuccessCallback?: () => void, failedCallback?: () => void): AppFormRule[] {
    return [
      { required: true, message: t('valid.access_id.required') },
      {
        validator: (v) => {
          if (/^(?=.*[A-Za-z\d])[A-Za-z\d]{6,12}$/.test(v)) {
            if (scuccessCallback) {
              scuccessCallback();
            }
            return true;
          } else {
            if (failedCallback) {
              failedCallback();
            }
            return false;
          }
        },
        message: t('valid.access_id.valid'),
      },
    ];
  }

  function passwordRules(required = true): AppFormRule[] {
    return [
      { required, message: t('valid.password.required') },
      {
        validator: (v: string) => /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,18}$/.test(v),
        message: t('valid.password.valid'),
      },
    ];
  }

  function cPasswordRules<T extends { password: string }>(formData: T): AppFormRule[] {
    return [
      { required: Boolean(formData.password), message: t('valid.c_password.required') },
      { validator: (v) => formData.password === v, message: t('valid.c_password.mismatch') },
    ];
  }

  function nameRules(): AppFormRule[] {
    return [
      { required: true, message: t('valid.name.required') },
      {
        validator: (v: string) => /^[가-힣]+$/u.test(v),
        message: t('valid.name.onlyKor'),
      },
    ];
  }

  function birthDateRules(): AppFormRule[] {
    return [
      { required: true, message: t('valid.birth_date.required') },
      {
        validator: (v: string) => /^\d{4}-\d{2}-\d{2}$/.test(v),
        message: t('valid.birth_date.valid'),
      },
      {
        validator: (v: string) => {
          return dayjs(v).isBefore(dayjs().startOf('day'));
        },
        message: t('valid.birth_date.after'),
      },
    ];
  }

  function phoneRules(): AppFormRule[] {
    return [
      {
        validator: (v: string) => /^(010)[0-9]{4}[0-9]{4}$/.test(v),
        message: t('valid.phone.valid'),
      },
    ];
  }

  function parentsNameRules(): AppFormRule[] {
    return [
      { required: true, message: t('valid.parents_name.required') },
      {
        validator: (v: string) => /^[가-힣]+$/u.test(v),
        message: t('valid.parents_name.onlyKor'),
      },
    ];
  }

  function parentsPhoneRules(): AppFormRule[] {
    return [
      { required: true, message: t('valid.parents_phone.required') },
      {
        validator: (v: string) => /^(010)[0-9]{4}[0-9]{4}$/.test(v),
        message: t('valid.parents_phone.valid'),
      },
    ];
  }

  function codeRules<T extends { student_phone_id: number }>(formData: T): AppFormRule[] {
    return [
      { required: true, message: t('valid.code.required') },
      {
        validator: (v: string) => /^[0-9]+$/.test(v),
        message: t('valid.code.onlyNumber'),
      },
      {
        validator: () => Boolean(formData.student_phone_id),
        message: t('valid.code.required'),
      },
    ];
  }

  return {
    accessIdRules,
    passwordRules,
    cPasswordRules,
    nameRules,
    birthDateRules,
    phoneRules,
    parentsNameRules,
    parentsPhoneRules,
    codeRules,
  };
}

/**
 * 속성 값에 해당 하는 유효성 검사를 진행 한다.
 * @param value 해당 속성의 값
 * @param rules 해당 속성의 rules
 * @returns 에러 메시지를 배열 형태로 반환
 */
export const validateFormField = (value: unknown, rules: AppFormRule[]) => {
  const tempMessages: string[] = [];

  rules.forEach((rule) => {
    if (
      (rule.required && !value) ||
      (typeof rule.validator === 'function' && !rule.validator(String(value)) && value !== '')
    ) {
      tempMessages.push(rule.message);
    }
  });

  return tempMessages;
};

/**
 * 전달 받은 formData를 rules에 있는 규칙들을 기준으로 유효겅 검사를 진행 한 뒤 결과를 반환한다.
 * @param formData 검사를 진행할 폼 데이터 값
 * @param rules 검사를 진행할 규칙들
 * @returns success: 성공여부, newErrorMessages: 에러가 났다면 에러에 해당하는 메시지들의 배열
 */
export function allValidateFormField(
  formData: object,
  rules: AppFormRules,
): {
  success: boolean;
  newErrorMessages: { [key: string]: string[] };
} {
  const newErrorMessages: { [key: string]: string[] } = {};

  Object.keys(rules).forEach((name) => {
    newErrorMessages[name] = validateFormField(formData[name], rules[name]);
  });

  return {
    success: !sum(map(newErrorMessages, (value) => value.length)),
    newErrorMessages,
  };
}

export interface AppFormRule {
  required?: boolean;
  message: string;
  validator?: (v: string) => boolean;
}

export interface AppFormRules {
  [key: string]: AppFormRule[];
}
