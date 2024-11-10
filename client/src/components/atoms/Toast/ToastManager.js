import { Toast } from './Toast.js';

export const showToast = (message, status) => {
  const toast = new Toast(message, status);
  document.body.appendChild(toast);

  setTimeout(() => {
    toast.remove();
  }, 3000);
};
