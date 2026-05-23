if (!window.Button) {
    class Button {

        element

        click(element) {
            this.element = element;
        }
        getRecordID() {
            let record_id = ''
            if ($("#formDetailView > input[name=record]").length > 0) {
                record_id = $("input[name=record]").val()
            } else {
                record_id = $(this.element).parent().parent().find('select').val()
            }
            return record_id
        }
        isButtonInDashlet() {
            return this.element.closest('.dashletPanel') !== null;
        }
        getDashletID() {
            if (this.isButtonInDashlet()) {
                return $(this.element.closest('.dashletPanel')).find('input[name=dashlet_id]').val()
            }
            return null;
        }
    }
    window.Button = Button;
}