/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM,
 * Copyright (C) 2018-2019 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM"
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo.
 * If the display of the logos is not reasonably feasible for technical reasons, the
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */

let sugarFieldChecklistTimer = 0;
if (typeof SugarFieldChecklist !== 'function') {
    class SugarFieldChecklist {
        constructor() {
            this.nextId = 0;
        }

        setNextId(name){
            if(this.nextId == 0){
               this.nextId= $("div#"+name).children('div').length;
               return;
            }
            this.nextId = ++this.nextId;
        }
        removeTask(task_id,field_name){
            let task = $("#"+task_id);
            if(task.length == 0){
                return;
            }
            task.remove();
            this.updateHiddenField(field_name);
        }
        addTask(name,size = 0,length=0,tabindex='',){
            this.setNextId(name);
            let container = $("div#"+name);
            let div = document.createElement('div');
            div.id = name+'_container_'+this.nextId;
            div.className = "vt_formulaSelector col-xs-12";
            div.style.width = "90%";
            div.style.display = "flex";
            div.style.alignItems = "center";
            div.style.gap = "5px";
            let input = this.createInput(size,length,tabindex,name);

            let span = document.createElement('span');
            span.className = 'suitepicon suitepicon-action-minus';
            let button = this.createButton(name);
            let checkbox = this.createCheckbox(name);
            div.appendChild(input);
            div.appendChild(checkbox);
            div.appendChild(button);
            button.appendChild(span);
            container.append(div);
            
        }

        createButton(name){
            let button = document.createElement('button');
            button.id = 'removeButton_'+this.nextId;
            button.className = 'btn btn-danger email-address-remove-button checklistAdded';
            button.type="button";
            button.style.marginRight = "0px";
            let id = this.nextId;
            button.addEventListener('click', function (event) {
                window.SugarFieldChecklist.removeTask(name+'_container_'+id,name);
             }.bind(this));
             return button;
        }

        createCheckbox(name){
            let checkbox = document.createElement('input')
            checkbox.type = "checkbox";
            checkbox.name = name+"["+this.nextId+"][complete]";
            checkbox.id = name+"_complete_"+this.nextId;
            checkbox.className = 'checklistAdded';
            checkbox.value='1';
            checkbox.style.marginLeft = "2px";
            checkbox.style.marginRight = "2px";
            if($("form#formDetailView").length >0 && module_sugar_grp1 == 'Tasks'){
                checkbox.addEventListener('change', function () {
                    window.SugarFieldChecklist.updateCheckList(name);
                }.bind(this));
            }
            if($("form#formEditView").length >0 || $("form[id^='form_SubpanelQuickCreate_']").length > 0){
                checkbox.addEventListener('change', function () {
                    window.SugarFieldChecklist.updateHiddenField(name);
                }.bind(this));
            }
            return checkbox;
        }

        createInput(size,length,tabindex,name){
            let input = document.createElement('input');
            input.className = "checklist";
            input.type = "text";
            input.name = name+"["+this.nextId+"][task]";
            input.id = name+"_task_"+this.nextId;
            if(size != 0){
                input.size=size;
            }
            if(length != 0){
                input.length=length;
            }
            if(tabindex != ''){
                input.tabindex=tabindex;
            }
            input.addEventListener('change', function () {
                window.SugarFieldChecklist.updateHiddenField(name);
            }.bind(this));
            return input;
        }

        
        updateCheckList(field_name){
            let tasks_array = {};
            let tasks = $("div[field='"+field_name+"']").children('div');
            let complete = true;
            let meter = 0;
            tasks.each(function(index,value){
                let task_array = {};
                
                let task = $("#"+value.id).children();
                let checkbox = task.children();
                var textArea = document.createElement('textarea');
                textArea.innerHTML = task.text();

                task_array['task']=textArea.value.trim();
                if(checkbox.is(':checked')){
                    task_array['complete']=1;
                }else{
                    task_array['complete']=0
                    complete = false;
                }
                tasks_array[meter]=task_array;
                ++meter;
            });

            viewTools.api.callCustomApi({
                module: 'Tasks',
                action: 'updateCheckList',
                async:true,
                dataPOST: {
                    value: JSON.stringify(tasks_array),
                    complete: complete,
                    id: $("input[name='record']").val(),
                },
                callback: function (data) {
                    if(data == false){
                        viewTools.GUI.statusBox.showStatus( SUGAR.language.get( 'app_strings', 'LBL_CHECKLIST_ERROR' ), 'error', 6000 );
                    }
                    let status_input = $("#status");
                    if(status_input.length ==1){
                        status_input.val(data);
                    }
                    let status_div = $("div[field='status']");
                    if(status_div.length ==1){
                        status_div.html(data)
                    }
                }
    
            });
        }
        updateHiddenField(field_name){
            let tasks_array = {};
            let tasks = $("input[id^='" + field_name + "_task_']");
            let meter = 0;
            tasks.each(function(index,value){
                let task_array = {};
                ;
                task_array['task']=value.value.trim();
                let checkbox = $("#"+field_name+"_complete_"+value.id.slice(-1));
                if(checkbox.is(':checked')){
                    task_array['complete']=1;
                }else{
                    task_array['complete']=0;
                }
                tasks_array[meter]=task_array;
                ++meter;
            });
            $("input[name='"+field_name+"']").val(JSON.stringify(tasks_array));
        }
    }
    if (typeof window.SugarFieldChecklist == 'undefined') {
        window.SugarFieldChecklist = new SugarFieldChecklist();
    }
}
$(document).ready(function () {
    SugarFieldChecklistTimer = setInterval(function () {
        if (typeof moment == 'function' && typeof viewTools == 'object') {
            clearInterval(SugarFieldChecklistTimer);

        }
    }, 100);
});