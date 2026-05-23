if (typeof KudosCommonView !== "function") {
    class KudosCommonView {
        init() {
            this.handleAnonymous();
        }
        getField(field_id) {
            let field = $("#" + this.form + " #" + field_id);
            if (field.length == 0) {
                field = $("#" + this.form + " [name=" + field_id + "]");
            }
            if (field.length == 0) {
                field = false;
            } else {
                field = field.first();
            }
            return field;
        }
        handleAnonymous() {
            let anonymous = this.getField("anonymous");
            if (!anonymous) {
                return;
            }
            anonymous.on("change", function () {
                this.blurAssignedUserName();
            }.bind(this));
        }
        blurAssignedUserName(){
            let assigned_user_name = this.getField("assigned_user_name");
            if (!assigned_user_name) {
                return;
            }
            assigned_user_name.blur();
        }
    }
    if (typeof window.KudosCommonView == "undefined") {
        window.KudosCommonView = KudosCommonView;
    }
}
