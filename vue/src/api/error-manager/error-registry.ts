import { errorHandlers } from "./error-handlers"
import { ErrorHandler, ErrorHandlerMany, ErrorHandlerObject, HttpErrorType } from "../interfaces"

class ErrorHandlerRegistry {
    private handlers = new Map<string, ErrorHandler>()

    constructor(input?: ErrorHandlerMany) {
        if (typeof input !== 'undefined') this.registerMany(input)
    }

    register(key: string, handler: ErrorHandler) {
        this.handlers.set(key, handler)
        return this
    }

    unregister(key: string) {
        this.handlers.delete(key)
        return this
    }

    find(seek: string): ErrorHandler | null {
        const handler = this.handlers.get(seek)
        if (handler) return handler
        return null
    }

    registerMany(input: ErrorHandlerMany) {
        for (const [key, value] of Object.entries(input)) {
            this.register(key, value)
        }
        return this
    }

    async handleError(
        this: ErrorHandlerRegistry,
        seek: (string | undefined)[] | string,
        error: HttpErrorType
    ): Promise<boolean> {
        if (Array.isArray(seek)) {
            return seek.some(async (key) => {
                if (key !== undefined) return await this.handleError(String(key), error)
            })
        }
        const handler = this.handlers.get(String(seek))
        if (!handler) {
            return false
        }

        if (typeof handler === 'string') {
            return await this.handleErrorObject(error, { message: handler })
        }

        if (typeof handler === 'function') {
            const result = handler(error)
            if (this.isErrorHandlerObject(result)) return await this.handleErrorObject(error, result)
            return !!result
        }

        if (this.isErrorHandlerObject(handler)) {
            return await this.handleErrorObject(error, handler)
        }
        return false
    }

    async handleErrorObject(error: HttpErrorType, options: ErrorHandlerObject = {}) {
        if(await options?.before?.(error, options)) return true
        console.error('Error:', error, options.message ?? 'Somthing went wrong') //TODO Error msg in alert
        await options?.after?.(error, options)
        return true
    }

    isErrorHandlerObject(obj: any): obj is ErrorHandlerObject {
        return (
            typeof obj === 'object' &&
            obj !== null &&
            ('message' in obj || 'before' in obj || 'after' in obj)
        )
    }
}

export const errorHandlerRegistry = new ErrorHandlerRegistry(errorHandlers)