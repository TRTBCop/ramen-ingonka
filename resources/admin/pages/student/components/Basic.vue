<template>
  <div class="card">
    <div class="card-body border-top p-9">
      <el-form
        ref="formRef"
        :rules="rules"
        :model="form"
        label-width="130px"
        class="form"
        status-icon
        @submit.prevent="submit"
      >
        <!-- #################### 계정 정보 #################### -->
        <h5 class="ps-5">계정 정보</h5>

        <el-divider />

        <el-form-item label="아이디" prop="access_id">
          <el-input v-model="form.access_id" placeholder="아이디" />
        </el-form-item>

        <el-form-item label="비밀번호" prop="password">
          <el-input v-model="form.password" placeholder="입력 후 저장 시 비밀번호가 저장됩니다" />
        </el-form-item>

        <el-form-item v-if="form.naver_id || form.kakao_id" label="소셜 로그인">
          <div class="d-flex gap-2">
            <span v-if="form.kakao_id" class="badge badge-light-warning">카카오</span>
            <span v-if="form.naver_id" class="badge badge-light-success">네이버</span>
          </div>
        </el-form-item>

        <!-- #################### 개인 정보 #################### -->
        <h5 class="ps-5 mt-12">개인 정보</h5>

        <el-divider />

        <el-form-item label="대표이미지">
          <div class="col-lg-8">
            <div
              class="image-input image-input-outline"
              data-kt-image-input="true"
              :style="{
                backgroundImage: `url(${getAssetPath('media/avatars/blank.png')})`,
              }"
            >
              <div
                class="image-input-wrapper w-125px h-125px"
                :style="{
                  backgroundImage: `url('${avatarUrl}')`,
                }"
              ></div>

              <label
                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                data-kt-image-input-action="change"
                data-bs-toggle="tooltip"
                title="Change avatar"
              >
                <i class="bi bi-pencil-fill fs-7"></i>
                <input type="file" name="avatar" accept=".png, .jpg, .jpeg" @change="onChangeAvatar" />
                <input type="hidden" name="logo_remove" />
              </label>
              <span
                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                data-kt-image-input-action="remove"
                data-bs-toggle="tooltip"
                title="Remove avatar"
                @click="removeImage()"
              >
                <i class="bi bi-x fs-2"></i>
              </span>
            </div>
            <div class="form-text">허용이미지 타입 : png, jpg, jpeg.</div>
          </div>
        </el-form-item>

        <el-form-item label="학생 이름" prop="name">
          <el-input v-model="form.name" placeholder="학생 이름" />
        </el-form-item>

        <el-form-item label="생년월일" prop="birth_date">
          <el-date-picker
            v-model="form.birth_date"
            type="date"
            placeholder="생년월일"
            size="default"
            value-format="YYYY-MM-DD"
          />
        </el-form-item>

        <el-form-item label="학원명" prop="name">
          <el-select v-if="pageData.props.student.academy_id" v-model="form.academy_id" filterable placeholder="학원명">
            <el-option v-for="(item, key) in pageData.props.academies" :key="key" :label="item" :value="key" />
          </el-select>
          <template v-else>
            <el-input placeholder="없음 (B2C)" disabled></el-input>
          </template>
        </el-form-item>

        <el-form-item label="전화번호" prop="phone">
          <el-input v-model="form.phone" placeholder="전화번호" />
        </el-form-item>

        <el-form-item label="주소" prop="adress">
          <el-input v-model="form.adress" placeholder="주소" />
        </el-form-item>

        <el-form-item label="학교명" prop="school_name">
          <el-input v-model="form.school_name" placeholder="학교명" />
        </el-form-item>

        <div class="d-flex">
          <el-form-item label="학년" prop="grade">
            <el-select v-model="form.grade" placeholder="힉년">
              <el-option
                v-for="(value, key) in pageData.props.config.dbcode.students.grade"
                :key="key"
                :value="key"
                :label="value"
              />
            </el-select>
          </el-form-item>
          <el-form-item label="학기" prop="term">
            <el-select v-model="form.term" placeholder="학기">
              <el-option v-for="term in ['1', '2']" :key="term" :value="term" :label="`${term}학기`" />
            </el-select>
          </el-form-item>
        </div>

        <el-form-item label="마지막 로그인">
          <el-date-picker
            v-model="form.last_login_at"
            type="date"
            placeholder="마지막 로그인"
            size="default"
            value-format="YYYY-MM-DD"
            readonly
          />
        </el-form-item>

        <!-- #################### 학부모 정보 #################### -->
        <h5 class="ps-5 mt-12">학부모 정보</h5>

        <el-divider />

        <el-form-item label="학부모 이름" prop="parents_name">
          <el-input v-model="form.parents_name" placeholder="학부모 이름" />
        </el-form-item>

        <el-form-item label="학부모 전화번호" prop="parents_phone">
          <el-input v-model="form.parents_phone" placeholder="학부모 전화번호" />
        </el-form-item>

        <!-- #################### 서비스 정보 #################### -->
        <h5 class="ps-5 mt-12">학년/학기</h5>
        <el-divider />
        <el-form-item label="학년/학기">
          <!-- todo 학년 학기 배찌로 구분 -->
          <span
            :class="`btn btn-sm btn-light-${statusColors[form.status]} fw-bold ms-2 fs-8 py-1 px-3`"
            style="cursor: auto"
          >{{ pageData.props.config.dbcode.students.grade[pageData.props.student.grade] }} {{pageData.props.student.term}}학기</span
          >
        </el-form-item>
        
        <el-form-item label="서비스 상태">
          <span
            :class="`btn btn-sm btn-light-${statusColors[form.status]} fw-bold ms-2 fs-8 py-1 px-3`"
            style="cursor: auto"
            >{{ pageData.props.config.dbcode.students.status[form.status] }}</span
          >
        </el-form-item>

        <el-form-item label="이용권 시작일">
          <el-date-picker v-if="form.service_end_date" v-model="form.service_start_date" type="date" size="default" value-format="YYYY-MM-DD" />
          <el-text v-else tag="b">이용권이 없습니다.</el-text>
        </el-form-item>

        <el-form-item label="이용권 종료일">
          <el-date-picker v-if="form.service_end_date" v-model="form.service_end_date" type="date" size="default" value-format="YYYY-MM-DD"  />
          <el-text v-else tag="b">이용권이 없습니다.</el-text>
        </el-form-item>

        <el-form-item label="가입일">
          <el-text tag="b">{{ dayjs(form.created_at).format('YYYY-MM-DD HH:mm:ss') }}</el-text>
        </el-form-item>
        
        <!-- #################### SNS 연동 #################### -->
        <h5 class="ps-5 mt-12">SNS 연동</h5>
        <el-divider />
        <el-form-item >
          <el-radio-group v-model="radioValue" disabled class="ml-4">
            <el-radio disabled :label="0">연동없음</el-radio>
            <el-radio disabled :label="1">네이버</el-radio>
            <el-radio disabled :label="2">카카오</el-radio>
          </el-radio-group>
          
        </el-form-item>
        
        <!-- #################### SNS 연동 #################### -->
        <h5 class="ps-5 mt-12">마케팅 활용 동의</h5>
        <el-divider />
        <el-form-item >
          <el-radio-group v-model="pageData.props.student.marketing_consent" disabled class="ml-4">
            <el-radio disabled :label="1">동의</el-radio>
            <el-radio disabled :label="0">미동의</el-radio>
          </el-radio-group>
        
        </el-form-item>

        <!-- #################### 메모 #################### -->
        <h5 class="ps-5 mt-12">메모</h5>
        <el-divider />
        <el-form-item label="관리자 메모" prop="manager_memo">
          <el-input
            v-model="form.manager_memo"
            type="textarea"
            resize="none"
            placeholder="내용을 입력해 주세요."
            :autosize="{ minRows: 2, maxRows: 4 }"
          ></el-input>
        </el-form-item>
      </el-form>
    </div>
  </div>
  <div class="d-flex justify-content-between">
    <GoListButton :list-url="route('admin.students.index')" />
    <el-button class="mt-5" type="primary" @click="submit">{{ pageData.props.student ? '저장' : '등록' }}</el-button>
  </div>
</template>

<script setup lang="ts">
import {computed, ref} from 'vue';
  import { getAssetPath } from '@/admin/core/helpers/assets';
  import { useForm, usePage } from '@inertiajs/vue3';
  import { FormRules, dayjs } from 'element-plus';
  import Swal from 'sweetalert2/dist/sweetalert2.js';
  import type { PageProps } from '@/admin/types';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import { Student } from '@/admin/api/model/student';
  import GoListButton from '@/admin/components/admin/GoListButton.vue';

  interface Page extends PageProps {
    academies: { [key in number]: string };
    student: Student;
    config: {
      dbcode: Pick<Dbcode, 'students'>;
    };
  }

  const pageData = usePage<Page>();

  const avatarUrl = ref(pageData.props.student ? pageData.props.student.avatar : 'media/avatars/blank.png');
  const formRef = ref<HTMLFormElement>();

  const loading = ref(false);

  const rules = ref<FormRules>();
  
  const form = useForm({
    _method: 'put',
    remove_avatar: false,
    avatar: pageData.props.student.avatar,
    academy_id: pageData.props.student.academy_id ? String(pageData.props.student.academy_id) : null,
    name: pageData.props.student.name,
    phone: pageData.props.student.phone,
    access_id: pageData.props.student.access_id,
    password: '',
    adress: pageData.props.student.address,
    school_name: pageData.props.student.school_name,
    grade: String(pageData.props.student.grade),
    term: String(pageData.props.student.term),
    parents_name: pageData.props.student.parents_name,
    parents_phone: pageData.props.student.parents_phone,
    last_login_at: pageData.props.student.last_login_at,
    manager_memo: pageData.props.student.manager_memo || '',
    birth_date: pageData.props.student.birth_date,
    naver_id: pageData.props.student?.naver_id,
    kakao_id: pageData.props.student?.kakao_id,
    created_at: pageData.props.student.created_at,
    service_start_date: pageData.props.student.service_start_date,
    service_end_date: pageData.props.student.service_end_date,
    status: pageData.props.student.status,
  });

  
  const naverId = computed(() => form.naver_id !== null);
  const kakaoId = computed(() => form.kakao_id !== null);
  const radioValue = computed(() => {
    if (naverId.value) {
      return 1;
    } else if (kakaoId.value) {
      return 2;
    } else {
      return 0;
    }
  });
  
  

console.log('here 출력', naverId.value, kakaoId.value, radioValue.value);

  const removeImage = () => {
    form.remove_avatar = true;
    avatarUrl.value = '/media/avatars/blank.png';
  };

  const statusColors = {
    '-2': 'danger',
    '-1': 'warning',
    '0': 'dark',
    '1': 'success',
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

      // 컨텐츠를 특정 file 에서 읽어옴. 읽는 행위가 종료되면 loadend 이벤트 트리거함
      // & base64 인코딩된 스트링 데이터가 result 속성에 담김
      reader.readAsDataURL(file);
    }
  };

  const submit = () => {
    formRef.value?.validate((valid: boolean) => {
      if (valid) {
        loading.value = true;

        const action = pageData.props.student
          ? route('admin.students.update', pageData.props.student.id)
          : route('admin.students.store');

        form.post(action, {
          onSuccess: () => {
            loading.value = false;
          },
          onError: (error) => {
            Swal.fire({
              html: Object.values(error).join('<br>'),
              icon: 'error',
              confirmButtonText: 'Ok',
              buttonsStyling: false,
              heightAuto: false,
              customClass: {
                confirmButton: 'btn btn-light-primary',
              },
            }).then(() => {
              loading.value = false;
            });
          },
        });
      } else {
        loading.value = false;
        return false;
      }
    });
  };
</script>
