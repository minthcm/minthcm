if (typeof Files !== 'function') {
    class Files {

        constructor(record, module, customData = {}, enableAutoProcess = false, validations = []) {
            this.record = record;
            this.module = module;
            this.customData = customData;
            this.dropzone = null;
            this.enableAutoProcess = enableAutoProcess;
            this.validations = validations;
            this.alreadyDisabled = false;
            this.fileTypesMap = {
                'doc': 'file-word.png',
                'docx': 'file-word.png',
                'xls': 'file-excel.png',
                'xlsx': 'file-excel.png',
                'pdf': 'file-pdf.png',
                'jpg': 'preview',
                'png': 'preview',
                'jpeg': 'preview',
                'gif': 'preview',
            };
            this.defaultThumbnail = 'file-default.png';
            this.imagesPath = 'themes/SuiteP/images/';
        }

        init () {
            this.isError = false;
            if (!this.record) {
                console.error('Provided empty record');
                return;
            }
            Dropzone.autoDiscover = false;
            this.dropzone = new Dropzone('#dZUpload', {
                autoProcessQueue: this.enableAutoProcess,
                url: "index.php?entryPoint=SaveFileFromDropzone&module=" + this.module + "&record=" + this.record,
                addRemoveLinks: true, 
                acceptedFiles: this.getAcceptedFiles(),
                maxFilesize: viewTools.formula.getUploadMaxsize(),
                maxFiles: this.getMaxFiles(),
                parallelUploads: this.getParallelUploads(),
                createImageThumbnails: false,
                ...SUGAR.language.languages.app_list_strings.dropzone_labels,
            });
            this.loadFiles();
            this.setEvents();
        }

        handleThumbnail(file) {
            const [name, ext] = file.name.split(/\.(?=[^\.]+$)/);
            const fileSize = file.previewElement.querySelector('.dz-size').innerText;
            const iconPath = this.fileTypesMap[ext] ?? this.defaultThumbnail;
            if (iconPath !== 'preview') {
                file.previewElement.classList.remove('dz-file-preview');
                file.previewElement.querySelector('.dz-image img').src = this.imagesPath + iconPath;
            }
            file.previewElement.title = `${name} (${fileSize})`;
            this.appendNameToThumbnail(file.previewElement, name);
        }

        appendNameToThumbnail(thumbnail, name) {
            if (thumbnail.querySelector('.dz-custom-thumbnail-name')) {
                return;
            }
            const nameDiv = document.createElement('span');
            nameDiv.classList.add('dz-custom-thumbnail-name');
            nameDiv.innerText = name;
            thumbnail.append(nameDiv);
        }

        setEvents () {
            this.dropzone.on("addedfile", this.onAddedFile.bind(this));
            this.dropzone.on("removedfile", this.onRemovedFile.bind(this));
            this.dropzone.on("sending", this.beforeSend.bind(this));
            this.dropzone.on("processing", this.onProcessingFiles.bind(this));
            this.dropzone.on("complete", this.onComplete.bind(this));
            this.dropzone.on("thumbnail", this.handleThumbnail.bind(this));
        }

        onProcessingFiles() {
            this.disableSubmit();
        }
        onComplete() {
            this.enableSubmit();
        }

        disableSubmit() {
            let submitBtn = document.querySelector('#btn_send_documetns');
            if (submitBtn && !this.alreadyDisabled) {
                Object.assign(submitBtn?.style,
                    {
                        pointerEvents: 'none',
                        background: '#ddd',
                        color: '#f05a41',
                    }
                );
            }
            this.alreadyDisabled = true;
        }
        enableSubmit() {
            let submitBtn = document.querySelector('#btn_send_documetns');
            if (submitBtn && this.alreadyDisabled) {
                Object.assign(submitBtn?.style,
                    {
                        pointerEvents: 'auto',
                        background: '#f05a41',
                        color: '#fff',
                    }
                );
            }
            this.alreadyDisabled = false;
        }

        loadFiles () {
            viewTools.api.callCustomApi({
                module: 'Files',
                action: 'getFiles',
                dataPOST: {
                    record: this.record,
                    module_name: this.module,
                    note_type: this.customData?.note_type,
                    candidature_id: this.customData?.candidature_id
                },
                callback: function (files) {
                    if (files.length) {
                        files.forEach(function (file) {
                            this.dropzone.displayExistingFile(file, this.getFileUrl(file.id));
                            file.previewElement.addEventListener("click", this.openPreview.bind(this, file.id));
                            this.dropzone.files.push(file);
                        }.bind(this));
                    }
                }.bind(this)
            });
        }

        onAddedFile (file) {
            this.handleThumbnail(file);
        }

        onRemovedFile (file) {
            viewTools.api.callCustomApi({
                module: 'Files',
                action: 'removeFile',
                dataPOST: { record: file.id }
            });
        }

        beforeSend (file, xhr, data) {
            for (const property in this.customData) {
                data.append(property, this.customData[property]);
            }
        }

        openPreview (fileId) {
            window.open(this.getFileUrl(fileId, true), '_blank');
        }

        getFileUrl (fileId, preview = false) {
            return 'index.php?entryPoint=download&type=Files&id=' + fileId + '&time=' + Date.now()
                + (preview ? '&preview=yes' : '');
        }

        getAcceptedFiles() {
            return 'image/*,application/pdf,.doc,.docx,.pages,.odt,.rtf/*,.xls,.xlsx';
        }

        getMaxFiles() {
            return 10;
        }

        getParallelUploads() {
            return 10;
        }

        canSaveFiles() {
            if (!this.dropzone.getRejectedFiles().length) {
                return true;
            }
            return false;
        }

        save() {
            const valid = this.validateDropzone();
            if(valid == true){
                this.dropzone.processQueue();
                return true;
            }
            if (valid == false) {
                return viewTools.language.get('Notes', 'ERR_FILE_SAVE')
            }
            return valid;
        }

        validateDropzone() {
            if(!this.canSaveFiles()){
                return false;
            }
            let errors = '';
            const validationMap = {
                'validateMinOneFile': this.validateMinOneFile.bind(this),
                'validateOnlyOneFile': this.validateOnlyOneFile.bind(this),
            }
            this.validations.forEach((v) => {
                const result = validationMap[v]();
                if(result !== true) {
                    errors += `${result} `;
                }
            })
            return errors.length ? errors : true;
        }

        validateMinOneFile(){
            if (this.dropzone.files.length < 1) {
                return viewTools.language.get('Notes', 'ERR_MIN_ONE_FILE');
            }
            return true;
        }

        validateOnlyOneFile(){
            if (this.dropzone.files.length !== 1) {
                return viewTools.language.get('Notes', 'ERR_ONLY_ONE_FILE');
            }
            return true;
        }
    }
    window.Files = Files;
}
