export interface Payment {
  id: number;
  model_type: string;
  model_id: number;
  trd_no: string;
  method: string;
  od_id: string;
  od_name: string;
  amount: number;
  cancel_amount: number;
  status: number;
  extra: {
    service_date: {
      start: string;
      end: string;
    };
    pg_result: PgResult[];
    cancel_memo: string;
  };
  canceled_at: string | null;
  approved_at: string | null;
  created_at: string;
  updated_at: string | null;
  deleted_at: null;
  cash_receipt: false;
  info: PaymentInfo;
  model: unknown;
  txt_status: string;
  txt_method: string;
}

export interface PgResult {
  trdNo: string;
  cardCd: string;
  cardNm: string;
  cardNo: string;
  mchtId: string;
  method: string;
  trdAmt: string;
  trdDtm: string;
  bizType: string;
  pktHash: string;
  mchtName: string;
  pmtprdNm: string;
  instmtMon: string;
  mchtParam: string;
  mchtTrdNo: string;
  outStatCd: string;
  cardApprNo: string;
  mchtCustId: number;
  mchtCustNm: string;
}
export interface PaymentInfo {
  detail: [string, string];
  trd_no: string;
  type: string;
}

export enum PaymentStatusModel {
  PARTIAL_CANCEL = -2,
  CANCEL = -1,
  WAITING = 0,
  APPROVE = 1,
}

export enum PaymentMethodModel {
  CA = 'CA',
  VA = 'VA',
  PZ = 'PZ',
}

export interface PaymentProduct {
  name: string;
  day: string;
  month: number;
  amount: {
    origin: number;
    sale: number;
  };
}
