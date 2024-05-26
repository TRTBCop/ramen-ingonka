<template>
  <div class="card mb-5 mb-xxl-8">
    <div class="card-body pt-9 pb-0">
      <div class="d-flex flex-wrap flex-sm-nowrap mb-3 align-items-center">
        <div class="me-7 mb-4">
          <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
<!--            <img :src="academy.avatar" alt="image" />-->
          </div>
        </div>
        
        <div class="flex-grow-1">
          <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
            <div class="d-flex flex-column gap-4">
              <div class="d-flex align-items-center mb-2">
                <span class="text-gray-800 text-hover-primary fs-2 fw-bold me-1">{{ academy.name }}</span>
                
                <span
                  :class="`btn btn-sm btn-light-${statusColors[academy.status]} fw-bold ms-2 fs-8 py-1 px-3`"
                  style="cursor: auto"
                >{{ pageData.props.config.dbcode.academies.status[academy.status] }}</span
                >
              </div>
              
              <ul class="d-flex flex-column gap-1 m-0 px-2">
                <li class="d-flex gap-1">
                  <span class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                    아이디 : {{ academy.id }}
                  </span>
                  <span class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                    학원상태 : {{ pageData.props.config.dbcode.academies.status[academy.status] }}
                  </span>
                  <span class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                    담당자명 : {{ academy.staff_name }}
                  </span>
                  <span class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                    전화번호 : {{ academy.staff_phone }}
                  </span>
                  <span class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                    등록일 : {{ academy.created_at.substring(0,10) }}
                  </span>
                </li>
                <a
                  v-if="academy.id"
                  :href="route('admin.academies.show', academy.id)"
                  class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2"
                  target="_blank"
                >
                  학원 : {{ academy.name }}
                </a>
              </ul>
            </div>
          </div>
        </div>
      </div>
      
      <div class="d-flex overflow-auto h-55px">
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold flex-nowrap">
          <li class="nav-item">
            <Link
              :href="route('admin.academies.show', academy.id)"
              class="nav-link text-active-primary"
              :class="{ active: route().current() == 'admin.academies.show' }"
            >
              학원정보
            </Link>
          </li>
          <li class="nav-item">
            <Link
              :href="route('admin.academies.student-list', academy)"
              class="nav-link text-active-primary"
              :class="{ active: route().current() == 'admin.academies.student-list' }"
            >
              학생목록
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
import { ElMessage } from 'element-plus';
import {Academy} from "@/admin/api/model/academy";

interface Page extends PageProps {
  academies: { [key in number]: string };
  academy: Academy;
  config: {
    dbcode: Pick<Dbcode, 'academies'>;
  };
}

const pageData = usePage<Page>();

const academy = computed(() => pageData.props.academy);

console.log('tetsts', pageData.props.config.dbcode.academies.status)

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
</script>
