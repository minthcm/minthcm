import axios from 'axios'

interface Request {
    url: string
    method?: string
    data?: object
}

export function useApi() {
    const instance = axios.create()

    async function request({ url, method = 'GET', data = {} }: Request) {
        const response = await axios.request({
            url,
            method,
            headers: {
                'Content-Type': 'application/json',
            },
            data,
        })
        // todo: 3xx => redirect
        // todo: 401 => redirect to login
        // todo: 5xx => show error
        return response
    }

    async function get(url: string) {
        return await request({ url })
    }

    return {
        get,
    }
}
