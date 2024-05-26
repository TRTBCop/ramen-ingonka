<template>
  <MyLayout page-name="마이페이지">
    <h3>계정 정보</h3>
    <div class="input__box mb-0">
      <dl class="mypage__info">
        <dt>학생이름</dt>
        <dd>{{ formData.name }}</dd>
        <template v-if="formData.access_id">
          <dt>아이디</dt>
          <dd>{{ formData.access_id }}</dd>
        </template>
        <dt>가입일</dt>
        <dd>{{ dayjs(formData.created_at).format('YYYY-MM-DD') }}</dd>
      </dl>
    </div>

    <template v-if="isB2cUser()">
      <a v-if="isB2cUser()" class="btn__withdraw" href="javascript:;" @click="handleWithdraw">회원 탈퇴</a>

      <h3>학생 정보</h3>
      <div class="input__box">
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
        <div class="input--row">
          <AppFormInput
            :value="
              userSchoolInfo ? `${userSchoolInfo.step} ${userSchoolInfo.grade}학년 ${userSchoolInfo.term}학기` : ''
            "
            name="grade_term"
            icon="fa-regular fa-user"
            placehoder="학년 학기를 선택해 주세요."
            readonly
          />

          <button class="btn--gray" @click="showGradeTermModifyModal">변경하기</button>
        </div>
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

      <!-- sns 회원가입이 아닐 경우에만 수정 가능 -->
      <template v-if="formData.access_id">
        <h3>비밀번호 변경</h3>
        <div class="input__box">
          <AppFormInput
            v-model:value="formData.password"
            v-model:error-messages="errorMessages"
            name="password"
            icon="fa-regular fa-lock"
            :placehoder="t('placehoder.new_password')"
            type="password"
            :rules="rules.password"
          />
          <AppFormInput
            v-model:value="formData.c_password"
            v-model:error-messages="errorMessages"
            name="c_password"
            icon="fa-regular fa-lock"
            :placehoder="t('placehoder.c_new_password')"
            type="password"
            :rules="rules.c_password"
          />

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
      </template>

      <h3>학부모 정보</h3>
      <div class="input__box">
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
            <p v-if="!Boolean(formData.student_phone_id)" class="timer">{{ phoneLeftMinutes }}:{{ phoneLeftSecond }}</p>
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

      <h3>마케팅 활용 동의</h3>

      <div class="input__box">
        <div class="checkbox_wrap checkbox--sm">
          <label class="row">
            <input v-model="formData.marketing_consent" type="checkbox" />
            동의합니다.
          </label>
          <button class="is--size5 txt--bold" @click="showPolicyModal('마케팅 활용 동의', pageData.props.marketing)"
            >약관 보기</button
          >
        </div>
      </div>

      <h3>SNS 연동</h3>
      <div class="input__box row">
        <span class="row"> <img src="@/assets/img/math/ico_naver.svg" alt="" />네이버</span>
        <button class="is--size5" @click="handleUpdateSocial('naver')">{{
          userData.naver_id ? '연동 해제' : '연동 하기'
        }}</button>
      </div>
      <div class="input__box row">
        <span class="row"> <img src="@/assets/img/math/ico_kakao.svg" alt="" />카카오톡</span>
        <button class="is--size5" @click="handleUpdateSocial('kakao')">{{
          userData.kakao_id ? '연동 해제' : '연동 하기'
        }}</button>
      </div>
    </template>

    <template v-else>
      <h3 style="margin-top: 2rem">학년/학기 변경</h3>
      <div class="input__box">
        <div class="input--row">
          <AppFormInput
            :value="
              userSchoolInfo ? `${userSchoolInfo.step} ${userSchoolInfo.grade}학년 ${userSchoolInfo.term}학기` : ''
            "
            name="grade_term"
            icon="fa-regular fa-user"
            placehoder="학년 학기를 선택해 주세요."
            readonly
          />

          <button class="btn--gray" @click="showGradeTermModifyModal">변경하기</button>
        </div>
      </div>
    </template>

    <div class="btns">
      <button class="btn--sub" @click="goMainPage">메인으로</button>
      <button v-if="isB2cUser()" class="btn--brand" @click="handleUpdateMyInfo">저장하기</button>
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
  </MyLayout>

  <GradeTermModifyModal v-model:is-show="isShowGradeTermModifyModal" />
</template>

<script setup lang="ts">
  import { useI18n } from 'vue-i18n';
  import { watch, reactive, ref, computed } from 'vue';
  import MyLayout from '@/app/layouts/MyLayout.vue';
  import { useForm, usePage } from '@inertiajs/vue3';
  import { PageProps } from '@/app/types/pageData';
  import { AppFormRules, allValidateFormField, useAppFormRules, validateFormField } from '@/app/core/helpers/validator';
  import AppFormInput from '@/app/components/AppFormInput.vue';
  import { useVerifyPhoneCode } from '@/app/core/helpers/verifyPhoneCode';
  import { getUserData, getUserSchoolInfo, isB2cUser, isFreeExpired } from '@/app/core/helpers/userHelper';
  import { some } from 'lodash';
  import { dayjs } from 'element-plus';
  import GradeTermModifyModal from '@/app/components/modals/GradeTermModifyModal.vue';
  import { useSystemStoreWithOut } from '@/app/stores/modules/system';
  import { goMainPage, goWithdrawPage } from '@/app/core/helpers/routerHelper';

  const { t } = useI18n();

  const { birthDateRules, phoneRules, parentsNameRules, parentsPhoneRules, passwordRules, cPasswordRules } =
    useAppFormRules();

  const systemStore = useSystemStoreWithOut();

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

  const userData = computed(() => getUserData());

  const userSchoolInfo = getUserSchoolInfo();

  const isSocialLogin = ref(false);

  const formData = useForm(() => {
    let result: {
      _method: string;
      access_id: string;
      password: string;
      c_password: string;
      student_phone_id: number;
      name: string;
      phone: string;
      birth_date: string;
      parents_name: string;
      parents_phone: string;
      marketing_consent: boolean;
      code: string;
      kakao_id?: string | null;
      naver_id?: string | null;
      created_at: string;
    } = {
      _method: 'patch',
      access_id: userData.value.access_id,
      password: '',
      c_password: '',
      student_phone_id: 0,
      name: userData.value.name,
      phone: userData.value.phone,
      birth_date: userData.value.birth_date,
      parents_name: userData.value.parents_name,
      parents_phone: userData.value.parents_phone,
      marketing_consent: Boolean(userData.value.marketing_consent),
      code: '',
      created_at: userData.value.created_at,
    };

    if (userData.value.kakao_id) {
      result.kakao_id = pageData.props.kakao_id;
      isSocialLogin.value = true;
    }

    if (userData.value.naver_id) {
      result.naver_id = pageData.props.naver_id;
      isSocialLogin.value = true;
    }

    return result;
  });

  const rules = reactive<AppFormRules>({
    password: passwordRules(false),
    c_password: cPasswordRules(formData),
    birth_date: birthDateRules(),
    phone: phoneRules(),
    parents_name: parentsNameRules(),
    parents_phone: parentsPhoneRules(),
    code: [
      {
        validator: (v: string) => /^[0-9]+$/.test(v),
        message: t('valid.code.onlyNumber'),
      },
      {
        validator: () => Boolean(formData.student_phone_id),
        message: t('valid.code.required'),
      },
    ],
  });

  const errorMessages = ref<{ [key: string]: string[] }>({});

  /** 학년 학기 변경 모달 여부 */
  const isShowGradeTermModifyModal = ref(false);

  function showGradeTermModifyModal() {
    isShowGradeTermModifyModal.value = true;
  }

  const policyModalState = reactive({
    isShow: false,
    data: {
      title: '',
      content: '',
    },
  });

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
        errorMessages.value.code = validateFormField(formData.code, rules['code']);
      } else {
        systemStore.setModalState({
          show: true,
          message: t('system.verificationCodeCheck.failed'),
        });
        formData.student_phone_id = 0;
      }
    });
  };

  const handleUpdateMyInfo = () => {
    const { success, newErrorMessages } = allValidateFormField(formData, rules);

    errorMessages.value = newErrorMessages;

    if (success) {
      formData.patch(route('app.my.profile.update'));
      formData.password = '';
      formData.c_password = '';
      formData.code = '';
    }
  };

  /**
   * 학생 서비스 기간 지났는지 체크
   */
  const isPassedStudentEndDate = () => {
    let endServiceDate = dayjs().format('YYYY-MM-DD') > (userData.value.service_end_date ?? '');
    //서비스 기간이 없을때는 끝난걸로 처리
    if (!userData.value.service_end_date) {
      endServiceDate = true;
    }

    // 무료체험 기간이 종료 되지 않았으면 기간 남은걸로 처리
    if (!isFreeExpired()) {
      endServiceDate = false;
    }

    return endServiceDate;
  };

  const handleWithdraw = () => {
    if (!isPassedStudentEndDate()) {
      systemStore.setModalState({
        show: true,
        message: `남은 이용기간 사용후 회원 탈퇴가 가능합니다!`,
      });
      return;
    }

    if (userData.value.academy_id) {
      systemStore.setModalState({
        show: true,
        message: `b2c 회원만 탈퇴 가능합니다!`,
      });
      return;
    }

    goWithdrawPage();
  };

  const handleUpdateSocial = (driver: string) => {
    window.location.href = route('app.my.social.update', {
      driver,
    });
  };

  // 비밀번호 입력시 비밀번호 확인 rules 초기화
  watch(
    () => formData.password,
    (newVal) => {
      if (newVal && !some(rules.c_password, ['required', true])) {
        rules.c_password.unshift({ required: true, message: t('valid.c_password.required') });
      } else if (!newVal) {
        rules.c_password.shift();
      }
    },
  );

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
