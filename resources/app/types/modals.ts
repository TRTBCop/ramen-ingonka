export interface ModalState {
  show: boolean;
  title?: string;
  message: string;
  size?: 'sm' | 'md';
  confirmEvent?: (() => void) | null;
}
