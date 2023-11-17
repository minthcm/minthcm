if (typeof PhotosFiles !== 'function') {
    class PhotosFiles extends Files{

        init () {
            super.init();
        }

        getAcceptedFiles() {
            return 'image/*,application/pdf,.doc,.docx,.pages,.odt,.rtf/*,.xls,.xlsx';
        }
    }
    window.PhotosFiles = PhotosFiles;
}