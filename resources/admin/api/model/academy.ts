import { AppTag } from '@/admin/types';

export interface Academy {
  id: number;
  name: string;
  zipcode: string;
  address: string;
  address2: string;
  phone: string;
  staff_phone: string;
  staff_name: string;
  staff_email: string;
  status: AcademyStatus;
  extra: unknown[];
  manager_memo: string | null;
  created_at: string;
  updated_at: string;
  deleted_at: string | null;
  txt_status: string;
  students_count: number;
  logo: string;
  files: unknown[];
  media: unknown[];
  tags: AppTag[];
  payments: unknown[];
}

export enum AcademyStatus {
  UNPAID_STOP = -2, // 미납정지
  STOP = -1, // 정지
  FREE = 0, // 무료사용
  PREMIUM = 1, // 정상
}
