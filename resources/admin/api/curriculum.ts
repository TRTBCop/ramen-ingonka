import ApiService from '@/admin/core/services/ApiService';
import { Result } from '@/admin/api/model/baseModel';
import { Curriculum } from '@/admin/api/model/curriculum';

interface CurriculumUpdateParams {
  id: number;
  name: string;
  parent_id?: number;
  position?: number;
  old_position?: number;
  element?: number;
}

export function storeCurriculum(name: string, parent_id: number) {
  return ApiService.post<Result<{ curriculum: Curriculum }>>(route('admin.curricula.store'), {
    name,
    parent_id,
  });
}

export function updateCurriculum(params: CurriculumUpdateParams) {
  return ApiService.put<Result<{ curriculum: Curriculum }>>(route('admin.curricula.update', params.id), params);
}

export function deleteCurriculum(id: number) {
  return ApiService.delete<Result<[]>>(route('admin.curricula.destroy', id));
}
