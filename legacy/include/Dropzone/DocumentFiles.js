if (typeof DocumentFiles !== 'function') {
    class DocumentFiles extends Files {

        init () {
            super.init();
        }

        getAcceptedFiles() {
            return 'image/*,application/pdf,.doc,.docx,.pages,.odt,.rtf/*,.xls,.xlsx';
        }
    }
    window.DocumentFiles = DocumentFiles;
}