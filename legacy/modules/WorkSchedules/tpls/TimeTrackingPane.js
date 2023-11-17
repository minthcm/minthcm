function getModuleName() {
    var module_name = '';
    if ($("#EditView input[name=module]").length > 0) {
        module_name = $("#EditView input[name=module]").val();
    } else if ($("#formDetailView input[name=module]").length > 0) {
        module_name = $("#formDetailView input[name=module]").val();
    } else if ($("input[name=module]").length > 0) {
        module_name = $("input[name=module]").val();
    }
    return module_name;
}

function fromDbFormat(dbFormat) {
    var date, s, d, t;
    if (dbFormat instanceof Date) {
        date = dbFormat
    } else {
        s = dbFormat.split(' ');
        d = s[0].split('-');
        t = (s[1] || '00:00:00').split(':');
        date = new Date(d[0], d[1] - 1, d[2], t[0], t[1], t[2] || 0);
    }
    return date;
}

function toSugarDate(date_object, as_object) {
    var separator = /\)(.)\(/.exec(date_reg_format)[1],
        isFullYear = /\{(\d)\}/.exec(date_reg_format)[1] == 4,
        yearPos = date_reg_positions['Y'] - 1,
        monthPos = date_reg_positions['m'] - 1,
        dayPos = date_reg_positions['d'] - 1,
        posArr = [],
        y = date_object.getFullYear(),
        m = date_object.getMonth() + 1,
        d = date_object.getDate();

    y = isFullYear ? y : y.substring(2);
    m = m < 10 ? '0' + m : m;
    d = d < 10 ? '0' + d : d;

    if (as_object) {
        return {
            separator: separator,
            isFullYear: !!isFullYear,
            yearPos: yearPos,
            monthPos: monthPos,
            dayPos: dayPos,
            year: y.toString(),
            month: m,
            day: d
        };
    }

    posArr[yearPos] = y;
    posArr[monthPos] = m;
    posArr[dayPos] = d;

    return posArr.join(separator);
}

function toSugarTime(date_object, asObject) {
    var h = date_object.getHours(),
        m = date_object.getMinutes(),
        s = date_object.getSeconds(),
        format12 = (/ap/i).exec(time_reg_format),
        format12Separator = (function () {
            var x = (/\)\s\(/).exec(time_reg_format);
            return x ? x[0] : '';
        })(),
        format12Sufix = (function () {
            if (format12) {
                if (format12[0][0].charCodeAt() == 65)
                    return h < 12 ? 'AM' : 'PM';
                return h < 12 ? 'am' : 'pm';
            }
            return '';
        })();

    if (format12) {
        if (h > 12)
            h -= 12;
        if (h == 0)
            h = 12;
    }
    if (h < 10)
        h = '0' + h;
    m = m < 10 ? '0' + m : m;
    s = s < 10 ? '0' + s : s;

    if (asObject) {
        return {
            format12: !!format12,
            hour: h,
            separator: time_separator,
            minute: m,
            meridiemSeparator: format12Separator,
            meridiem: format12Sufix
        };
    }

    return h + time_separator + m + format12Separator + format12Sufix;
}

if (!window.TimePanel) { // avoid multi-declaration

    TimePanel = function (root) {

        this.index = TimePanel.instances.push(this);
        this.root = $(root);
        this.currentTimes = [];
        this.timeline = {};
        this.inDashlet = true;
        this.taskman = null;
        this.initialize();
    };

    TimePanel.instances = [];

    TimePanel.prototype.getRecordID = function () {
        var record_id = '';
        if ($("input[name=record]").length > 0) {
            record_id = $("input[name=record]").val();
        }
        return record_id;
    };
    TimePanel.prototype.initialize = function () {
        var _this = this;
        _this.inDashlet = getModuleName() == 'Home';
        if (this.inDashlet) {
            var all = document.querySelectorAll('div.TWSDashlet');
            var el = all[all.length - 1];
            if (el) {
                _this.taskman = TWSDashlet.instances.filter(function (d) {
                    return (d.$root !== undefined && d.$root.get(0) === el);
                }).pop();
                if (_this.taskman) {
                    _this.taskman.timeline = _this;
                    _this.taskman.$planSelect.change();
                } else
                    setTimeout(function () {
                        _this.initialize();
                    }, 50);
            } else {
                setTimeout(function () {
                    _this.initialize();
                }, 50);
            }
        } else {
            this.displayTimeline();
        }
    };
    TimePanel.prototype.displayTimeline = async function () {
        try {
            await this.getTimes();
            this.createTimeLine();
            this.createTimeLineItems();
        } catch (err) {
            console.error(err);
        }
    };
    TimePanel.prototype.getPlanId = function () {
        return this.inDashlet ? this.taskman.$planSelect.val() : this.getRecordID();
    };
    TimePanel.prototype.getCurrentPlanData = function () {
        var start, end, plan;

        if (this.inDashlet) {
            plan = this.taskman._currentPlans.filter(function (i) {
                return i.id == this.taskman.$planSelect.val();
            }.bind(this)).pop();
            start = fromDbFormat(plan.date_start);
            start.setMinutes(start.getMinutes() + start.getTimezoneOffset() * -1);
            end = fromDbFormat(plan.date_end);
            end.setMinutes(end.getMinutes() + end.getTimezoneOffset() * -1);
        } else {
            start = this.formatToDBDateTime(getDateObject($('#date_start').val()));
            end = this.formatToDBDateTime(getDateObject($('#date_end').val()));
        }

        return {
            date_start: start,
            date_end: end
        };

    };
    TimePanel.prototype.formatToDBDateTime = function (date_object) {
        var year = date_object.getFullYear();
        var month = date_object.getMonth() + 1;
        var day = date_object.getDate();
        var hours = date_object.getHours();
        var minutes = date_object.getMinutes();
        var seconds = date_object.getSeconds();
        var date_sep = '-';
        var time_sep = ':';

        month = month < 10 ? '0' + month : month;
        day = day < 10 ? '0' + day : day;
        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        return year + date_sep + month + date_sep + day + ' ' + hours + time_sep + minutes + time_sep + seconds;
    };
    TimePanel.prototype.getTimes = async function () {
        const result = await viewTools.api.asyncControllerCall({
            module: 'WorkSchedules',
            action: 'getRelatedTimes',
            dataType: 'json',
            dataGET: {
                record: this.getPlanId()
            },
        })
        this.currentTimes = result.items
    };
    TimePanel.prototype.createTimeLine = function () {
        var p = this.getCurrentPlanData();
        var t = this.timeline;

        t.start = fromDbFormat(p.date_start);
        t.end = fromDbFormat(p.date_end);
        t.minutes = (+t.end - +t.start) / 1000 / 60;
        t.offset = t.start.getTimezoneOffset();

        this.root.find('tr>td.TimePanelLeft').html(toSugarTime(t.start));
        this.root.find('tr>td.TimePanelMiddle').html('&nbsp;');
        this.root.find('tr>td.TimePanelRight').html(toSugarTime(t.end));
    };
    TimePanel.prototype.createTimeLineItems = function () {
        if ($('span#time_tracking_pane').length) {
            $('span#time_tracking_pane').css('display', 'block');
        }
        var parent_el = this.root.find('tr>td.TimePanelMiddle');
        var width = parent_el.width();
        var div = width / this.timeline.minutes;
        var timeline = this.timeline;

        this.currentTimes.forEach(function (i) {
            var start = fromDbFormat(i.date_start);
            var end = fromDbFormat(i.date_end);
            var minutes = (+end - +start) / 1000 / 60;
            /* MintHCM #93842 START */
            var dislpayed_minutes = minutes % 60 < 10 ? '0' + minutes % 60 : minutes % 60;
            /* MintHCM #93842 END */
            var pos = (+start - +timeline.start) / 1000 / 60;
            var left = (pos + timeline.offset * -1) * +div.toFixed(2);
            start.setMinutes(start.getMinutes() + Math.abs(this.timeline.start.getTimezoneOffset()));
            end.setMinutes(end.getMinutes() + Math.abs(this.timeline.start.getTimezoneOffset()));
            if (!i.$el) {
                var css_classes = this.getTimeCellCssClasses(i);
                /* MintHCM #93842 START */
                /* i.$el = $('<span class="' + css_classes + '">' + i.spent_time + '</span>'); */
                i.$el = $('<span class="' + css_classes + '">' + Math.round(minutes / 60 - 0.5) + ':' + dislpayed_minutes + '</span>');
                /* MintHCM #93842 END */
                var task_number_desc = '';
                if (i.spendtime_projecttask_id != undefined && i.spendtime_projecttask_id != '') {
                    task_number_desc = ' #' + i.spendtime_projecttask_id;
                }
                i.$el.attr('title', toSugarTime(start) + ' - ' + toSugarTime(end) + task_number_desc + ' ' + i.description);
                parent_el.append(i.$el);
            }

            i.$el.click(function () {
                MintHCMDynamicPopupView.init(
                    SUGAR.language.get('app_strings', 'LBL_WORKSCHEDULES'),
                    'SpentTime',
                    i.id,
                    {
                        postSaveCallback: function () {
                            SUGAR.mySugar.retrieveDashlet($('.TWSDashlet').parent().parent().find('#dashlet_id').val());
                            return false;
                        }.bind(this),

                    }
                );
            });

            i.$el.css({
                width: Math.ceil(minutes * div) - 2 + 'px',
                left: Math.ceil(left) + 'px'
            });
        }.bind(this));
    };

    TimePanel.prototype.getTimeCellCssClasses = function (i) {
        var classes = [];
        classes.push('time-cell');
        classes.push('orangeColor');
        return classes.join(' ');
    };

    $(window).resize(function () {
        TimePanel.instances.forEach(function (i) {
            i.createTimeLineItems();
        });
    });
}
