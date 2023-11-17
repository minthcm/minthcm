class Kanban {
    constructor(defs, module) {
        this.defs = {
            ...defs,
            user_date_format: viewTools.date.getDateFormat(),
            user_time_format: viewTools.date.getTimeFormat(),
            current_user: window.current_user
        };
        this.module = module;
        this.component = document.getElementsByTagName('kanban-view')[0].vueComponent;
    }
    init () {
        this.prepareColumns();
        this.setEvents();
        this.loadItems();
        this.component.$data.defs = this.defs;
    }
    prepareColumns () {
        if (this.defs.black_list) {
            this.defs.black_list.forEach(function (key) {
                delete this.defs.columns[key];
            }.bind(this));
        }
    }
    setEvents () {
        this.component.$on('item-click', this.handleItemClick.bind(this));
        this.component.$on('item-add', this.handleItemAdd.bind(this));
        this.component.$on('item-change', this.handleItemChange.bind(this));
        this.component.$on('open-fullscreen', this.handleOpenFullscreen.bind(this));
    }
    handleItemClick (item) {
        if(item.id == undefined){
            item = {
                id:item,
            };
        }

        MintHCMDynamicPopupView.init(
            viewTools.language.get(this.module, 'LBL_MODULE_NAME'),
            this.module,
            item.id,
            {
                postSaveCallback: function () {
                    this.loadItems()
                }.bind(this),
                isDetailView: true,
            }
        );
    }
    handleItemAdd (status) {
        MintHCMDynamicPopupView.init(
            viewTools.language.get(this.module, 'LBL_MODULE_NAME'),
            this.module,
            null,
            {
                fields: {
                    [this.defs.columns_field]: status
                },
                postSaveCallback: function () {
                    this.loadItems()
                }.bind(this)
            }
        );
    }
    handleItemChange (itemID, oldOrder, newOrder, oldStatus, newStatus) {
        let fieldsToFill = this.checkRequiredFields(newOrder, oldStatus, newStatus);
        if (!fieldsToFill.length) {
            this.saveItem(itemID, oldOrder, newOrder, oldStatus, newStatus);
        } else {
            this.showPopupViewWithRequiredFields(itemID, oldOrder, newOrder, oldStatus, newStatus, fieldsToFill);
        }
    }
    showPopupViewWithRequiredFields (itemID, oldOrder, newOrder, oldStatus, newStatus, fieldsToFill) {
        MintHCMDynamicPopupView.init(
            viewTools.language.get(this.module, 'LBL_MODULE_NAME'),
            this.module,
            itemID,
            {
                onShow: this.onPopupViewWithRequiredFieldsShow.bind(this, oldOrder, newOrder, oldStatus, newStatus, fieldsToFill),
                postSaveCallback: function () {
                    this.loadItems()
                }.bind(this)
            }
        );
    }
    onPopupViewWithRequiredFieldsShow (oldOrder, newOrder, oldStatus, newStatus, fieldsToFill) {
        YAHOO.util.Event.onContentReady(
            fieldsToFill[0],
            function () {
                this.getKanbanRequiredFieldsErrorMessage(fieldsToFill);
                let form = document.getElementById(MintHCMDynamicPopupView.formname);
                form[this.defs.columns_field].value = newStatus;
                $('#' + MintHCMDynamicPopupView.popup.id + '_close').click(
                    this.restoreAfterError.bind(this, oldOrder, newOrder, oldStatus, newStatus)
                );
                $('#' + MintHCMDynamicPopupView.popup.id + ' input#' + MintHCMDynamicPopupView.popup.buttons[1].text).click(
                    this.restoreAfterError.bind(this, oldOrder, newOrder, oldStatus, newStatus)
                );
                viewTools.form.calculateSelectors();
                MintHCMDynamicPopupView.save.call(MintHCMDynamicPopupView);
            }.bind(this)
        )
    }
    getKanbanRequiredFieldsErrorMessage (fieldsToFill) {
        viewTools.GUI.statusBox.showStatus(
            viewTools.language.get(this.module, 'LBL_KANBAN_REQUIRED_FIELDS_ERROR') +
            fieldsToFill.map(function (fieldName) {
                return viewTools.language.get(this.module, 'LBL_' + fieldName.toUpperCase());
            }.bind(this)).join(", "),
            'error',
            5000
        );
    }
    saveItem (itemID, oldOrder, newOrder, oldStatus, newStatus) {
        viewTools.api.callController({
            module: this.module,
            action: 'kanbanView',
            dataPOST: {
                function_name: 'saveItem',
                id: itemID,
                fields: {
                    [this.defs.columns_field]: newStatus,
                    [this.defs.order_field]: newOrder
                }
            },
            callback: function (result) {
                result = result ? JSON.parse(result) : false;
                if (result) {
                    this.updateItem(result, newOrder, newStatus);
                    this.loadItems();
                } else {
                    this.showError();
                    this.restoreAfterError(oldOrder, newOrder, oldStatus, newStatus);
                }
            }.bind(this)
        });
    }
    checkRequiredFields (newOrder, oldStatus, newStatus) {
        let fieldsToFill = [];
        if (this.defs.required_fields && newStatus !== oldStatus &&
            Object.keys(this.defs.required_fields).indexOf(newStatus) !== -1) {
            const item = this.component.$data.items[newStatus][(newOrder - 1)];
            this.defs.required_fields[newStatus].forEach(function (requiredField) {
                if (!item[requiredField]) {
                    fieldsToFill.push(requiredField);
                }
            }.bind(this))
        }
        return fieldsToFill;
    }
    loadItems () {
        this.component.$data.items = {};
        viewTools.api.callController({
            module: this.module,
            action: 'kanbanView',
            dataPOST: { function_name: 'getItems' },
            callback: function (items) {
                this.component.$data.items = JSON.parse(items);
            }.bind(this)
        });
    }
    restoreAfterError (oldOrder, newOrder, oldStatus, newStatus) {
        let temp = this.component.$data.items;
        let item = temp[newStatus].splice((newOrder - 1), 1);
        temp[oldStatus].splice((oldOrder - 1), 0, item[0]);
        this.component.$data.items = temp;
    }
    updateItem (item, order, status) {
        let temp = this.component.$data.items;
        temp[status].splice((order - 1), 1, item);
        this.component.$data.items = temp;
    }
    showError () {
        viewTools.GUI.statusBox.showStatus(
            viewTools.language.get('app_strings', 'LBL_KANBAN_SAVING_ERROR'),
            'error',
            2000
        );
    }
    handleOpenFullscreen (item) {
        const url = `index.php?module=${item.module_name}&action=DetailView&record=${item.id}`;
        window.open(url, '_blank').focus();
    }
}
window.Kanban = Kanban;
