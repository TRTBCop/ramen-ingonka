import { watch, onMounted, nextTick } from 'vue';
import { isEmpty } from 'lodash';
import { useSystemStoreWithOut } from '@/app/stores/modules/system';
import { usePage } from '@inertiajs/vue3';

const systemStore = useSystemStoreWithOut();

export function useErrorModal() {
  const pageData = usePage();

  function handleErrorMessage() {
    if (!isEmpty(pageData.props.errors)) {
      systemStore.setModalState({
        show: true,
        message: String(pageData.props.errors),
      });
    } else if (pageData.props.flash.message && pageData.props.flash.message[0] === 'error') {
      systemStore.setModalState({
        show: true,
        message: pageData.props.flash.message[1],
      });
    }
  }

  // 에러 메시지 노출
  watch(
    () => pageData.props.errors,
    () => {
      handleErrorMessage();
    },
  );

  onMounted(() => {
    // 에러 메시지 노출
    nextTick(() => {
      handleErrorMessage();
    });
  });
}
