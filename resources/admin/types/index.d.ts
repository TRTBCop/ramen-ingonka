export interface User {
  id: number;
  name: string;
  email: string;
  access_id: string;
  role_names: string[];
  roles: object;
  email_verified_at: string;
  avatar: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  auth: {
    user: User;
    csrf: string;
    is_guest: boolean;
    is_student: boolean;
    is_academy: boolean;
  };
  page: {
    title: string;
    active?: string;
  };
  flash: {
    message: Array;
  };
  route_name: string;
};

export interface Collection<T = unknown> {
  data: T[];
  links: {
    first: string | null;
    last: string | null;
    prev: string | null;
    next: string | null;
  };
  meta: {
    current_page: number;
    total: number;
    per_page: number;
  };
}

export interface AppTag {
  id: number;
  name: {
    ko: string;
  };
  slug: {
    ko: string;
  };
  type: string;
  order_column: number;
  created_at: string;
  updated_at: string;
}
