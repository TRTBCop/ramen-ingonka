import { ElMessageBox } from 'element-plus';

/**
 * html 코드를 제거한 문자열을 반환
 * @param input html이 포함된 문자열
 * @returns
 */
export const stripHtmlTags = (input: string) => {
  return input.replace(/<\/?[^>]+(>|$)/g, '');
};

/**
 * 삭제하기 전에 공통 컨펌 모달 함수
 * @param submitAction 삭제 진행시 실행 할 함수
 * @param cancelAction 삭제 취소시 실행 할 함수
 */
export const commonDeleteConfirm = (submitAction: () => void, cancelAction?: () => void) => {
  ElMessageBox.confirm('정말 삭제하시겠습니까?', '', {
    type: 'warning',
  }).then(
    () => {
      submitAction();
    },
    () => {
      if (cancelAction) {
        cancelAction();
      }
    },
  );
};
