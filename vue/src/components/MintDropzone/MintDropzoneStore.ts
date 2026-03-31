import { defineStore } from 'pinia'
import { filesApi } from '@/api/files.api'

export const useDropzoneStore = defineStore('dropzone', () => {

    async function getFiles(module: string, record_id: string) {
        const response = await filesApi.getFiles(module, record_id)
        if (response.status === 200 && response.data) {
            return response.data
        }
    }

    async function saveFile(module: string, record_id: string, file: File) {
        const response = await filesApi.saveFile(module, record_id, file)
        if (response.status === 200 && response.data) {
            return response.data
        }
    }

    async function deleteFile(file_id: string) {
        const response = await filesApi.deleteFile(file_id)
        if (response.status === 200 && response.data) {
            return response.data
        }
    }

    return {
        getFiles,
        saveFile,
        deleteFile,
    }
})
