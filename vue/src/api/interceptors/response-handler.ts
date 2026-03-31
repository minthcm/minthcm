import { AxiosResponse } from "axios";


export function responseHandler(response: AxiosResponse<any>): AxiosResponse<any> | any {
    return response; //TODO parse response
}