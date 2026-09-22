import { MassAction } from '../MassAction'

export class Export extends MassAction {
    protected static readonly actionName = 'Export'

    public async execute(): Promise<boolean> {
        const response = await this.sendRequest({}, { responseType: 'blob' })

        if (!response?.headers) {
            return false
        }

        const disposition: string = response.headers['content-disposition'] ?? ''
        const filenameMatch = disposition.match(/filename="?([^"]+)"?/)
        const filename = filenameMatch ? filenameMatch[1] : `${this.module}.csv`

        const url = URL.createObjectURL(response.data)
        const a = document.createElement('a')
        a.href = url
        a.download = filename
        document.body.appendChild(a)
        a.click()
        document.body.removeChild(a)
        URL.revokeObjectURL(url)

        return true
    }
}
