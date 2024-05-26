<template>
  <div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 cursor-pointer" role="button" aria-expanded="true">
      <div class="card-title m-0">
        <h3 class="fw-bold m-0">{{ $page.props.page.title }}</h3>
      </div>
    </div>
    <div class="card-body border-top p-9">
      <el-form
        ref="formRef"
        :rules="rules"
        :model="form"
        class="form"
        status-icon
        label-width="100px"
        @submit.prevent="submitAction"
      >
        <el-form-item label="대표이미지">
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
              <div class="image-input-wrapper w-125px h-125px" :style="`background-image: url('${logoUrl}')`"></div>
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
                <input type="file" name="avatar" accept=".png, .jpg, .jpeg" @change="onChangeLogo" />
                <input type="hidden" name="logo_remove" />
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
        </el-form-item>

        <el-form-item label="아이디" prop="access_id">
          <el-input v-model="form.access_id" placeholder="아이디" />
        </el-form-item>

        <el-form-item label="비밀번호" prop="password">
          <el-input v-model="form.password" placeholder="입력 후 저장 시 비밀번호가 저장됩니다" />
        </el-form-item>

        <el-form-item label="이름" prop="name">
          <el-input v-model="form.name" placeholder="입력 후 저장 시 비밀번호가 저장됩니다" />
        </el-form-item>

        <el-form-item label="권한" prop="roles">
          <el-checkbox-group v-model="form.roles">
            <el-checkbox
              v-for="(value, key) in pageData.props.roles"
              :key="key"
              :label="value.value"
              :checked="form.roles.includes(value.value)"
            >
              {{ value.text }}</el-checkbox
            >
          </el-checkbox-group>
        </el-form-item>

        <div class="text-end">
          <el-button type="primary" @click="submitAction">저장</el-button>
          <el-button v-if="!isCreatePage" type="danger" @click="deleteAdmin">삭제</el-button>
          <el-button type="default" @click="router.get(route('admin.admins.index'))"> 목록 </el-button>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, computed } from 'vue';
  import { PageProps } from '@/admin/types';
  import { router, useForm, usePage } from '@inertiajs/vue3';
  import { ElMessageBox, FormRules } from 'element-plus';
  import { getAssetPath } from '@/admin/core/helpers/assets';

  const pageData = usePage<
    PageProps<{
      admin?: any;
      roles: {
        text: string;
        name: string;
        value: string;
      }[];
    }>
  >();

  const formRef = ref<null | HTMLFormElement>(null);

  /** 등록 페이지이면 true */
  const isCreatePage = computed(() => !pageData.props?.admin);

  const logoUrl = ref(pageData.props?.admin ? pageData.props.admin.avatar : '/media/avatars/blank.png');

  const rules = ref<FormRules>({
    name: [{ required: true, message: '필수입력입니다' }],
    access_id: [{ required: true, message: '필수입력입니다' }],
  });

  const initFormData = () => {
    let result: {
      _method: string;
      access_id: string;
      password: string;
      name: string;
      roles: string[];
      remove_avatar: boolean;
      avatar: string;
    } = {
      _method: 'post',
      access_id: '',
      password: '',
      name: '',
      roles: [],
      remove_avatar: false,
      avatar: '',
    };

    // 새로 만드는 페이지가 아닐 경우 초반 데이터 세팅
    if (!isCreatePage.value) {
      result = {
        ...result,
        _method: 'put',
        access_id: pageData.props.admin.access_id,
        password: '',
        name: pageData.props.admin.name,
        roles: pageData.props.admin.roles.map((data) => data.name),
        avatar: pageData.props.admin.avatar,
      };
    }

    return result;
  };

  const removeImage = () => {
    form.remove_avatar = true;
    logoUrl.value = '/media/avatars/blank.png';
  };

  const onChangeLogo = (event) => {
    const files = event.target?.files;
    if (files.length > 0) {
      const file = files[0];

      // FileReader 객체 : 웹 애플리케이션이 데이터를 읽고, 저장하게 해줌
      const reader = new FileReader();

      // load 이벤트 핸들러. 리소스 로딩이 완료되면 실행됨.
      reader.onload = (e) => {
        if (!e.target?.result || typeof e.target.result != 'string') return;
        logoUrl.value = e.target.result;

        form.avatar = file;
      }; // ref previewImage 값 변경

      // 컨텐츠를 특정 file 에서 읽어옴. 읽는 행위가 종료되면 loadend 이벤트 트리거함
      // & base64 인코딩된 스트링 데이터가 result 속성에 담김
      reader.readAsDataURL(file);
    }
  };

  const form = useForm(initFormData());

  const submitAction = () => {
    if (!formRef.value) return;

    formRef.value.validate((valid: boolean) => {
      if (valid) {
        const action = isCreatePage.value
          ? route('admin.admins.store')
          : route('admin.admins.update', pageData.props.admin.id);

        form.post(action, {
          onSuccess: () => {
            // 성공 처리
          },
          onError: (error) => {
            // 에러 처리
          },
        });
      }
    });
  };

  const deleteAdmin = () => {
    ElMessageBox.confirm('정말 삭제하시겠습니까?', '', {
      type: 'warning',
    }).then(
      () => {
        router.delete(route('admin.admins.destroy', pageData.props.admin.id));
      },
      () => {
        //
      },
    );
  };
</script>
