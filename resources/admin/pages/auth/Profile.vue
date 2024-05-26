<script setup lang="ts">
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import { Head, useForm, usePage } from '@inertiajs/vue3';

  import { getAssetPath } from '@/admin/core/helpers/assets';
  import { ref, onMounted } from 'vue';
  import { ErrorMessage, Field, Form as VForm } from 'vee-validate';
  import * as Yup from 'yup';
  import Swal from 'sweetalert2/dist/sweetalert2.js';
  import yupKo from 'yup-locale-ko';

  Yup.setLocale(yupKo);

  import InputLabel from '@/admin/components/InputLabel.vue';

  const submitButton = ref<HTMLButtonElement | null>(null);

  const page = usePage();

  const avatarUrl = ref(page.props.auth.user.avatar);

  const profileDetailsValidator = Yup.object().shape({
    name: Yup.string().required().max(10).label('이름'),
    /*
  password: Yup.string().min(4).required().label('비밀번호'),
  confirmpassword: Yup.string().
      min(4).
      required().
      oneOf([Yup.ref('password'), null], '비밀번호가 일치하지 않습니다').
      label('비밀번호 확인'),
  */
  });

  const form = useForm({
    _method: 'put',
    avatar: '',
    remove_avatar: false,
    name: page.props.auth.user.name,
  });

  const saveChange = () => {
    submitButton.value!.disabled = true;
    // Activate indicator
    submitButton.value!.setAttribute('data-kt-indicator', 'on');

    form.post(route('admin.profile.update'), {
      forceFormData: true,
      onSuccess: () => {
        Swal.fire({
          text: '성공',
          icon: 'success',
          confirmButtonText: 'Ok',
          buttonsStyling: false,
          heightAuto: false,
          customClass: {
            confirmButton: 'btn btn-light-primary',
          },
        }).then(() => {
          submitButton.value!.disabled = false;
          submitButton.value?.removeAttribute('data-kt-indicator');
        });
      },
      onError: () => {
        //Deactivate indicator
        submitButton.value?.removeAttribute('data-kt-indicator');
        // eslint-disable-next-line
        submitButton.value!.disabled = false;
      },
    });
  };

  const removeImage = () => {
    form.remove_avatar = true;
    avatarUrl.value = '/media/avatars/blank.png';
  };
  const onChangeAvatar = (event) => {
    const files = event.target?.files;
    if (files.length > 0) {
      const file = files[0];

      // FileReader 객체 : 웹 애플리케이션이 데이터를 읽고, 저장하게 해줌
      const reader = new FileReader();

      // load 이벤트 핸들러. 리소스 로딩이 완료되면 실행됨.
      reader.onload = (e) => {
        if (!e.target?.result || typeof e.target.result != 'string') return;
        avatarUrl.value = e.target.result;

        form.avatar = file;
      }; // ref previewImage 값 변경

      // 컨텐츠를 특정 file에서 읽어옴. 읽는 행위가 종료되면 loadend 이벤트 트리거함
      // & base64 인코딩된 스트링 데이터가 result 속성에 담김
      reader.readAsDataURL(file);
    }
  };
</script>

<template>
  <Head title="Dashboard" />

  <AdminLayout>
    <!--begin::Basic info-->
    <div class="card mb-5 mb-xl-10">
      <!--begin::Card header-->
      <div
        class="card-header border-0 cursor-pointer"
        role="button"
        aria-expanded="true"
        aria-controls="kt_account_profile_details"
      >
        <!--begin::Card title-->
        <div class="card-title m-0">
          <h3 class="fw-bold m-0">내 프로필 상세</h3>
        </div>
        <!--end::Card title-->
      </div>
      <!--begin::Card header-->

      <!--begin::Content-->
      <div id="kt_account_profile_details" class="collapse show">
        <!--begin::Form-->
        <VForm
          id="kt_account_profile_details_form"
          class="form"
          novalidate
          :validation-schema="profileDetailsValidator"
          @submit="saveChange()"
        >
          <!--begin::Card body-->
          <div class="card-body border-top p-9">
            <!--begin::Input group-->
            <div class="row mb-6">
              <!--begin::Label-->

              <InputLabel value="프로필사진" />
              <!--end::Label-->

              <!--begin::Col-->
              <div class="col-lg-8">
                <!--begin::Image input-->
                <div
                  class="image-input image-input-outline"
                  data-kt-image-input="true"
                  :style="{
                    backgroundImage: `url(${getAssetPath('/media/avatars/blank.png')})`,
                  }"
                >
                  <!--begin::Preview existing avatar-->
                  <div
                    class="image-input-wrapper w-125px h-125px"
                    :style="`background-image: url('${avatarUrl}')`"
                  ></div>
                  <!--end::Preview existing avatar-->

                  <!--begin::Label-->
                  <label
                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="change"
                    data-bs-toggle="tooltip"
                    title="Change avatar"
                  >
                    <i class="bi bi-pencil-fill fs-7"></i>

                    <!--begin::Inputs-->
                    <input type="file" name="avatar" accept=".png, .jpg, .jpeg" @change="onChangeAvatar" />
                    <input type="hidden" name="avatar_remove" />
                    <!--end::Inputs-->
                  </label>
                  <!--end::Label-->

                  <!--begin::Remove-->
                  <span
                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="remove"
                    data-bs-toggle="tooltip"
                    title="Remove avatar"
                    @click="removeImage()"
                  >
                    <i class="bi bi-x fs-2"></i>
                  </span>
                  <!--end::Remove-->
                </div>
                <!--end::Image input-->

                <!--begin::Hint-->
                <div class="form-text">허용이미지 타입 : png, jpg, jpeg.</div>
                <!--end::Hint-->
              </div>
              <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-6">
              <!--begin::Label-->
              <InputLabel for="fname" value="이름" />
              <!--end::Label-->

              <!--begin::Col-->
              <div class="col-lg-8">
                <!--begin::Row-->
                <div class="row">
                  <!--begin::Col-->
                  <div class="col-lg-6 fv-row">
                    <Field
                      v-model="form.name"
                      type="text"
                      name="name"
                      class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                      placeholder="이름"
                    />
                    <div class="fv-plugins-message-container">
                      <div class="fv-help-block">
                        <ErrorMessage name="name" />
                      </div>
                    </div>
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Row-->
              </div>
              <!--end::Col-->
            </div>
            <!--end::Input group-->
          </div>
          <!--end::Card body-->

          <!--begin::Actions-->
          <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button id="kt_sign_in_submit" ref="submitButton" type="submit" class="btn btn-primary">
              <span class="indicator-label"> 저장하기 </span>
              <span class="indicator-progress">
                잠시만 기다려주세요...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
              </span>
            </button>
          </div>
          <!--end::Actions-->
        </VForm>
        <!--end::Form-->
      </div>
      <!--end::Content-->
    </div>
    <!--end::Basic info-->
  </AdminLayout>
</template>
