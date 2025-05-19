export type ApiResponse =
    | {
          responseType: ApiResponseType.AUTH_ADD
          data: AuthAddResponse
      }
    | {
          responseType: ApiResponseType.AUTH_LOGIN
          data: AuthLoginResponse
      }

interface AuthLoginResponse {
    success: boolean
    error?: string
}

interface AuthAddResponse extends AuthLoginResponse {
    message: string
}

export enum ApiResponseType {
    AUTH_LOGIN = 'auth_login',
    AUTH_ADD = 'auth_add',
}
