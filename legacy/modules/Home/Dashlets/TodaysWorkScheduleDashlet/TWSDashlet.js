if (!window.TWSDashlet) {
    TWSDashlet = function (innerEl) {
        var _this = this;
        this._currentPlans = [];
        this._currentTasks = [];
        this._currentTimes = [];
        this.index = TWSDashlet.instances.push(this) - 1;
        this.timeline = null;
        this.storageKeyDate = 'TWSDashlet_userdate';
        this.storageKeyPlanId = 'TWSDashlet_plan_id';
        this.initialDateChange = false;
        this.$root = $(innerEl);
        this.$dashlet = this.$root.closest('div[id^=dashlet_entire]');
        this.$toolbar = this.$root.find('div.TWSToolbar');
        this.$toolForm = this.$toolbar.find('form.TWSToolbarForm');
        if (!this.$toolForm) {
            this.$toolForm = $(document).find('form.TWSToolbarForm');
        }
        this.$dateInput = this.$toolForm.find('input.date_input');
        this.$dateCalendar = this.$dateInput.next();
        this.$calendarNext = this.$toolForm.find('.calendar_next');
        this.$calendarBefore = this.$toolForm.find('.calendar_before');
        this.$planSelect = this.$toolForm.find('select');
        this.$showPlanButton = this.$toolForm.find('img.showPlanButton');
        this.$editPlanButton = this.$toolForm.find('img.editPlanButton');
        this.$logTimeButton = this.$toolForm.find('img.logTimeButton');
        this.$CloseButton = this.$toolForm.find('#CloseButton');
        this.$content = this.$root.find('table.TWSList');
        this.$listBody = this.$root.find('.TWSListBody');
        this.$foot = this.$root.find('.TWSListFooter');
        this.current_user_is_admin = ($('#current_user_is_admin').val() == '1');
        this.current_user_id = $('#current_user_id').val();
        this.$dateInput.change(async function () {
            _this.$content.hide();
            await _this.changeDisplayByDate(getDateObject(this.value));
        });
        this.$dateInput.blur(async function () {
            _this.$content.hide();
            await _this.changeDisplayByDate(getDateObject(this.value));
        });
        this.$calendarBefore.click(async function () {
            var current_date = getDateObject(_this.$dateInput.val());
            var next_date = current_date.setDate(current_date.getDate() - 1);
            var finished_date = new Date(next_date);
            var sugar_date = toSugarDate(finished_date);
            _this.$dateInput.val(sugar_date);
            _this.$content.hide();
            await _this.changeDisplayByDate(getDateObject(sugar_date));

        });
        this.$calendarNext.click(async function () {
            var current_date = getDateObject(_this.$dateInput.val());
            var next_date = current_date.setDate(current_date.getDate() + 1);
            var finished_date = new Date(next_date);
            var sugar_date = toSugarDate(finished_date);
            _this.$dateInput.val(sugar_date);
            _this.$content.hide();
            await _this.changeDisplayByDate(getDateObject(sugar_date));

        });
        this.$planSelect.change(async function () {
            var items = [];
            if (!this.value) {
                _this.$content.hide();
                localStorage.removeItem(_this.storageKeyPlanId);
                _this.$showPlanButton.parent().hide();
                _this.$editPlanButton.parent().hide();
                _this.$logTimeButton.parent().hide();
                _this.$CloseButton.parent().hide();
            } else {
                _this.$content.show();
                var isClosed = _this.getCurrentPlanValue('status') == 'closed';
                _this.$listBody.find('td>div')[isClosed ? 'hide' : 'show']();
                if (_this.timeline) {
                    _this.showLoading()
                    await _this.timeline.displayTimeline()
                    _this.closeLoading()
                    // MT: do czego ten delay 500ms?
                    // _this.delay(500).then(_this.timeline.displayTimeline.bind(_this.timeline));
                }
                _this.$showPlanButton.parent().show();
                _this.$editPlanButton.parent().show();
                _this.$logTimeButton.parent()[isClosed ? 'hide' : 'show']();
                _this.$CloseButton.parent()[isClosed ? 'hide' : 'show']();
                _this.$listBody.droppable('option', 'disabled', isClosed);
                _this.$listBody.sortable('option', 'disabled', isClosed);
            }
            return items;
        });
        this.$showPlanButton.click(function () {
            var id = _this.$planSelect.val();
            if (id == '' || id == null) {
                alert(SUGAR.language.get('app_strings', 'LBL_CHOOSE_PLAN'));
            } else {
                url = 'index.php?action=DetailView&module=WorkSchedules&' + 'record=' + id;
                window.open(url);
            }
        });
        this.$editPlanButton.click(function () {
            var id = _this.$planSelect.val();
            if (id == '' || id == null) {
                alert(SUGAR.language.get('app_strings', 'LBL_CHOOSE_PLAN'));
            } else {
                url = 'index.php?action=EditView&module=WorkSchedules&' + 'record=' + id;
                window.open(url);
            }
        });
        this.$logTimeButton.click(async function () {
            if (_this.$planSelect.val()) {
                var planId = _this.$planSelect.val() || '';
                if (!await TWSDashlet.checkIfUserCanAddTimeToWorkSchedule(_this.$dateInput.val(), planId)) {
                    alert(SUGAR.language.get('app_strings', 'LBL_CANNOT_ADD_TIME_FOR_PREV_MONTHS'));
                } else {
                    var planName = _this.getCurrentPlanValue('name') || '';

                    MintHCMDynamicPopupView.init(        
                        SUGAR.language.get('app_strings', 'LBL_WORKSCHEDULES'),
                        'SpentTime',
                        "",
                        {
                        "fields" : 
                            {
                            "workschedule_id":planId,
                            "workschedule_name":planName
                            },
                            postSaveCallback: function () {
                                SUGAR.mySugar.retrieveDashlet( $('#dashlet_id').val() );
                                return false;
                            }.bind(this),
                        
                        }
                    );
                }
            } else {
                alert(SUGAR.language.get('app_strings', 'LBL_PLAN_NOT_CHOOSED'));
            }
        });
        this.initListBody();
        this.initInstance();
    };

    TWSDashlet.ATTR_TAKS_ID = 'task-id';
    TWSDashlet.initialized = false;
    TWSDashlet.instances = [];
    TWSDashlet.planStatusDom = SUGAR.language.languages.app_list_strings.workschedules_status_dom;
    TWSDashlet.planTypeDom = SUGAR.language.languages.app_list_strings.workschedules_status_dom;
    TWSDashlet.taskStatusDom = SUGAR.language.languages.app_list_strings.project_task_status_options;
    TWSDashlet.init = function () {
        TWSDashlet.initialized = true;
    };
    TWSDashlet.get = function (index) {
        return TWSDashlet.instances[index];
    };
    TWSDashlet.checkIfUserCanAddTimeToWorkSchedule = async function (date, workschedule_id) {
        if(typeof TWSDashlet.checkIfUserCanAddTimeToWorkSchedule_cache == 'undefined'){
            TWSDashlet.checkIfUserCanAddTimeToWorkSchedule_cache = {}
        }
        if(TWSDashlet.checkIfUserCanAddTimeToWorkSchedule_cache[workschedule_id] == undefined){
            TWSDashlet.checkIfUserCanAddTimeToWorkSchedule_cache[workschedule_id] = await viewTools.api.asyncApiCall({
                module: 'SpentTime',
                action: 'canLogTimeToPast',
                format: 'JSON',
                dataPOST: { workschedule_id },
            });
        }
        return TWSDashlet.checkIfUserCanAddTimeToWorkSchedule_cache[workschedule_id]
    }
    TWSDashlet.listItemTemplate = function () {
        return $('#TWSDashletListItemTemplate').html();
    };
    TWSDashlet.parseTemplate = function (params) {
        var template = TWSDashlet.listItemTemplate(),
            key, re;
        for (key in params || {}) {
            re = new RegExp('{{' + key + '}}', 'g');
            template = template.replace(re, params[key]);
        }
        return template;
    };
    TWSDashlet.prototype.showLoading = function () {
        const dashletContainer = this.$root[0]
        if (!dashletContainer || dashletContainer.querySelector('.twsdashlet-loader')) {
            return
        }
        dashletContainer.style.position = 'relative'
        const dashletLoader = document.createElement('div')
        dashletLoader.classList.add('twsdashlet-loader')
        dashletLoader.innerHTML = "<img src='themes/default/images/loading.gif' alt='loading'>"
        dashletContainer.appendChild(dashletLoader)
    }
    TWSDashlet.prototype.closeLoading = function () {
        const dashletContainer = this.$root[0]
        if (dashletContainer) {
            dashletContainer.style.position = null
            dashletContainer.querySelector('.twsdashlet-loader')?.remove()
        }
    }
    TWSDashlet.prototype.jQueryAsyncCall = function (props) {
        this.showLoading()
        return new Promise((resolve, reject) => {
            $.ajax({
                ...props,
                async: true,
                success: (response) => {
                    this.closeLoading()
                    resolve(response)
                },
                error: (err) => {
                    this.closeLoading()
                    reject(err)
                },
            })
        })
    }
    TWSDashlet.prototype.delay = function (msec) {
        return this.promise(function (success) {
            setTimeout(success.bind(null, arguments.callee.returned), msec);
        });
    }
    TWSDashlet.prototype.initInstance = async function () {
        var userDate = Date(),
            date = userDate ? new Date(userDate) : new Date;
        this.initialDateChange = true;

        var dashlet_loaded_before = await this.jQueryAsyncCall({
            url: 'index.php?module=WorkSchedules&action=wasDasheltLoadedBefore&to_pdf=1'
        });
        var sugar_date = '';


        if (dashlet_loaded_before.status !== true) {
            sessionStorage.setItem('cookie_date', moment(date).format("YYYY-MM-DD"));
        }

        if (sessionStorage.getItem('cookie_date') !== '' && sessionStorage.getItem('cookie_date') !== null) {
            date = new Date(sessionStorage.getItem('cookie_date'));
            sugar_date = toSugarDate(date, false);
        } else {
            sugar_date = toSugarDate(date, false);
        }
        this.$dateInput.val(sugar_date).trigger('change');
    };
    TWSDashlet.prototype.getCurrentPlanValue = function (key) {
        var planId = this.$planSelect.val(),
            val = this._currentPlans.filter(function (el) {
                return el.id == planId
            }).pop();
        return (val && val[key]) || void 0;
    };
    TWSDashlet.prototype.initListBody = function () {
        var _this = this;
        this.$listBody.sortable({
            update: async function () {
                await _this.updateTasksOrder();
            },
            helper: function (e, tr) {
                var $originals = tr.children(),
                    $helper = tr.clone();
                $helper.children().each(function (index) {
                    $(this).width($originals.eq(index).width());
                });
                return $helper;
            }
        }).droppable({
            activeClass: 'ui-state-hover',
            drop: function (t, ui) {
                var draggable = ui.draggable,
                    taskId;
                var go_back_animate = function (ui) {
                    ui.helper.clone().appendTo('body')
                        .animate(draggable.offset(), "slow", function () {
                            $(this).remove();
                        });
                }
                if (draggable.get(0).nodeName === 'B') {
                    taskId = location.getParam('record', draggable.children('[href]').attr('href'));
                    if (!~_this._currentTasks.map(function (el) {
                        return el.id
                    }).indexOf(taskId)) {
                        _this.canPushTask(taskId).then((result) => {
                            if (!result) {
                                go_back_animate(ui);
                            }
                        })
                    } else {
                        go_back_animate(ui);
                    }
                }
            }

        });
    };
    TWSDashlet.prototype.clear = function () {
        this.$listBody.html('');
        this.$content.hide();
        this.$planSelect.html('');
    };
    TWSDashlet.prototype.promise = function (f) {
        f = f || function (s) {
            s()
        };

        return this instanceof Promise ? this.then(_promise) : _promise(void 0);

        function _promise(r) {
            f.returned = r;
            return new Promise(f);
        }
    };
    TWSDashlet.prototype.calculateNextPreviousDate = function (date, type) {

    };
    TWSDashlet.prototype.changeDisplayByDate = async function (date) {
        var _this = this;
        var items = [];
        if (date instanceof Date && date.toString() !== "Invalid Date") {
            localStorage.setItem(_this.storageKeyDate, date);

            var date_formatted = moment(date).format("YYYY-MM-DD");
            if (sessionStorage.getItem('cookie_date') !== date_formatted) {
                sessionStorage.setItem('cookie_date', date_formatted);
                date = new Date(sessionStorage.getItem('cookie_date'));
            }

            var result = await _this.getPlansListForDay(date);
            items = result.items;
            items = _this.createPlanSelectOptions(items);
            if (items.length == 1) {
                _this.$planSelect
                    .find('option:first').remove().end()
                    .find('option:last').prop('selected', true).end()
                    .trigger('change');
            }
            _this._currentPlans = items;
            if (_this.initialDateChange) {
                var planId = localStorage.getItem(_this.storageKeyPlanId);
                if (planId) {
                    _this.$planSelect.val(planId).trigger('change');
                }
            }
        }
        return items;
    };
    TWSDashlet.prototype.createPlanSelectOptions = function (items) {
        this.$planSelect.html('');
        this.$planSelect.append(new Option('', ''));
        items.forEach(function (item) {
            var n = item.name + ' - ' + TWSDashlet.planStatusDom[item.status];
            this.$planSelect.append(new Option(n, item.id));
        }.bind(this));
        return items;
    };
    TWSDashlet.prototype.toDbDate = function (date) {
        var y = date.getFullYear();
        var m = date.getMonth() + 1; // months start form 0
        var d = date.getDate();
        var x = '-';

        m = m < 10 ? '0' + m : m;
        d = d < 10 ? '0' + d : d;
        return y + x + m + x + d;
    };
    TWSDashlet.prototype.getPlansListForDay = async function (date, userId) {
        var _this = this;
        userId = !userId ? TWSDashlet.current_user_id : userId;
        var result = {};
        const result_json = await this.jQueryAsyncCall({
            url: "index.php?module=WorkSchedules&user_id=" + userId + "&action=getPlansListForDashlet&sugar_body_only=1&date=" + _this.toDbDate(date) + "&sugar_body_only=1",
            dataType: "json",
        });
        if (!(result_json && result_json.items instanceof Array)) {
            console.error('Error while loading related redmine tasks');
        } else {
            result_json.items = result_json.items.sort(function (a, b) {
                if (a.lp > b.lp)
                    return 1;
                if (a.lp < b.lp)
                    return -1;
                return 0;
            });
            result = result_json;
        }
        return result;
    };
    TWSDashlet.prototype.getPlanDetailsById = async function (id) {
        localStorage.setItem(this.storageKeyPlanId, id);
        var result;
        const result_json = await this.jQueryAsyncCall({
            url: "index.php?module=WorkSchedules&record=" + id + "&action=getPlanDetailsForDashlet&sugar_body_only=1",
            dataType: "json",
        })
        if (!(result_json && result_json.items instanceof Array)) {
            console.error('Error while loading related redmine tasks');
        } else {
            result_json.items = result_json.items.sort(function (a, b) {
                if (a.ord > b.ord) {
                    return 1;
                }
                if (a.ord < b.ord) {
                    return -1;
                }
                return 0;
            });
            result = result_json;
        }
        return result;
    };
    TWSDashlet.prototype.createListItem = async function (item) {
        var planId = this.$planSelect.val();
        var display = '';
        if (!await TWSDashlet.checkIfUserCanAddTimeToWorkSchedule(this.$dateInput.val(), planId)) {
            display = 'display: none;';
        }

        var planName = this.getCurrentPlanValue('name');
        var html = TWSDashlet.parseTemplate({
            attr_taks_id: TWSDashlet.ATTR_TAKS_ID,
            index: this.index || 0,
            planId: planId,
            planName: planName,
            id: item.id,
            name: item.name,
            name_link: item.name.replace(/&#039;/g, '%2527').replace(/#/g, '%2523').replace(/\?/g, '%253F'),
            status: TWSDashlet.taskStatusDom[item.status],
            issue_tracker: item.issue_tracker,
            priority: item.priority,
            project_name: item.project_name,
            display: display,
        });
        return $(html).find('tr').get(0);
    };
    TWSDashlet.prototype.addItemToPlanTaskList = async function (item) {
        this.$listBody.append(await this.createListItem(item));
    };
    TWSDashlet.prototype.removeItemFromPlanTaskList = async function (taskId) {
        return await this.doTaskAction(taskId, 'removeTasksFromPlanForDashlet');
    };
    TWSDashlet.prototype.createPlanTaskList = async function (items) {
        this.$listBody.html('');
        for (item of items) {
            await this.addItemToPlanTaskList(item)
        }
        if (items.length == 0)
            this.$listBody.html('<tr><td colspan="6"></td></tr>');
        return items;
    };
    TWSDashlet.prototype.getCurrentTasksOrder = function () {
        var order = {};
        this.$listBody.children().each(function (i) {
            order[i] = this.getAttribute(TWSDashlet.ATTR_TAKS_ID);
        });
        return order;
    };
    TWSDashlet.prototype.updateTasksOrder = async function () {
        var _this = this;
        const result = await this.jQueryAsyncCall({
            url: "index.php?module=WorkSchedules&record=" + _this.$planSelect.val() + "&action=updateTasksOrderForDashlet&tasks_order=" + _this.getCurrentTasksOrder() + "&sugar_body_only=1",
            dataType: "text",
        })
        if (result !== '1') {
            console.error('Error while updating order: Unknown response: ' + result);
        }
    };
    TWSDashlet.prototype.doTaskAction = async function (taskId, action) {
        var _this = this;
        var errmsg;
        const result = await this.jQueryAsyncCall({
            url: "index.php?module=WorkSchedules&record=" + _this.$planSelect.val() + "&action=" + action + "&task_id=" + taskId + "&sugar_body_only=1",
            dataType: "text",
        })
        if (result === '1') {
            _this.$planSelect.trigger('change');
        } else {
            errmsg = 'Error while doing action (' + action + ') on task (' + taskId + '): ';
            console.error('Unknown response: ' + result + ' ' + errmsg);
        }
    };
    TWSDashlet.prototype.pushTask = async function (taskId) {
        return await this.doTaskAction(taskId, 'addTasksToPlanForDashlet');
    };
    TWSDashlet.prototype.canPushTask = async function (taskId) {
        var _this = this;
        var response = false;
        const result = await this.jQueryAsyncCall({
            url: "index.php?module=WorkSchedules&record=" + _this.$planSelect.val() + "&action=canAddTaskToPlan&task_id=" + taskId + "&sugar_body_only=1",
            dataType: "text",
        })
        if (result !== '1') {
            viewTools.GUI.statusBox.showStatus(SUGAR.language.get('app_strings', 'LBL_WRONG_TASK_FOR_PLAN'), 'error', 5000);
        } else {
            await _this.pushTask(taskId);
            response = true;
        }
        return response;
    };
    $(function () {
        if (document.readyState == 'complete') {
            if (!TWSDashlet.initialized) {
                TWSDashlet.init();
            }
            document.body.style.overflow = "auto";
            document.body.onmousewheel = null;
        } else {
            setTimeout(arguments.callee, 50);
        }
    });
}
if (typeof location.getParam != "function") {
    location.getParam = function (name, url) {
        if (!url) {
            url = window.location.href;
        }
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) {
            return null;
        }
        if (!results[2]) {
            return '';
        }
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
}
