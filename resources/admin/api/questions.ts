import ApiService from '@/admin/core/services/ApiService';
import { Result } from '@/admin/api/model/baseModel';
import { Collection } from '@/admin/types';
import { Question, QuestionAnswer } from './model/questions';

export function getQuestion(id: number) {
  return ApiService.get<
    Result<{
      curriculum_id_to_name: { [key in number]: { name: string; txt_element: string } };
      question: Question;
    }>
  >(route('admin.questions.show', id));
}

export interface QuestionStoreParams {
  curriculum_id?: number;
  inquiry?: string;
  answers: QuestionAnswer[];
  level?: number;
  tags?: unknown;
  rel?: unknown;
  is_published?: boolean;
}

export function storeQuestion(params: QuestionStoreParams) {
  return ApiService.post<
    Result<{
      curriculum_id_to_name: { [key in number]: { name: string; txt_element: string } };
      question: Question;
    }>
  >(route('admin.questions.store'), params);
}

export function updateQuestion(id: number, params: unknown) {
  return ApiService.put<
    Result<{
      curriculum_id_to_name: { [key in number]: { name: string; txt_element: string } };
      question: Question;
    }>
  >(route('admin.questions.update', id), params);
}

export function getQuestionsList(page: number, filter_text: string) {
  return ApiService.get<
    Result<{
      collection: Collection;
    }>
  >(
    route('admin.questions.index', {
      page,
      filter_text,
      output: 'json',
    }),
  );
}

export function deleteQuestion(id: number) {
  return ApiService.delete<Result>(route('admin.questions.destroy', id));
}
