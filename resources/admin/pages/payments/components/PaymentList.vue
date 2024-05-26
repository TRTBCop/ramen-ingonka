<template>
  <CustomTable :request-url="route(pageData.props.route_name, pageData.props.student_id)" :select-filters="selectFilters" :table-info="tableInfo">
    <template #student_name="{ row: value }">
      <Link :href="route('admin.students.show', value.model.id)" class="menu-link px-3">
        {{ value.model.name }}
      </Link>
    </template>

    <template #student_id="{ row: value }">
      {{ value.model.access_id }}
    </template>

    <template #product_name="{ row: value }">
      {{ value.extra.pg_result[0].pmtprdNm }}
    </template>

    <template #amount="{ row: value }"> {{ value.amount.toLocaleString() }}원 </template>

    <template #cancel_amount="{ row: value }">
      {{ value.cancel_amount ? `${value.cancel_amount.toLocaleString()}원` : '' }}
    </template>

    <template #created_at="{ row: value }">
      {{ dayjs(value.created_at).format('YYYY-MM-DD HH:mm:ss') }}
    </template>

    <template #approved_at="{ row: value }">
      {{ value.approved_at ? dayjs(value.approved_at).format('YYYY-MM-DD HH:mm:ss') : '' }}
    </template>

    <template #canceled_at="{ row: value }">
      {{ value.canceled_at ? dayjs(value.canceled_at).format('YYYY-MM-DD HH:mm:ss') : '' }}
    </template>

    <template #cash_receipt="{ row: value }">
      {{ value.cash_receipt ? '발행' : '미발행' }}
    </template>

    <template #actions="{ row: value }">
      <div class="d-flex gap-1">
        <a class="btn btn-sm btn-icon btn-light-primary" @click="showPaymentDetail(value.info)">
          <KTIcon icon-name="magnifier" icon-class="fs-4 m-0" />
        </a>

        <template v-if="value.approved_at && !value.canceled_at">
          <a class="btn btn-sm btn-icon btn-light-danger" @click="showPaymentCancelModal(value)">
            <KTIcon icon-name="dollar" icon-class="fs-4 m-0" />
          </a>
        </template>
      </div>
    </template>
  </CustomTable>

  <!-- 결제 상세 모달 -->
  <el-dialog v-model="paymentDetailModal.isShow" title="결제정보" width="600">
    <el-form-item label="결제수단">
      <el-input readonly :value="`${paymentDetailModal.info?.detail[0]}(${paymentDetailModal.info?.detail[1]})`" />
    </el-form-item>
    <el-form-item label="결제상세">
      <el-input readonly :value="paymentDetailModal.info?.type" />
    </el-form-item>
    <el-form-item label="승인번호">
      <el-input readonly :value="paymentDetailModal.info?.trd_no" />
    </el-form-item>
  </el-dialog>

  <!-- 결제 취소 모달 -->
  <PaymentCancelModal
    v-if="paymentCancelModal.payment"
    :is-show="paymentCancelModal.isShow"
    :payment="paymentCancelModal.payment"
    :bank-codes="pageData.props.config.bank"
    @hide-modal="hidePaymentCancelModal"
  />
</template>

<script setup lang="ts">
  import { computed, reactive } from 'vue';
  import {Link, usePage} from '@inertiajs/vue3';
  import CustomTable from '@/admin/components/customTable/CustomTable.vue';
  import { TableInfo, TableSelectFilter } from '@/admin/components/customTable/types';
  import { AppTag, Collection, PageProps } from '@/admin/types';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import KTIcon from '@/admin/core/helpers/kt-icon/KTIcon.vue';
  import { dayjs } from 'element-plus';
  import {BankCodes, Payment, PaymentInfo} from '@/admin/api/model/payment';
  import PaymentCancelModal from './PaymentCancelModal.vue';
  
  interface Page extends PageProps {
    config: {
      dbcode: Pick<Dbcode, 'payments'>;
      bank: BankCodes;
    };
    collection: Collection;
    tags: AppTag[];
    student_id: number;
  }

  const pageData = usePage<Page>();
  const paymentDetailModal = reactive<{
    isShow: boolean;
    info: PaymentInfo | null;
  }>({
    isShow: false,
    info: null,
  });

  const showPaymentDetail = (paymentInfo: PaymentInfo) => {
    paymentDetailModal.isShow = true;
    paymentDetailModal.info = paymentInfo;
  };

  const paymentCancelModal = reactive<{
    isShow: boolean;
    payment: Payment | null;
  }>({
    isShow: false,
    payment: null,
  });

  const showPaymentCancelModal = (payment: Payment) => {
    paymentCancelModal.isShow = true;
    paymentCancelModal.payment = payment;
  };

  const hidePaymentCancelModal = () => {
    paymentCancelModal.isShow = false;
    paymentCancelModal.payment = null;
  };

  const statusOptions = computed(() => {
    const statusObj = pageData.props.config.dbcode.payments.status;
    const obj = {};

    Object.entries(statusObj).forEach(([key, value]) => {
      obj[key] = value;
    });

    return obj;
  });

  const methodOptions = computed(() => {
    const methodObj = pageData.props.config.dbcode.payments.method;
    const obj = {};

    Object.entries(methodObj).forEach(([key, value]) => {
      obj[key] = value;
    });

    return obj;
  });

  const selectFilters = computed<TableSelectFilter[]>(() => [
    {
      name: 'status',
      isMultiple: true,
      placeholder: '결제상태',
      options: statusOptions.value,
    },
    {
      name: 'method',
      isMultiple: true,
      placeholder: '결제방식',
      options: methodOptions.value,
    },
  ]);

  const tableInfo = computed<TableInfo>(() => ({
    perPage: pageData.props.collection.meta.per_page,
    total: pageData.props.collection.meta.total,
    currentPage: pageData.props.collection.meta.current_page,
    data: pageData.props.collection.data,
    header: [
      {
        columnName: '고유번호',
        columnLabel: 'id',
        columnWidth: 100,
      },
      {
        columnName: '상품명',
        columnLabel: 'product_name',
        columnWidth: 100,
      },
      {
        columnName: '학생명',
        columnLabel: 'student_name',
        columnMinWidth: 100,
      },
      {
        columnName: '아이디',
        columnLabel: 'student_id',
        columnMinWidth: 100,
      },
      {
        columnName: '결제수단',
        columnLabel: 'txt_method',
        columnWidth: 150,
      },
      {
        columnName: '결제금액',
        columnLabel: 'amount',
        columnWidth: 150,
      },
      {
        columnName: '환불금액',
        columnLabel: 'cancel_amount',
        columnWidth: 150,
      },
      {
        columnName: '결제상태',
        columnLabel: 'txt_status',
        columnWidth: 150,
      },
      {
        columnName: '결제신청일',
        columnLabel: 'created_at',
        columnWidth: 150,
      },
      {
        columnName: '결제완료일',
        columnLabel: 'approved_at',
        columnWidth: 150,
      },
      {
        columnName: '결제취소일',
        columnLabel: 'canceled_at',
        columnWidth: 150,
      },
      {
        columnName: '현금영수증',
        columnLabel: 'cash_receipt',
        columnWidth: 100,
      },
      {
        columnName: '',
        columnLabel: 'actions',
        columnWidth: 100,
      },
    ],
  }));
</script>
