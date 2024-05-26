<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >

  <AdminLayout>
    <Navbar />
    <div class="card mt-5">
      <div class="card-body border-top p-9">
        <el-form ref="formRef" :rules="rules" :model="form" label-width="70px">
          <h3>기본 정보</h3>
          <el-divider />
          <el-form-item label="속성" prop="element">
            <el-select v-model="form.element" placeholder="속성 선택">
              <el-option
                v-for="(value, key) in pageData.props.config.dbcode.curricula.element"
                :key="key"
                :label="value"
                :value="key"
              />
            </el-select>
          </el-form-item>

          <el-form-item label="이름" prop="name">
            <el-input v-model="form.name" placeholder="이름" />
          </el-form-item>

          <el-form-item label="등록일시" prop="created_at">
            <span>{{ createdAtToDateString(pageData.props.curriculum.created_at) }}</span>
          </el-form-item>

          <div class="mt-4 text-end">
            <el-button type="primary" @click="submitAction">저장</el-button>
            <el-button type="danger" @click="deleteSubscription">삭제</el-button>
          </div>
        </el-form>
      </div>
    </div>
    <GoListButton :list-url="route('admin.curricula.index')" />
  </AdminLayout>
</template>

<script setup lang="ts">
  import { ref } from 'vue';
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import { Head, router, useForm, usePage } from '@inertiajs/vue3';
  import Navbar from './components/Navbar.vue';
  import { Curriculum } from '@/admin/api/model/curriculum';
  import { deleteCurriculum, updateCurriculum } from '@/admin/api/curriculum';
  import { PageProps } from '@/admin/types';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import GoListButton from '@/admin/components/admin/GoListButton.vue';
  import { commonDeleteConfirm } from '@/admin/core/helpers/commonHelpers';
  import { FormRules } from 'element-plus';

  interface Page extends PageProps {
    config: {
      dbcode: Pick<Dbcode, 'curricula'>;
    };
    curriculum: Curriculum;
  }

  const pageData = usePage<Page>();

  const rules = ref<FormRules>({
    name: [{ required: true, message: '필수입력입니다' }],
  });

  const form = useForm({
    _method: 'put',
    name: pageData.props.curriculum.name,
    element: pageData.props.curriculum.element ? String(pageData.props.curriculum.element) : null,
  });

  const formRef = ref();

  const submitAction = () => {
    formRef.value.validate((valid: boolean) => {
      if (valid) {
        handleUpdateCurriculum();
      }
    });
  };

  /**
   * 날짜를 받아서 시간을 자른 날짜만 반환
   * @param date 2020-11-11 00:14:57 형식의 날짜 데이터
   */
  const createdAtToDateString = (date: string) => {
    return new Date(date).toISOString().split('T')[0];
  };

  const handleUpdateCurriculum = async () => {
    try {
      const { data } = await updateCurriculum({
        id: pageData.props.curriculum.id,
        name: form.name,
        element: Number(form.element),
        parent_id: pageData.props.curriculum.parent_id,
      });

      if (!data.success) throw new Error();

      router.get(route('admin.curricula.show', pageData.props.curriculum.id));
    } catch (err) {
      console.log(err);
    }
  };

  const handelDeleteCurriculum = async () => {
    try {
      const { data } = await deleteCurriculum(pageData.props.curriculum.id);

      if (!data.success) throw new Error();

      router.get(route('admin.curricula.index'));
    } catch (err) {
      console.log(err);
    }
  };

  const deleteSubscription = () => {
    commonDeleteConfirm(() => handelDeleteCurriculum());
  };
</script>
