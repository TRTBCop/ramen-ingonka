<script setup lang="ts">
  import { ref } from 'vue';
  import { ErrorMessage, Field, Form as VForm } from 'vee-validate';
  import { useAuthStore, type User } from '@/admin/stores/auth';
  import * as Yup from 'yup';
  import yupKo from 'yup-locale-ko';

  import { Head, Link, useForm } from '@inertiajs/vue3';
  import AuthLayout from '@/admin/layouts/AuthLayout.vue';

  Yup.setLocale(yupKo);

  const store = useAuthStore();

  const submitButton = ref<HTMLButtonElement | null>(null);

  //Create form validation object
  const login = Yup.object().shape({
    access_id: Yup.string().required().label('아이디'),
    password: Yup.string().min(4).required().label('비밀번호'),
  });

  const form = useForm({
    access_id: '',
    password: '',
    remember: false,
  });
  //Form submit function
  const onSubmitLogin = async (values: any) => {
    values = values as User;

    if (submitButton.value) {
      // eslint-disable-next-line
      submitButton.value!.disabled = true;

      // Activate indicator
      submitButton.value.setAttribute('data-kt-indicator', 'on');
    }

    form.post(route('admin.login'), {
      onError: () => {
        //Deactivate indicator
        submitButton.value?.removeAttribute('data-kt-indicator');
        // eslint-disable-next-line
        submitButton.value!.disabled = false;
      },
    });
  };
</script>

<template>
  <AuthLayout>
    <Head title="관리자로그인" />

    <!--begin::Wrapper-->
    <div class="w-lg-500px p-10">
      <!--begin::Form-->
      <VForm class="form w-100" id="kt_login_signin_form" @submit="onSubmitLogin" :validation-schema="login">
        <!--begin::Heading-->
        <div class="text-center mb-10">
          <!--begin::Title-->
          <h1 class="text-dark mb-3">관리자 로그인</h1>
          <!--end::Title-->
        </div>
        <!--begin::Heading-->
        <div v-if="Object.keys($page.props.errors).length" class="mb-10 bg-light-info p-8 rounded">
          <div class="text-info">
            <template v-for="(item, i) in $page.props.errors" :key="i">
              {{ item }}
            </template>
          </div>
        </div>

        <div class="mb-4 font-medium text-sm text-green-600"> </div>

        <!--begin::Input group-->
        <div class="fv-row mb-10">
          <!--begin::Label-->
          <label class="form-label fs-6 fw-bold text-dark">아이디</label>
          <!--end::Label-->

          <!--begin::Input-->
          <Field
            tabindex="1"
            class="form-control form-control-lg form-control-solid"
            type="text"
            name="access_id"
            v-model="form.access_id"
            autocomplete="off"
          />
          <!--end::Input-->
          <div class="fv-plugins-message-container">
            <div class="fv-help-block">
              <ErrorMessage name="access_id" />
            </div>
          </div>
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-10">
          <!--begin::Wrapper-->
          <div class="d-flex flex-stack mb-2">
            <!--begin::Label-->
            <label class="form-label fw-bold text-dark fs-6 mb-0">비밀번호</label>
            <!--end::Label-->
          </div>
          <!--end::Wrapper-->

          <!--begin::Input-->
          <Field
            tabindex="2"
            class="form-control form-control-lg form-control-solid"
            type="password"
            name="password"
            v-model="form.password"
            autocomplete="off"
          />
          <!--end::Input-->
          <div class="fv-plugins-message-container">
            <div class="fv-help-block">
              <ErrorMessage name="password" />
            </div>
          </div>
        </div>
        <!--end::Input group-->

        <!--begin::Actions-->
        <div class="text-center">
          <!--begin::Submit button-->
          <button
            tabindex="3"
            type="submit"
            ref="submitButton"
            id="kt_sign_in_submit"
            class="btn btn-lg btn-primary w-100 mb-5"
          >
            <span class="indicator-label"> 로그인 </span>

            <span class="indicator-progress">
              로그인중입니다 잠시만기다리세요...
              <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
          <!--end::Submit button-->
        </div>
        <!--end::Actions-->
      </VForm>
      <!--end::Form-->
    </div>
    <!--end::Wrapper-->
  </AuthLayout>
</template>
