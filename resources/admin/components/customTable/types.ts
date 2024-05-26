export interface TableSelectFilter {
  name: string;
  options: TableSelectFilterOptions;
  placeholder: string;
  isMultiple: boolean;
  // 해당 필터가 바뀌었을 때 그 값에 맞게 바껴야하는 하위 필터
  subOptions?: {
    name: string;
    placeholder: string;
    isMultiple: boolean;
    options: { [parentKey: string]: { [key: string]: string } };
  };
}

export interface TableSelectFilterOptions {
  [key: string]: string;
}
export interface TableInfo {
  perPage: number;
  total: number;
  currentPage: number;
  data: unknown[];
  header: {
    columnName: string;
    columnLabel: string;
    columnWidth?: number;
    columnMinWidth?: number;
  }[];
}
