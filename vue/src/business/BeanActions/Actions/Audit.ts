import { BeanAction } from '../BeanAction'

export class Audit extends BeanAction {
    public static readonly TITLE = 'LNK_VIEW_CHANGE_LOG'
    public static readonly ICON = 'mdi-history'

    public async execute() {
        window.open(
            `legacy/index.php?module=Audit&action=Popup&record=${this.bean.id}&module_name=${this.bean.module}`,
            `Audit_popup_window_record_${this.bean.id}_module_name_${this.bean.module}`,
            'width=800,height=800,resizable=1,scrollbars=1',
        )
        return true
    }
}
