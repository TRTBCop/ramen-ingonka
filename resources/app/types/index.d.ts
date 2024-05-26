export interface User {
  id: number;
  academy_id: number | null;
  access_id: string;
  address: string;
  school_name: string;
  grade: number;
  term: number;
  name: string;
  profile_img_type: number;
  phone: string;
  parents_name: string;
  parents_phone: string;
  birth_date: string;
  manager_memo: string | null;
  status: number;
  service_start_date: string | null;
  service_end_date: string | null;
  marketing_consent: boolean;
  last_login_at: string;
  kakao_id: number | null;
  naver_id: number | null;
  extra: {
    free_trial?: {
      expired: boolean;
      end_date: string;
      start_date: string;
    };
  };
  created_at: string;
  updated_at: string;
  deleted_at: string | null;
  txt_status: string;
  avatar: string;
  roles: object;
  role_names: string[];
  media: unknown[];
  email: string;
  email_verified_at: string;
}

export interface CollectionMeta {
  current_page: number;
  total: number;
  per_page: number;
  last_page: number;
  links: {
    url: string | null;
    label: string;
    active: boolean;
  }[];
}

export interface Collection<T = unknown> {
  data: T[];
  links: {
    first: string | null;
    last: string | null;
    prev: string | null;
    next: string | null;
  };
  meta: CollectionMeta;
}
