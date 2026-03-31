import Dropzone from 'dropzone';
import { useDropzoneStore } from './MintDropzoneStore';
import { useLanguagesStore } from '@/store/languages';
import { useBackendStore } from '@/store/backend';
import { useACL } from '@/composables/useACL';

class MintDropzone {
    module: string
    record: string
    customData: {}
    dropzone: Dropzone
    enableAutoProcess: boolean
    validations: string[]
    alreadyDisabled: boolean
    fileTypesMap: {}
    defaultThumbnail: string
    isError: boolean
    dropzoneStore: ReturnType<typeof useDropzoneStore>
    languageStore: ReturnType<typeof useLanguagesStore>
    backendStore: ReturnType<typeof useBackendStore>
    acl: ReturnType<typeof useACL>
    isSave: boolean

    constructor(record: string, module: string, customData = {}, enableAutoProcess = false, validations = []) {
        this.record = record;
        this.module = module;
        this.customData = customData;
        this.dropzone = null;
        this.enableAutoProcess = enableAutoProcess;
        this.validations = validations;
        this.alreadyDisabled = false;
        this.isError = false;
        this.fileTypesMap = {
            'doc': 'mdi-file-word',
            'docx': 'mdi-file-word',
            'xls': 'mdi-file-excel',
            'xlsx': 'mdi-file-excel',
            'pdf': 'mdi-file-pdf-box',
            'csv': 'mdi-file-delimited',
            'jpg': 'preview',
            'png': 'preview',
            'jpeg': 'preview',
            'gif': 'preview',
        };
        this.defaultThumbnail = 'mdi-file';
        this.dropzoneStore = useDropzoneStore();
        this.languageStore = useLanguagesStore();
        this.backendStore = useBackendStore();
        this.languageStore.fetchModuleLanguage(this.module);
        this.acl = useACL();
        this.isSave = true;
    }

    init(form: string) {
        this.isError = false;
        if (!this.record) {
            console.error('Provided empty record');
            return;
        }
        Dropzone.autoDiscover = false;
        this.dropzone = new Dropzone(form, {
            autoProcessQueue: this.enableAutoProcess,
            addRemoveLinks: true,
            acceptedFiles: this.getAcceptedFiles(),
            maxFilesize: this.backendStore.initData?.upload_maxsize,
            parallelUploads: this.getParallelUploads(),
            createImageThumbnails: false,
            ...this.languageStore.getList('dropzone_labels'),
            url: `/api/files/save`,
            accept: this.onFileAccepted.bind(this),
        });
        this.loadFiles();
        this.setEvents();
    }

    handleThumbnail(file) {
        const [name, ext] = file.name.split(/\.(?=[^\.]+$)/);
        const iconPath = this.fileTypesMap[ext] ?? this.defaultThumbnail;
        file.previewElement.querySelector('.dz-success-mark').remove();
        file.previewElement.querySelector('.dz-error-mark').remove();
        if (iconPath !== 'preview') {
            const icon = document.createElement('i');
            icon.classList.add('mdi');
            icon.classList.add(iconPath);
            file.previewElement.classList.remove('dz-file-preview');
            file.previewElement.querySelector('.dz-image img').remove();
            file.previewElement.querySelector('.dz-image').appendChild(icon);
        }

        if (this.isTextOverflowing(file.previewElement.querySelector('.dz-filename'))) {
            this.addTooltip(file.previewElement.querySelector('.dz-filename'), file);
        }

    }

    private isTextOverflowing(element) {
        return element.scrollWidth > element.clientWidth;
    }

    private addTooltip(element, file) {
        const tooltip = document.createElement('div');
        tooltip.classList.add('dz-filename-tooltip');
        tooltip.innerHTML = file.name;
        element.after(tooltip);
    }

    setEvents() {
        this.dropzone.on("addedfile", this.onAddedFile.bind(this));
        this.dropzone.on("removedfile", this.onRemovedFile.bind(this));
        this.dropzone.on("sending", this.beforeSend.bind(this));
        this.dropzone.on("thumbnail", this.handleThumbnail.bind(this));
    }

    loadFiles() {
        this.dropzoneStore.getFiles(this.module, this.record).then((files) => {
            if (files.length) {
                files.forEach(function (file) {
                        this.isSave = false;
                        this.dropzone.displayExistingFile(file, this.getFileUrl(file.id));
                        file.previewElement.addEventListener('click', this.openPreview.bind(this, file.id));
                        if (!file.canDelete) {
                            file.previewElement.querySelector('.dz-remove').remove();
                        }
                        this.dropzone.files.push(file);
                        this.isSave = true;
                    }.bind(this),
                );
            }

            this.updateDropzoneAppearance();
        });
    }

    async onAddedFile(file) {
        setTimeout(() => {
            this.updateDropzoneAppearance();
        }, 0);
    }

    async onFileAccepted(file, done) {
        if (this.isSave) {
            const resultedFile = await this.dropzoneStore.saveFile(this.module, this.record, file);

            file.id = resultedFile.id;
            file.previewElement.addEventListener('click', this.openPreview.bind(this, file.id))
            if (file.type.includes('image')) {
                file.previewElement.querySelector('.dz-image img').src = this.getFileUrl(file.id, true);
                file.previewElement.querySelector('.dz-image img').alt = file.name;
            }
            if (!resultedFile.canDelete) {
                file.previewElement.querySelector('.dz-remove').remove();
            }
            this.handleThumbnail(file)
            done();
        } else {
            done();
        }
    }

    onRemovedFile(file) {
        this.dropzoneStore.deleteFile(file.id);
        setTimeout(() => {
            this.updateDropzoneAppearance();
        }, 0);
    }

    beforeSend(file, xhr, data) {
        for (const property in this.customData) {
            data.append(property, this.customData[property]);
        }
    }

    openPreview(fileId) {
        window.open(this.getFileUrl(fileId, true), '_blank');
    }

    getFileUrl(fileId, preview = false) {
        return (
            'index.php?entryPoint=download&type=Files&id=' +
            fileId +
            '&time=' +
            Date.now() +
            (preview ? '&preview=yes' : '')
        );
    }

    getAcceptedFiles() {
        return 'image/*,application/pdf,.doc,.docx,.pages,.odt,.rtf/*,.xls,.xlsx,.csv,.ppt,.pptx,.odp,.key';
    }

    getMaxFiles() {
        return 10;
    }

    getParallelUploads() {
        return 10;
    }

    private updateDropzoneAppearance() {
        const dzMessage = this.dropzone?.element?.querySelector('.dz-message');
        if (dzMessage) {
            const fileCount = this.dropzone.files ? this.dropzone.files.length : 0;
            const hasFiles = fileCount > 0;
            if (hasFiles && !dzMessage.classList.contains('smudge-effect')) {
                dzMessage.classList.add('smudge-effect');
            } else if (!hasFiles && dzMessage.classList.contains('smudge-effect')) {
                dzMessage.classList.remove('smudge-effect');
            }
        }
    }

}

export default MintDropzone