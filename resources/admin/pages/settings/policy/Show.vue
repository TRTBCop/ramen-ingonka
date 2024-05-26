<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >
  <AdminLayout>
    <div class="card mb-5 mb-xl-10">
      <div class="card-header border-0 cursor-pointer" role="button" aria-expanded="true">
        <div class="card-title m-0">
          <h3 class="fw-bold m-0">{{ $page.props.page.title }}</h3>
        </div>
      </div>
      <div class="card-body border-top p-9">
        <el-form
          ref="formRef"
          :model="form"
          class="form"
          label-width="130px"
          status-icon
          @submit.prevent="submitAction"
        >
          <el-form-item label="이용약관">
            <div class="w-100">
              <froala
                v-model:value="form.agree"
                height="300px"
                tag="textarea"
                :config="{ ...froalaEditorMathConfig(), heightMax: 400 }"
              />
            </div>
          </el-form-item>

          <el-form-item label="개인정보 처리방침">
            <div class="w-100">
              <froala
                v-model:value="form.privacy"
                height="300px"
                tag="textarea"
                :config="{ ...froalaEditorMathConfig(), heightMax: 400 }"
              />
            </div>
          </el-form-item>

          <el-form-item label="마케팅 동의">
            <div class="w-100">
              <froala
                v-model:value="form.marketing"
                height="300px"
                tag="textarea"
                :config="{ ...froalaEditorMathConfig(), heightMax: 400 }"
              />
            </div>
          </el-form-item>

          <div class="text-end">
            <el-button type="primary" @click="submitAction">저장</el-button>
          </div>
        </el-form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
  import { ref } from 'vue';
  import { PageProps } from '@/admin/types';
  import { Head, useForm, usePage } from '@inertiajs/vue3';
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import '@vueup/vue-quill/dist/vue-quill.snow.css';
  import { froalaEditorMathConfig } from '@/admin/core/plugins/froalaEditor';

  const pageData = usePage<
    PageProps<{
      agree: string;
      privacy: string;
      marketing: string;
    }>
  >();

  const formRef = ref<null | HTMLFormElement>(null);

  const form = useForm({
    agree: pageData.props.agree,
    privacy: pageData.props.privacy,
    marketing: pageData.props.marketing,
  });

  const submitAction = () => {
    if (!formRef.value) return;

    formRef.value.validate((valid: boolean) => {
      if (valid) {
        form.put(route('admin.settings.policy.update'), {
          onSuccess: () => {
            // 성공 처리
          },
        });
      }
    });
  };
</script>
