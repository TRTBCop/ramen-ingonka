import type { App } from 'vue';
import type { AxiosResponse } from 'axios';
import axios from 'axios';
import VueAxios from 'vue-axios';
import JwtService from '@/admin/core/services/JwtService';
import { ElNotification } from 'element-plus';

/**
 * @description service to call HTTP request via Axios
 */
class ApiService {
  /**
   * @description property to share vue instance
   */
  public static vueInstance: App;

  /**
   * @description initialize vue axios
   */
  public static init(app: App<Element>) {
    ApiService.vueInstance = app;
    ApiService.vueInstance.use(VueAxios, axios);
    ApiService.vueInstance.axios.defaults.baseURL = import.meta.env.VITE_APP_API_URL;

    // interceptor 설정
    ApiService.vueInstance.axios.interceptors.response.use(
      (response) => {
        const { config, data } = response;

        // 성공 메시지 공통화
        if (data.success && !config?.params?.notifyDisabled && config.method !== 'get') {
          ElNotification({
            title: 'success',
            message: data.message,
            type: 'success',
          });
        }

        return Promise.resolve(response);
      },
      (error) => {
        const { config, status, data } = error.response;

        if (!config?.params?.notifyDisabled) {
          // 에러 메시지 공통화
          ElNotification({
            title: data.message,
            message: data.data,
            type: 'error',
          });
        }
      },
    );
  }

  /**
   * @description set the default HTTP request headers
   */
  public static setHeader(): void {
    ApiService.vueInstance.axios.defaults.headers.common['Authorization'] = `Token ${JwtService.getToken()}`;
    ApiService.vueInstance.axios.defaults.headers.common['Accept'] = 'application/json';
  }

  /**
   * @description send the GET HTTP request
   * @param resource: string
   * @param params: AxiosRequestConfig
   * @returns Promise<AxiosResponse>
   */
  public static query(resource: string, params: any): Promise<AxiosResponse> {
    return ApiService.vueInstance.axios.get(resource, params);
  }

  /**
   * @description send the GET HTTP request
   * @param resource: string
   * @param slug: string
   * @returns Promise<AxiosResponse>
   */
  public static get<T = any>(resource: string): Promise<AxiosResponse<T>> {
    return ApiService.vueInstance.axios.get<T>(`${resource}`);
  }

  /**
   * @description set the POST HTTP request
   * @param resource: string
   * @param params: AxiosRequestConfig
   * @returns Promise<AxiosResponse>
   */
  public static post<T = any>(resource: string, params: any): Promise<AxiosResponse> {
    return ApiService.vueInstance.axios.post<T>(`${resource}`, params);
  }

  /**
   * @description send the UPDATE HTTP request
   * @param resource: string
   * @param slug: string
   * @param params: AxiosRequestConfig
   * @returns Promise<AxiosResponse>
   */
  public static update<T = any>(resource: string, slug: string, params: any): Promise<AxiosResponse> {
    return ApiService.vueInstance.axios.put<T>(`${resource}/${slug}`, params);
  }

  /**
   * @description Send the PUT HTTP request
   * @param resource: string
   * @param params: AxiosRequestConfig
   * @returns Promise<AxiosResponse>
   */
  public static put<T = any>(resource: string, params: any): Promise<AxiosResponse> {
    return ApiService.vueInstance.axios.put<T>(`${resource}`, params);
  }

  /**
   * @description Send the DELETE HTTP request
   * @param resource: string
   * @returns Promise<AxiosResponse>
   */
  public static delete<T = any>(resource: string): Promise<AxiosResponse> {
    return ApiService.vueInstance.axios.delete<T>(resource);
  }
}

export default ApiService;
