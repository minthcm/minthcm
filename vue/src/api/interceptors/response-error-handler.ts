import axios from 'axios'
import { HttpErrorData, HttpErrorType } from '../interfaces'
import { errorHandlerRegistry } from '../error-manager/error-registry'

export async function responseErrorHandler(error: HttpErrorType) {
    if (error === null) throw new Error('Unrecoverrable error!! Error is null!')
    if (error.name === 'CanceledError') throw error

    if (axios.isAxiosError(error)) {
        const response = error?.response
        const config = error?.config
        const data = response?.data as HttpErrorData

        if (config?.rawError) {
            throw error
        }

        const seekers = [String(data?.code), error.code, error?.name, String(response?.status)]

        const result = await errorHandlerRegistry.handleError(seekers, error)
        if (!result && data?.code && data?.message) {
            return errorHandlerRegistry.handleErrorObject(error, {
                message: data.message,
            })
        }
        return result
    }

    if (error instanceof Error) {
        return errorHandlerRegistry.handleError(error.name, error)
    }

    throw error
}
