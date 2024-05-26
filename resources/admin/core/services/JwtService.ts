const ADMIN_ID_TOKEN_KEY = 'admin_id_token' as string;

/**
 * @description get token form localStorage
 */
export const getToken = (): string | null => {
  return window.localStorage.getItem(ADMIN_ID_TOKEN_KEY);
};

/**
 * @description save token into localStorage
 * @param token: string
 */
export const saveToken = (token: string): void => {
  window.localStorage.setItem(ADMIN_ID_TOKEN_KEY, token);
};

/**
 * @description remove token form localStorage
 */
export const destroyToken = (): void => {
  window.localStorage.removeItem(ADMIN_ID_TOKEN_KEY);
};

export default { getToken, saveToken, destroyToken };
