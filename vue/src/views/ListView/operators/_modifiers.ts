
export const modifiers = {
    toLower: (a: any): any => {
        if (!Array.isArray(a)) {
            return a?.toString().toLowerCase()
        }
        return a.map((v) => modifiers.toLower(v))
    },
}