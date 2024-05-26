import { defineStore } from 'pinia';
import { store } from '@/app/stores';
import { ModalState } from '@/app/types/modals';

interface SystemState {
  modalState: ModalState;
}

export const useSystemStore = defineStore({
  id: 'app-system',
  state: (): SystemState => ({
    modalState: {
      show: false,
      title: '',
      message: '',
      confirmEvent: null,
    },
  }),
  getters: {
    getModalState(): ModalState {
      return this.modalState;
    },
  },
  actions: {
    setModalState(modalState: ModalState) {
      this.modalState = modalState;
    },
    hideModalState() {
      this.modalState.show = false;
      this.modalState.title = '';
      this.modalState.message = '';
      this.modalState.confirmEvent = null;
    },
  },
});

export function useSystemStoreWithOut() {
  return useSystemStore(store);
}
