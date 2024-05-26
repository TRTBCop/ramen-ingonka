import { Academy } from '@/admin/api/model/academy';

export interface Student {
  id: number;
  academy_id: number | null;
  name: string;
  profile_img_type: number;
  access_id: string;
  phone: string;
  address: string;
  school_name: string;
  grade: number;
  term: number;
  parents_name: string;
  parents_phone: string;
  birth_date: string | null;
  manager_memo: string | null;
  extra: unknown[];
  status: StudentStatus;
  last_login_at: string | null;
  created_at: string;
  updated_at: string;
  deleted_at: string | null;
  txt_status: string;
  avatar: string;
  academy?: Academy;
  media: [];
  naver_id: null | string;
  kakao_id: null | string;
  service_start_date: null | string;
  service_end_date: null | string;
  marketing_consent: number;
}

export enum StudentStatus {
  STOP = -2, // 학습중지
  EXPIRED = -1, // 기간만료
  STANDBY = 0, // 대기
  IN_USE = 1, // 이용중
}
