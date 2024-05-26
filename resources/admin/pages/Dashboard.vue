<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >

  <AdminLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
    </template>

    <div class="row g-5 g-xl-8">
      <div class="row g-5 g-xl-8">
        <div class="col-xl-4">
          <Statistics5
            widget-classes="card-xl-stretch mb-xl-8"
            icon-name="chart-simple"
            color="white"
            icon-color="primary"
            :title="String(pageData.props.data.paid_user)"
            description="이용중 학생"
          ></Statistics5>
        </div>

        <div class="col-xl-4">
          <Statistics5
            widget-classes="card-xl-stretch mb-xl-8"
            icon-name="cheque"
            color="dark"
            icon-color="white"
            :title="String(pageData.props.data.all_user)"
            description="총 사용자수"
          ></Statistics5>
        </div>

        <div class="col-xl-4">
          <Statistics5
            widget-classes="card-xl-stretch mb-xl-8"
            icon-name="chart-pie-simple"
            color="info"
            icon-color="white"
            :title="pageData.props.data.current_month_amount"
            description="당월 결제금액"
          ></Statistics5>
        </div>
      </div>
    </div>

    <!--    <div class="card">-->
    <!--      <div class="card-header card-header-stretch">-->
    <!--        <h3 class="card-title">Title</h3>-->
    <!--        <div class="card-toolbar">-->
    <!--          <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-5 border-0">-->
    <!--            <li class="nav-item">-->
    <!--              <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_7">Link 1</a>-->
    <!--            </li>-->
    <!--            <li class="nav-item">-->
    <!--              <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_8">Link 2</a>-->
    <!--            </li>-->
    <!--            <li class="nav-item">-->
    <!--              <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_9">Link 3</a>-->
    <!--            </li>-->
    <!--          </ul>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--      <div class="card-body">-->
    <!--        <div id="myTabContent" class="tab-content">-->
    <!--          <div id="kt_tab_pane_7" class="tab-pane fade show active" role="tabpanel"> .... </div>-->

    <!--          <div id="kt_tab_pane_8" class="tab-pane fade" role="tabpanel"> ... </div>-->

    <!--          <div id="kt_tab_pane_9" class="tab-pane fade" role="tabpanel"> ... </div>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--    </div>-->

    <!--    <div class="py-12">-->
    <!--      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">-->
    <!--        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">-->
    <!--          <div class="p-6 text-gray-900 dark:text-gray-100">로그인하였습니다.</div>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--    </div>-->
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import { Head } from '@inertiajs/vue3';
  import Statistics5 from '@/admin/pages/dashboard/Statistics5.vue';

  import { usePage } from '@inertiajs/vue3';
  import { PageProps } from '@/admin/types';

  interface Page extends PageProps {
    data: {
      all_user: string;
      paid_user: string;
      free_user: string;
      current_month_amount: string;
    };
  }

  const pageData = usePage<Page>();
</script>
