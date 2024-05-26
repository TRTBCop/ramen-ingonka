<template>
  <div class="card">
    <div class="card-header border-0 cursor-pointer" role="button" aria-expanded="true">
      <div class="card-title m-0">
        <h3 class="fw-bold m-0">기본정보</h3>
      </div>
    </div>

    <div class="card-body border-top p-9">
      <el-form
        ref="formRef"
        :rules="rules"
        :model="form"
        label-width="120px"
        class="form"
        status-icon
        @submit.prevent="submit"
      >
        <el-form-item label="대표이미지">
          <div class="col-lg-8">
            <div
              class="image-input image-input-outline"
              data-kt-image-input="true"
              :style="{
                backgroundImage: `url(${getAssetPath('/media/avatars/blank.png')})`,
              }"
            >
              <div class="image-input-wrapper w-125px h-125px" :style="`background-image: url('${logoUrl}')`"></div>

              <label
                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                data-kt-image-input-action="change"
                data-bs-toggle="tooltip"
                title="Change avatar"
              >
                <i class="bi bi-pencil-fill fs-7"></i>

                <input type="file" name="avatar" accept=".png, .jpg, .jpeg" @change="onChangeLogo" />
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

        <el-form-item label="학원명" prop="name">
          <el-input v-model="form.name" />
        </el-form-item>

        <el-form-item label="학원상태" prop="status">
          <el-radio-group v-model="form.status">
            <el-radio v-for="(item, i) in page.props.config.dbcode.academies.status" :key="i" :label="getLabel(i)">
              {{ item }}
            </el-radio>
          </el-radio-group>
        </el-form-item>

        <el-form-item label="관리자 메모" prop="memo">
          <el-input v-model="form.manager_memo" type="textarea" rows="5" />
        </el-form-item>

        <el-form-item label="태그" prop="tags">
          <el-select
            v-model="form.tags"
            multiple
            filterable
            allow-create
            default-first-option
            :reserve-keyword="false"
            placeholder="태그를 입력해주세요"
          >
          </el-select>
        </el-form-item>

        <el-form-item label="연락처" prop="phone">
          <el-input
            v-model="form.phone"
            :formatter="(value) => value.replace(/(\d{3})(\d{3,4})(\d{4})/, '$1-$2-$3')"
            :parser="(value) => value.replace(/\D+/g, '')"
            class="w-25"
          />
        </el-form-item>

        <el-form-item label="우편번호">
          <el-input v-model="form.zipcode" class="w-25" disabled />
          <el-button type="success" class="mx-3" @click="findAddress">주소검색</el-button>
        </el-form-item>

        <el-form-item label="주소">
          <el-input v-model="form.address" disabled />
        </el-form-item>

        <el-form-item label="상세 주소">
          <el-input v-model="form.address2" />
        </el-form-item>

        <el-row>
          <el-col :span="11">
            <el-form-item label="담당자명" prop="staff_name">
              <el-input v-model="form.staff_name" />
            </el-form-item>
          </el-col>
          <el-col :span="2" />
          <el-col :span="11">
            <el-form-item label="담당자 전화번호" prop="staff_name">
              <el-input
                v-model="form.staff_phone"
                :formatter="(value) => value.replace(/(\d{3})(\d{3,4})(\d{4})/, '$1-$2-$3')"
                :parser="(value) => value.replace(/\D+/g, '')"
              />
            </el-form-item>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="11">
            <el-form-item label="담당자 이메일" prop="staff_email">
              <el-input v-model="form.staff_email" />
            </el-form-item>
          </el-col>
          <el-col :span="2" />
          <el-col :span="11"> </el-col>
        </el-row>

        <div class="text-end">
          <button :data-kt-indicator="loading ? 'on' : null" class="btn btn-lg btn-primary me-3" type="submit">
            <span v-if="!loading" class="indicator-label">{{ page.props.academy ? '저장' : '등록' }}</span>
            <span v-if="loading" class="indicator-progress">
              처리중입니다...
              <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
        </div>
      </el-form>
    </div>
  </div>
  <GoListButton :list-url="route('admin.academies.index')" />
</template>

<script setup lang="ts">
  import { ref } from 'vue';
  import type { PageProps } from '@inertiajs/core';
  import { getAssetPath } from '@/admin/core/helpers/assets';
  import { useForm, usePage } from '@inertiajs/vue3';
  import { FormRules } from 'element-plus';
  import Swal from 'sweetalert2/dist/sweetalert2.js';
  import { Academy } from '@/admin/api/model/academy';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import { AppTag } from '@/admin/types';
  import GoListButton from '@/admin/components/admin/GoListButton.vue';

  interface Page extends PageProps {
    academy: Academy;
    config: {
      dbcode: Pick<Dbcode, 'academies'>;
      service: {
        academy: {
          basic_price: number;
        };
      };
    };
    tags: AppTag[];
  }

  const page = usePage<Page>();

  const logoUrl = ref(page.props.academy ? page.props.academy.logo : '');
  const formRef = ref<null | HTMLFormElement>(null);

  const loading = ref<boolean>(false);
  const rules = ref<FormRules>({
    name: [{ required: true, message: '필수입력입니다', trigger: 'blur' }],
    status: [{ required: true, message: '필수입력입니다', trigger: 'blur' }],
  });

  const form = useForm({
    _method: page.props.academy ? 'put' : 'post',
    name: page.props.academy?.name,
    status: page.props.academy?.status || 0,
    remove_logo: false,
    logo: '',
    manager_memo: page.props.academy?.manager_memo,
    tags: page.props.tags,
    phone: page.props.academy?.phone,
    staff_name: page.props.academy?.staff_name,
    staff_phone: page.props.academy?.staff_phone,
    staff_email: page.props.academy?.staff_email,
    zipcode: page.props.academy?.zipcode,
    address: page.props.academy?.address,
    address2: page.props.academy?.address2,
  });

  const removeImage = () => {
    form.remove_logo = true;
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

        form.logo = file;
      }; // ref previewImage 값 변경

      // 컨텐츠를 특정 file 에서 읽어옴. 읽는 행위가 종료되면 loadend 이벤트 트리거함
      // & base64 인코딩된 스트링 데이터가 result 속성에 담김
      reader.readAsDataURL(file);
    }
  };

  const getLabel = (v) => {
    return typeof v === 'string' ? parseInt(v) : v;
  };

  /**
   * 주소검색
   */
  const findAddress = function () {
    new window.daum.Postcode({
      oncomplete(data) {
        // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

        // 도로명 주소의 노출 규칙에 따라 주소를 조합한다.
        // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
        let fullRoadAddr = data.roadAddress; // 도로명 주소 변수
        let extraRoadAddr = ''; // 도로명 조합형 주소 변수

        // 법정동명이 있을 경우 추가한다. (법정리는 제외)
        // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
        if (data.bname !== '' && /[동|로|가]$/g.test(data.bname)) {
          extraRoadAddr += data.bname;
        }
        // 건물명이 있고, 공동주택일 경우 추가한다.
        if (data.buildingName !== '' && data.apartment === 'Y') {
          extraRoadAddr += extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName;
        }
        // 도로명, 지번 조합형 주소가 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
        if (extraRoadAddr !== '') {
          extraRoadAddr = ' (' + extraRoadAddr + ')';
        }
        // 도로명, 지번 주소의 유무에 따라 해당 조합형 주소를 추가한다.
        if (fullRoadAddr !== '') {
          fullRoadAddr += extraRoadAddr;
        }

        // 우편번호와 주소 정보를 해당 필드에 넣는다.
        form.zipcode = data.zonecode; //5자리 새우편번호 사용
        form.address = fullRoadAddr;
      },
    }).open();
  };

  const submit = () => {
    formRef.value?.validate((valid: boolean) => {
      if (valid) {
        loading.value = true;
        const action = page.props.academy
          ? route('admin.academies.update', page.props.academy.id)
          : route('admin.academies.store');

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
