import { AxiosError } from "axios";

export interface HttpErrorData {
    code: number,
    message: string,
}

export type HttpErrorType = Error | AxiosError | null

export interface ErrorHandlerObject {
    after?(error?: HttpErrorType, options?: ErrorHandlerObject): Promise<void>,
    before?(error?: HttpErrorType, options?: ErrorHandlerObject): Promise<boolean|undefined>
    message?: string
}

export type ErrorHandler = string | ((error: HttpErrorType) => boolean | ErrorHandlerObject) | ErrorHandlerObject;

export interface ErrorHandlerMany {
    [key: string]: ErrorHandler
}