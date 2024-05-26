export interface CurriculumNode {
  id: number;
  label: string;
  children?: CurriculumNode[];
}

export interface Curriculum {
  ancestors?: Curriculum[];
  name: string;
  parent_id: number;
  _lft: number;
  _rgt: number;
  id: number;
  element: number;
  txt_element: string;
  created_at: string;
  updated_at: string;
}
