<template>
  <el-dialog :model-value="isShow" title="결제취소" width="600" @close="emits('hideModal')">
    <h4>결제내역</h4>
    <el-divider class="my-4" />
    <div class="d-flex flex-column gap-2">
      <div class="d-flex gap-4">
        <el-text tag="b">고유번호</el-text>
        <el-text> {{ payment.id }}</el-text>
      </div>
      <div class="d-flex gap-4">
        <el-text tag="b">학생명</el-text>
        <el-text> {{ payment.model.name }}</el-text>
      </div>
      <div class="d-flex gap-4">
        <el-text tag="b">상품명</el-text>
        <el-text> {{ payment.extra.pg_result[0].pmtprdNm }}</el-text>
      </div>
      <div class="d-flex gap-4">
        <el-text tag="b">결제수단</el-text>
        <el-text> {{ payment.info.type }}</el-text>
      </div>
      <div class="d-flex gap-4">
        <el-text tag="b">결제금액</el-text>
        <el-text> {{ payment.amount.toLocaleString() }}원</el-text>
      </div>
    </div>

    <el-form ref="cancelFormRef" :rules="cancelFormRules" :model="cancelForm" label-width="150px">
      <h4 class="mt-15">취소내역</h4>
      <el-divider class="my-4" />
      <el-form-item label="결제금액">
        <el-input type="number" disabled readonly :value="payment.amount" />
      </el-form-item>
      <el-form-item label="취소(환불)금액" prop="amount">
        <el-input
          v-model="cancelForm.amount"
          type="number"
          step="10"
          placeholder="취소(환불) 금액을 입력해 주세요."
          :min="0"
          :max="payment.amount"
        />
      </el-form-item>
      <el-form-item label="취소(환불)사유" prop="memo">
        <el-input v-model="cancelForm.memo" type="textarea" placeholder="취소(환불) 사유를 입력해 주세요." />
      </el-form-item>
      <el-form-item label="취소타입" prop="confirmText">
        <el-input v-model="cancelForm.confirmText" type="text" :placeholder="cancelConfirmMessages.join(' or ')" />
      </el-form-item>
      <!-- todo 가상계좌 환불시 추가-->
      <template v-if="payment.info.type == '가상계좌'">
        <el-form-item label="환불 받을 은행" prop="refundBankCd">
          <el-select v-model="cancelForm.refundBankCd" filterable placeholder="은행을 선택하세요">
            <el-option
              v-for="(bankName, bankCode) in bankCodes"
              :key="bankCode"
              :label="bankName"
              :value="bankCode"
            ></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="환불 계좌 ('-' 없이)" prop="refundAcntNo">
          <el-input v-model="cancelForm.refundAcntNo" type="text"/>
        </el-form-item>
        <el-form-item label="환불 예금주" prop="refundDpstrNm">
          <el-input v-model="cancelForm.refundDpstrNm" type="text" />
        </el-form-item>
      </template>
    </el-form>

    <el-alert class="mt-5" type="info" show-icon :closable="false">
      <div class="d-flex gap-5 flex-wrap">
        * 결제취소는 건당 1회만 가능합니다.<br />
        * 이용권 결제취소 : 전체취소, 부분취소 상관없이 이용권 일수만큼 이용기간이 차감됩니다.<br />
        * 주문 결제취소 : 결제취소 후 배송상태 수정이 필요합니다.<br />
        * 가상계좌 취소시 환불은 익일부터 3일 소요됩니다.
      </div>
    </el-alert>

    <div class="mt-5 d-flex justify-content-center">
      <el-button type="danger" @click="onSubmitPaymetnCancel">취소환불</el-button>
      <el-button @click="emits('hideModal')">닫기</el-button>
    </div>
  </el-dialog>
</template>

<script setup lang="ts">
  import { ref, PropType } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import {BankCodes, Payment} from '@/admin/api/model/payment';
  import { ElMessageBox, FormRules } from 'element-plus';

  const props = defineProps({
    isShow: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    payment: {
      type: Object as PropType<Payment>,
      default: null,
    },
    bankCodes: {
      type: Object as PropType<BankCodes>,
      required: true,
    }
  });

  const emits = defineEmits<{
    (e: 'hideModal'): void;
  }>();

  const cancelFormRef = ref();

  const cancelConfirmMessages = ['전체취소', '부분취소'];

  const cancelFormRules = ref<FormRules>({
    amount: [
      { required: true, message: '필수입력입니다' },
      {
        validator(rule, value) {
          return !(Number(value) > Number(props.payment.amount));
        },
        message: '결제금액을 초과한 금액은 입력할 수 없습니다.',
      },
      {
        validator(rule, value) {
          return !(Number(value) < 0);
        },
        message: '0보다 작은 금액은 입력할 수 없습니다.',
      },
    ],
    confirmText: [
      { required: true, message: '필수입력입니다' },
      {
        validator() {
          if (Number(cancelForm.amount) < Number(props.payment.amount)) {
            if (cancelForm.confirmText === cancelConfirmMessages[1]) return true;

            return false;
          } else {
            if (cancelForm.confirmText === cancelConfirmMessages[0]) return true;

            return false;
          }
        },
        message: '취소타입을 정확하게 입력해 주세요.',
      },
    ],
    refundBankCd: [
      { required: true, message: '필수입력입니다' },
    ],
    refundAcntNo: [
      { required: true, message: '필수입력입니다' },
    ],
    refundDpstrNm: [
      { required: true, message: '필수입력입니다' },
    ],
  });

  const cancelForm = useForm({
    amount: '',
    memo: '',
    confirmText: '',
    refundBankCd: '',
    refundAcntNo: '',
    refundDpstrNm: '',
  });

  const onSubmitPaymetnCancel = () => {
    cancelFormRef.value.validate((valid: boolean) => {
      if (valid) {
        ElMessageBox.confirm('취소(환불) 하시겠습니까?', '', {
          type: 'warning',
        }).then(() => {
          cancelForm.post(route('admin.payments.cancel', props.payment.id), {
            onSuccess: () => {
              // 성공 처리
            },
            onError: (error) => {
              // 에러 처리
            },
          });
        });
      }
    });
  };
</script>
