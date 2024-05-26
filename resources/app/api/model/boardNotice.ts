export interface BoardNotice {
  id: number;
  admin_id: number;
  scope: number;
  category: string;
  sub_category: string;
  title: string;
  contents: string;
  files: { id: number; name: string; url: string }[];
  published_at: string | null;
  created_at: string;
  updated_at: string;
  deleted_at: string | null;
}
