<template>
  <div class="card mb-5 mb-xxl-8">
    <div class="card-body pt-9 pb-0">
      <div class="d-flex flex-wrap flex-sm-nowrap mb-3 align-items-center">
        <div class="me-7 mb-4">
          <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
            <img :src="student.avatar" alt="image" />
          </div>
        </div>

        <div class="flex-grow-1">
          <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
            <div class="d-flex flex-column gap-4">
              <div class="d-flex align-items-center mb-2">
                <span class="text-gray-800 text-hover-primary fs-2 fw-bold me-1">{{ student.name }}</span>

                <span
                  :class="`btn btn-sm btn-light-${statusColors[student.status]} fw-bold ms-2 fs-8 py-1 px-3`"
                  style="cursor: auto"
                  >{{ pageData.props.config.dbcode.students.status[student.status] }}</span
                >
              </div>

              <ul class="d-flex flex-column gap-1 m-0 px-2">
                <li class="d-flex gap-1">
                  <span class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                    아이디 : {{ student.access_id }}
                  </span>
                  <span class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                    서비스 상태 : {{ pageData.props.config.dbcode.students.status[student.status] }}
                  </span>
                  <span class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                    학년/학기 : {{ pageData.props.config.dbcode.students.grade[student.grade] }} {{student.term}}학기
                  </span>
                  <span class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                    이용권 종료일 : {{ student.service_end_date }}
                  </span>
                  <span class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                    부모님 연락처 : {{ student.parents_phone }}
                  </span>
                  <span class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                    가입일 : {{ dayjs(student.created_at).format('YYYY-MM-DD HH:mm:ss') }}
                  </span>
                </li>
                <a
                  v-if="student?.academy_id"
                  :href="route('admin.academies.show', student.academy_id)"
                  class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2"
                  target="_blank"
                >
                  학원 : {{ student?.academy?.name }}
                </a>
              </ul>
            </div>

            <div class="d-flex my-4">
              <a class="btn btn-sm btn-success me-3" @click="studentLogin()">학생 로그인</a>
              <a class="btn btn-sm btn-danger me-3" @click="deleteSubscription">학생 삭제</a>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex overflow-auto h-55px">
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold flex-nowrap">
          <li class="nav-item">
            <Link
              :href="route('admin.students.show', student.id)"
              class="nav-link text-active-primary"
              :class="{ active: route().current() == 'admin.students.show' }"
            >
              학생정보
            </Link>
          </li>
          <li class="nav-item">
            <Link
              :href="route('admin.students.learning-history', student.id)"
              class="nav-link text-active-primary"
              :class="{ active: route().current() == 'admin.students.learning-history' }"
            >
              학습내역
            </Link>
          </li>
          <li class="nav-item">
            <Link
              :href="route('admin.students.learning-report', student.id)"
              class="nav-link text-active-primary"
              :class="{ active: route().current() == 'admin.students.learning-report' }"
            >
              학습보고서
            </Link>
          </li>
          <li class="nav-item">
            <Link
              :href="route('admin.students.active-log', student.id)"
              class="nav-link text-active-primary"
              :class="{ active: route().current() == 'admin.students.active-log' }"
            >
              활동로그
            </Link>
          </li>
          <li class="nav-item">
            <Link
              :href="route('admin.students.change-log', student.id)"
              class="nav-link text-active-primary"
              :class="{ active: route().current() == 'admin.students.change-log' }"
            >
              변경로그
            </Link>
          </li>
          <li class="nav-item">
            <Link
              :href="route('admin.students.payments.index', student.id)"
              class="nav-link text-active-primary"
              :class="{ active: route().current() == 'admin.students.payments.index' }"
            >
              결제목록
            </Link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { computed } from 'vue';
  import { Link, usePage, router } from '@inertiajs/vue3';
  import type { PageProps } from '@/admin/types';
  import { Student } from '@/admin/api/model/student';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import { commonDeleteConfirm } from '@/admin/core/helpers/commonHelpers';
  import {dayjs, ElMessage, ElMessageBox} from 'element-plus';
  
  interface Page extends PageProps {
    academies: { [key in number]: string };
    student: Student;
    config: {
      dbcode: Pick<Dbcode, 'students'>;
    };
  }

  const pageData = usePage<Page>();

  const student = computed(() => pageData.props.student);

  const statusColors = {
    '-2': 'danger',
    '-1': 'warning',
    '0': 'dark',
    '1': 'success',
  };

  function todoMessage() {
    ElMessage({
      message: '미구현 기능',
      type: 'error',
    });
  }
  
  const studentLogin = () => {
    ElMessageBox.confirm('학생 로그인시 학습 기록이 남을 수 있습니다. 그래도 로그인하시겠습니까?', '', {
      type: 'warning',
    }).then(
      () => {
        window.location.href = route('admin.students.login', student.value);
      },
      () => {
      },
    );
  }

  function deleteSubscription() {
    commonDeleteConfirm(() => router.delete(route('admin.students.destroy', student.value.id)));
  }
</script>
