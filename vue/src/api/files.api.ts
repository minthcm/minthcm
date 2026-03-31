import { mintApi } from './api'

class FilesApi  {
    public async getFiles(module: string, record_id: string) {
        return await mintApi.get(`files/${module}/${record_id}`)
    }

    public async saveFile(module: string, record_id: string, file: File) {
        const formData = new FormData()
        formData.append('file', file)
        formData.append('module_name', module)
        formData.append('record_id', record_id)
        return await mintApi.post('files/save', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
    }

    public async deleteFile(file_id: string) {
        return await mintApi.post('files/delete', {
            file_id: file_id,
        })
    }
}

export const filesApi = new FilesApi()
