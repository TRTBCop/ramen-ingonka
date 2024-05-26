<template>
  <FindLayout page-type="password" :page-step="pageStep">
    <div class="box__result">
      <template v-if="isFindStudent">
        <strong>{{ pageData.props.access_id }}</strong>
        <p>가입일: {{ pageData.props.created_date }}</p>
      </template>

      <p v-else>{{ t('system.findRegister.notFound') }}</p>
    </div>

    <!-- ################ 핸드폰 인증 ################ -->
    <div v-if="pageStep === PageStep.PHONE && isFindStudent" class="panel_group">
      <div class="input--row">
        <AppFormInput
          v-model:value="formData.parents_phone"
          v-model:error-messages="errorMessages"
          name="parents_phone"
          icon="fa-regular fa-mobile-notch"
          :placehoder="t('placehoder.parents_phone')"
          maxlength="11"
          :disabled="true"
          :rules="rules.parents_phone"
        />
        <button
          class="btn--gray"
          :disabled="isSendVerificationCode"
          @click="clickVerificationCodeSend(formData.parents_phone)"
          >인증번호 발송</button
        >
      </div>
      <AppFormInput
        v-model:value="formData.code"
        v-model:error-messages="errorMessages"
        name="code"
        icon="fa-regular fa-hashtag"
        :placehoder="t('placehoder.code')"
        :disabled="Boolean(formData.student_phone_id)"
        :rules="rules.code"
      >
        <p class="timer">{{ phoneLeftMinutes }}:{{ phoneLeftSecond }}</p>
      </AppFormInput>

      <ul class="box__error">
        <li v-for="(message, i) in errorMessages.parents_phone" :key="message + i">
          <span class="">{{ message }}</span>
        </li>
        <li v-for="(message, i) in errorMessages.code" :key="message + i">
          <span class="">{{ message }}</span>
        </li>
      </ul>
    </div>
    <!-- ################ 비밀번호 재설정 ################ -->
    <div v-if="pageStep === PageStep.RESET" class="panel_group">
      <AppFormInput
        v-model:value="formData.password"
        v-model:error-messages="errorMessages"
        name="password"
        icon="fa-regular fa-lock"
        :placehoder="t('placehoder.password')"
        :rules="rules.password"
        :type="showPassword ? 'text' : 'password'"
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
        :rules="rules.c_password"
        :type="showCheckPassword ? 'text' : 'password'"
      >
        <button class="btn_pw" @click="showCheckPassword = !showCheckPassword">
          <font-awesome-icon icon="fa-regular fa-eye" />
        </button>
      </AppFormInput>

      <ul class="box__error">
        <li v-for="(message, i) in errorMessages.password" :key="message + i">
          <span class="">{{ message }}</span>
        </li>
        <li v-for="(message, i) in errorMessages.c_password" :key="i">
          <span class="">{{ message }}</span>
        </li>
      </ul>
    </div>

    <div class="panel__btns">
      <button v-if="isFindStudent" class="btn--brand" @click="handleNextButtonAction">다음</button>

      <template v-else>
        <button class="btn--sub" @click="goRegisterPage">회원가입</button>
        <button class="btn--brand" @click="goFindPasswordPage">다시 찾기</button>
      </template>
    </div>
  </FindLayout>
</template>

<script setup lang="ts">
  import { ref, reactive, computed, watch } from 'vue';
  import { PageProps } from '@/app/types/pageData';
  import { useForm, usePage } from '@inertiajs/vue3';
  import FindLayout from '@/app/layouts/FindLayout.vue';
  import { useI18n } from 'vue-i18n';
  import { allValidateFormField, useAppFormRules, validateFormField } from '@/app/core/helpers/validator';
  import AppFormInput from '@/app/components/AppFormInput.vue';
  import { useVerifyPhoneCode } from '@/app/core/helpers/verifyPhoneCode';
  import { useSystemStoreWithOut } from '@/app/stores/modules/system';
  import { goRegisterPage, goFindPasswordPage } from '@/app/core/helpers/routerHelper';

  const { t } = useI18n();

  interface Page extends PageProps {
    access_id: string;
    created_date: string;
    student_id: string;
    parents_phone: string;
    route_name: string;
  }

  const pageData = usePage<Page>();

  const systemStore = useSystemStoreWithOut();

  enum PageStep {
    PHONE = 2,
    RESET,
  }

  const { parentsPhoneRules, codeRules, passwordRules, cPasswordRules } = useAppFormRules();

  const {
    handleVerificationCodeSend,
    isSendVerificationCode,
    handleVerificationCodeCheck,
    phoneLeftSecond,
    phoneLeftMinutes,
    phoneTimer,
  } = useVerifyPhoneCode();

  const showPassword = ref(false);
  const showCheckPassword = ref(false);

  const pageStep = ref(PageStep.PHONE);

  /** 아이디 찾기 성공 여부 */
  const isFindStudent = computed(() => Boolean(pageData.props.student_id));

  /** 핸드폰 인증용 폼 */
  const formData = useForm({
    parents_phone: pageData.props.parents_phone,
    code: '',
    student_id: pageData.props.student_id,
    student_phone_id: 0,
    password: '',
    c_password: '',
  });

  const rules = reactive<{
    [key: string]: {
      required?: boolean;
      message: string;
      validator?: (v: string) => boolean;
    }[];
  }>({
    parents_phone: parentsPhoneRules(),
    code: codeRules(formData),
    password: passwordRules(),
    c_password: cPasswordRules(formData),
  });

  const errorMessages = ref<{ [key: string]: string[] }>({});

  /** 핸드폰 인증 번호 발송 버튼 클릭 시 */
  const clickVerificationCodeSend = async (parentsPhone: string) => {
    handleVerificationCodeSend(parentsPhone);
  };

  // 부모님 휴대폰 번호는 입력 할 때 마다 유효성 검사를 바로 바로 하게 끔 처리
  watch(
    () => formData.parents_phone,
    () => {
      errorMessages.value['parents_phone'] = validateFormField(formData.parents_phone, rules['parents_phone']);
    },
  );

  const handleNextButtonAction = async () => {
    if (pageStep.value === PageStep.PHONE) {
      if (phoneTimer.value <= 0) {
        systemStore.setModalState({
          show: true,
          message: t('system.verificationCodeCheck.timeout'),
        });
        return;
      }

      await handleVerificationCodeCheck(formData.parents_phone, formData.code, (success, student_pohne_id) => {
        if (success && student_pohne_id) {
          formData.student_phone_id = student_pohne_id;
          pageStep.value++;
        } else {
          systemStore.setModalState({
            show: true,
            message: t('system.verificationCodeCheck.failed'),
          });
          formData.student_phone_id = 0;
        }
      });
    } else {
      const { success, newErrorMessages } = allValidateFormField(formData, rules);

      errorMessages.value = newErrorMessages;

      if (success) {
        formData.post(route('app.register.find-password-reset'));
      }
    }
  };
</script>
