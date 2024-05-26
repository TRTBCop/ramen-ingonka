export interface Result<T = any> {
  success: boolean;
  message: string;
  data: T;
}

export interface BaseResult {
  total_answers: number;
  correct_answers: number;
  correct_percent: number;
}
