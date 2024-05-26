<template>
  <AppRegisterLayout>
    <div class="login_wrap">
      <button class="btn--back" @click="goOtherLoginPage">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>

      <div class="title">
        <h2>회원가입</h2>
        <p>회원가입에 필요한 정보를 입력해 주세요</p>
      </div>

      <div class="join_wrap">
        <div v-if="!isSocialLogin" class="input__box">
          <h4>계정 정보</h4>
          <AppFormInput
            v-model:value="formData.access_id"
            v-model:error-messages="errorMessages"
            name="access_id"
            icon="fa-regular fa-user"
            :placehoder="t('placehoder.access_id')"
            :rules="rules.access_id"
          />
          <AppFormInput
            v-model:value="formData.password"
            v-model:error-messages="errorMessages"
            name="password"
            icon="fa-regular fa-lock"
            :placehoder="t('placehoder.password')"
            :type="showPassword ? 'text' : 'password'"
            :rules="rules.password"
          >
            <button class="btn_pw" @click="showPassword = !showPassword">
              <font-awesome-icon icon="fa-regular fa-eye" />
            </button>
          </AppFormInput>
          <AppFormInput
            v-model:value="formData.c_password"
            v-model:error-messages="errorMessages"
            name="c_password"
            icon="fa-regular fa-lock"
            :placehoder="t('placehoder.c_password')"
            :type="showCheckPassword ? 'text' : 'password'"
            :rules="rules.c_password"
          >
            <button class="btn_pw" @click="showCheckPassword = !showCheckPassword">
              <font-awesome-icon icon="fa-regular fa-eye" />
            </button>
          </AppFormInput>

          <ul class="box__error">
            <li v-for="message in errorMessages.access_id" :key="message">
              <span class="">{{ message }}</span>
            </li>
            <li v-for="message in errorMessages.password" :key="message">
              <span class="">{{ message }}</span>
            </li>
            <li v-for="message in errorMessages.c_password" :key="message">
              <span class="">{{ message }}</span>
            </li>
          </ul>
        </div>

        <div class="input__box">
          <h4>학생 정보</h4>
          <AppFormInput
            v-model:value="formData.name"
            v-model:error-messages="errorMessages"
            name="name"
            icon="fa-regular fa-user"
            :placehoder="t('placehoder.name')"
            maxlength="10"
            :rules="rules.name"
          />
          <AppFormInput
            v-model:value="formData.birth_date"
            v-model:error-messages="errorMessages"
            name="birth_date"
            icon="fa-regular fa-calendar"
            :placehoder="t('placehoder.birth_date')"
            :rules="rules.birth_date"
            maxlength="10"
          />
          <AppFormInput
            v-model:value="formData.phone"
            v-model:error-messages="errorMessages"
            name="phone"
            icon="fa-regular fa-mobile-notch"
            :placehoder="t('placehoder.phone')"
            maxlength="11"
            :rules="rules.phone"
          />
          <ul class="box__error">
            <li v-for="message in errorMessages.name" :key="message">
              <span class="">{{ message }}</span>
            </li>
            <li v-for="message in errorMessages.birth_date" :key="message">
              <span class="">{{ message }}</span>
            </li>
            <li v-for="message in errorMessages.phone" :key="message">
              <span class="">{{ message }}</span>
            </li>
          </ul>
        </div>

        <div class="input__box">
          <h4>학부모 정보</h4>
          <AppFormInput
            v-model:value="formData.parents_name"
            v-model:error-messages="errorMessages"
            name="parents_name"
            icon="fa-regular fa-user"
            :placehoder="t('placehoder.parents_name')"
            maxlength="10"
            :rules="rules.parents_name"
          />
          <div class="input--row">
            <AppFormInput
              v-model:value="formData.parents_phone"
              v-model:error-messages="errorMessages"
              name="parents_phone"
              icon="fa-regular fa-mobile-notch"
              :placehoder="t('placehoder.parents_phone')"
              maxlength="11"
              :disabled="isSendVerificationCode"
              :rules="rules.parents_phone"
            />
            <button
              class="btn--gray"
              :disabled="
                !errorMessages.parents_phone ||
                (errorMessages.parents_phone && errorMessages.parents_phone.length > 0) ||
                isSendVerificationCode
              "
              @click="clickVerificationCodeSend(formData.parents_phone)"
              >인증번호 발송</button
            >
          </div>
          <div class="input--row">
            <AppFormInput
              v-model:value="formData.code"
              v-model:error-messages="errorMessages"
              name="code"
              icon="fa-regular fa-hashtag"
              :placehoder="t('placehoder.code')"
              :disabled="Boolean(formData.student_phone_id)"
              :rules="rules.code"
            >
              <p v-if="!Boolean(formData.student_phone_id)" class="timer"
                >{{ phoneLeftMinutes }}:{{ phoneLeftSecond }}</p
              >
            </AppFormInput>
            <button
              class="btn--gray"
              :disabled="Boolean(formData.student_phone_id) || !isSendVerificationCode"
              @click="clickVerificationCodeCheck(formData.parents_phone, formData.code)"
            >
              인증번호 확인
            </button>
          </div>

          <ul class="box__error">
            <li v-for="message in errorMessages.parents_name" :key="message">
              <span class="">{{ message }}</span>
            </li>
            <li v-for="message in errorMessages.parents_phone" :key="message">
              <span class="">{{ message }}</span>
            </li>
            <li v-for="message in errorMessages.code" :key="message">
              <span class="">{{ message }}</span>
            </li>
          </ul>
        </div>

        <div class="input__box">
          <h4>이벤트 정보</h4>
          <AppFormInput
            v-model:value="formData.referral"
            v-model:error-messages="errorMessages"
            name="referral"
            icon="fa-regular fa-party-horn"
            :placehoder="t('placehoder.referral')"
            :rules="rules.referral"
          />
        </div>
      </div>

      <div class="input__box">
        <h4>이용 동의</h4>
        <div class="join--row border checkbox__wrap">
          <input id="ck01" v-model="isAllAgree" type="checkbox" @click="clickAllAgree(!isAllAgree)" />
          <label for="ck01"> 모두 동의합니다. </label>
        </div>
        <hr />
        <div class="join--row checkbox__wrap">
          <input id="ck02" v-model="agreementList[0]" type="checkbox" @click="clickCheckBox(0, !agreementList[0])" />
          <label for="ck02"> 서비스 이용약관 동의 (필수) </label>
          <a @click="showPolicyModal('서비스 이용약관 동의', pageData.props.agree)">보기</a>
        </div>
        <div class="join--row checkbox__wrap">
          <input id="ck03" v-model="agreementList[1]" type="checkbox" @click="clickCheckBox(1, !agreementList[1])" />
          <label for="ck03"> 개인정보 처리 방침 동의 (필수) </label>
          <a @click="showPolicyModal('개인정보 처리 방침 동의', pageData.props.privacy)">보기</a>
        </div>
        <div class="join--row checkbox__wrap">
          <input id="ck04" v-model="agreementList[2]" type="checkbox" @click="clickCheckBox(2, !agreementList[2])" />
          <label for="ck04"> 마케팅 활용 동의 (선택) </label>
          <a @click="showPolicyModal('마케팅 활용 동의', pageData.props.marketing)">보기</a>
        </div>
      </div>

      <div class="btns">
        <button class="btn--sub" @click="goOtherLoginPage">취소</button>
        <button class="btn--brand" @click="handleRegisterAction">회원가입</button>
      </div>

      <!-- 개인정보 동의 상세 보기 모달 -->
      <div v-if="policyModalState.isShow" class="ly_join">
        <div class="ly_head">
          <strong>{{ policyModalState.data.title }}</strong>
          <button class="btn--close" @click="closePolicyModal">
            <font-awesome-icon icon="fa-regular fa-xmark" />
          </button>
        </div>
        <div class="ly_body">
          <div class="policy" v-html="policyModalState.data.content"></div>
        </div>
      </div>
    </div>
  </AppRegisterLayout>
</template>

<script setup lang="ts">
  import { watch, reactive, ref, computed } from 'vue';
  import { getCheckAccount } from '@/app/api/register';
  import AppRegisterLayout from '@/app/layouts/AppRegisterLayout.vue';
  import { useForm, usePage } from '@inertiajs/vue3';
  import { PageProps } from '@/app/types/pageData';
  import { useI18n } from 'vue-i18n';
  import { AppFormRules, allValidateFormField, useAppFormRules, validateFormField } from '@/app/core/helpers/validator';
  import AppFormInput from '@/app/components/AppFormInput.vue';
  import { PolicyEnum } from '@/app/enum/PolicyEnum';
  import { useVerifyPhoneCode } from '@/app/core/helpers/verifyPhoneCode';
  import { useSystemStoreWithOut } from '@/app/stores/modules/system';
  import { goOtherLoginPage } from '@/app/core/helpers/routerHelper';

  const { t } = useI18n();

  const systemStore = useSystemStoreWithOut();

  const {
    accessIdRules,
    passwordRules,
    cPasswordRules,
    nameRules,
    birthDateRules,
    phoneRules,
    parentsNameRules,
    parentsPhoneRules,
    codeRules,
  } = useAppFormRules();

  const {
    handleVerificationCodeSend,
    isSendVerificationCode,
    handleVerificationCodeCheck,
    phoneLeftSecond,
    phoneLeftMinutes,
    phoneTimer,
  } = useVerifyPhoneCode();

  interface Page extends PageProps {
    naver_id: null | string;
    kakao_id: null | string;
    agree: string;
    privacy: string;
    marketing: string;
    route_name: string;
  }

  const pageData = usePage<Page>();

  /** 소셜 로그인 여부 */
  const isSocialLogin = ref(false);

  const formData = useForm(() => {
    let result: {
      access_id: string;
      password: string;
      c_password: string;
      student_phone_id: number;
      referral: string;
      name: string;
      phone: string;
      birth_date: string;
      parents_name: string;
      parents_phone: string;
      marketing_consent: boolean;
      code: string;
      kakao_id?: string | null;
      naver_id?: string | null;
    } = {
      access_id: '',
      password: '',
      c_password: '',
      student_phone_id: 0,
      referral: '',
      name: '',
      phone: '',
      birth_date: '',
      parents_name: '',
      parents_phone: '',
      marketing_consent: false,
      code: '',
    };

    // 카카오 로그인
    if (pageData.props.kakao_id) {
      result.kakao_id = pageData.props.kakao_id;
      isSocialLogin.value = true;
    }

    // 네이버 로그인
    if (pageData.props.naver_id) {
      result.naver_id = pageData.props.naver_id;
      isSocialLogin.value = true;
    }

    return result;
  });

  const rules = reactive<AppFormRules>(
    (() => {
      let rules: AppFormRules = {
        name: nameRules(),
        birth_date: birthDateRules(),
        phone: phoneRules(),
        parents_name: parentsNameRules(),
        parents_phone: parentsPhoneRules(),
        code: codeRules(formData),
      };

      if (!isSocialLogin.value) {
        rules = {
          ...rules,
          access_id: accessIdRules(
            () => handleIdCheckAccount(formData.access_id),
            () => (isIdAvailable.value = false),
          ),
          password: passwordRules(),
          c_password: cPasswordRules(formData),
        };
      }

      return rules;
    })(),
  );

  const errorMessages = ref<{ [key: string]: string[] }>({});

  const showPassword = ref(false);
  const showCheckPassword = ref(false);

  /** 아이디 중복체크 여부 */
  const isIdAvailable = ref(false);

  const policyModalState = reactive({
    isShow: false,
    data: {
      title: '',
      content: '',
    },
  });

  /** 모두 동의합니다 */
  const isAllAgree = ref(false);

  /**
   * 0: 서비스 이용약관 동의
   * 1: 개인정보 처리방침 동의
   * 2: 마케팅 활용 동의
   */
  const agreementList = ref([false, false, false]);

  /** 필수 체크 여부 */
  const isRequirePolicyChecked = computed(
    () => agreementList.value[PolicyEnum.AGREE] && agreementList.value[PolicyEnum.PRIVACY],
  );

  /**
   * 0: 서비스 이용약관 동의
   * 1: 개인정보 처리방침 동의
   * 2: 마케팅 활용 동의
   */
  const showPolicyModal = (title: string, content: string) => {
    policyModalState.isShow = true;
    policyModalState.data = {
      title,
      content,
    };
  };

  const closePolicyModal = () => {
    policyModalState.isShow = false;
    policyModalState.data = {
      title: '',
      content: '',
    };
  };

  const clickAllAgree = (value: boolean) => {
    if (value) {
      agreementList.value = agreementList.value.map(() => true);
    } else {
      agreementList.value = agreementList.value.map(() => false);
    }
    formData.marketing_consent = value;
  };

  const clickCheckBox = (index: number, value: boolean) => {
    agreementList.value[index] = value;

    // 마케팅 동의 눌렀을 경우
    if (index === PolicyEnum.MARKETING) {
      formData.marketing_consent = value;
    }

    if (agreementList.value.reduce((current, prev) => current && prev, true)) {
      isAllAgree.value = true;
    } else {
      isAllAgree.value = false;
    }
  };

  /** 핸드폰 인증 번호 발송 버튼 클릭 시 */
  const clickVerificationCodeSend = async (parentsPhone: string) => {
    handleVerificationCodeSend(parentsPhone);
  };

  /** 핸드폰 인증 번호 확인 버튼 클릭 시 */
  const clickVerificationCodeCheck = async (parentsPhone: string, code: string) => {
    if (phoneTimer.value <= 0) {
      systemStore.setModalState({
        show: true,
        message: t('system.verificationCodeCheck.timeout'),
      });
      return;
    }

    handleVerificationCodeCheck(parentsPhone, code, (success, student_pohne_id) => {
      if (success && student_pohne_id) {
        systemStore.setModalState({
          show: true,
          message: t('system.verificationCodeCheck.success'),
        });
        formData.student_phone_id = student_pohne_id;
        errorMessages.value.code = validateFormField(formData.code, rules.code);
      } else {
        systemStore.setModalState({
          show: true,
          message: t('system.verificationCodeCheck.failed'),
        });
        formData.student_phone_id = 0;
      }
    });
  };

  const handleIdCheckAccount = async (accessId: string) => {
    try {
      const { data } = await getCheckAccount(accessId);
      if (!data.success) throw new Error();

      isIdAvailable.value = true;
      errorMessages.value.access_id = [];
    } catch (err) {
      errorMessages.value.access_id.push(t('valid.access_id.duplicate'));
      console.log(err);
    }
  };

  const handleRegisterAction = () => {
    const { success, newErrorMessages } = allValidateFormField(formData, rules);

    errorMessages.value = newErrorMessages;

    if (success) {
      if (isRequirePolicyChecked.value) {
        formData.post(route('app.register.store'));
      } else {
        systemStore.setModalState({
          show: true,
          message: t('system.requiredPolicyCheck.failed'),
        });
      }
    }
  };

  // 부모님 휴대폰 번호는 입력 할 때 마다 유효성 검사를 바로 바로 하게 끔 처리
  watch(
    () => formData.parents_phone,
    () => {
      errorMessages.value.parents_phone = validateFormField(formData.parents_phone, rules.parents_phone);
    },
  );

  // 생년월일 입력 감지
  watch(
    () => formData.birth_date,
    (value) => {
      // yyyy-mm-dd 형식에 맞게 포맷
      let val = value.replace(/\D/g, '');
      const original = value.replace(/\D/g, '').length;
      let conversion = '';
      for (let i = 0; i < 2; i++) {
        if (val.length > 4 && i === 0) {
          conversion += val.substr(0, 4) + '-';
          val = val.substr(4);
        } else if (original > 6 && val.length > 2 && i === 1) {
          conversion += val.substr(0, 2) + '-';
          val = val.substr(2);
        }
      }
      conversion += val;
      formData.birth_date = conversion;
    },
  );

  // 백엔드에서 보낸 에러 메시지가 있으면 모달로 띄움
  watch(
    () => usePage().props.flash.message,
    (newVal) => {
      if (newVal) {
        isSendVerificationCode.value = false;
        formData.student_phone_id = 0;
        systemStore.setModalState({
          show: true,
          message: newVal[1],
        });
      }
    },
  );
</script>
