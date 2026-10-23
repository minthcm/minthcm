import { AxiosRequestConfig } from 'axios'
import { mintApi } from './api'

class FilesApi  {
    public async getFiles(module: string, record_id: string) {
        return await mintApi.get(`files/${module}/${record_id}`)
    }

    public async saveFile(module: string, record_id: string, file: File, path: string = 'files/save', config: AxiosRequestConfig = {}) {
        const formData = new FormData()
        formData.append('file', file)
        if (module) formData.append('module_name', module)
        if (record_id) formData.append('record_id', record_id)
        return mintApi.post(path, formData, { ...config, headers: { ...config.headers, 'Content-Type': undefined } })
    }

    public async deleteFile(file_id: string) {
        return await mintApi.post('files/delete', {
            file_id: file_id,
        })
    }
}

export const filesApi = new FilesApi()
