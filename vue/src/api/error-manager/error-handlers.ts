import router from '@/router'

//TODO Transalate messages and add alert with messeage
export const errorHandlers = {
    '401': {
        message: 'Unauthorized! You are not allowed to access this resource!',
        after: async () => {
            console.log('Redirecting to login page after 401 error')
            router.push({ name: 'auth-login' })
            router.go(0) // refresh
        }
    },
    '404': { message: 'API Page not found!' },
    '500': { message: 'Internal server error!' },
}