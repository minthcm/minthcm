{*
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
ML
*}

<div id="{$field_name}" class="vt_formulaSelector" {{include file='include/SugarFields/include/formulaInclude.tpl'}}>
<script src='{sugar_getjspath file="include/SugarFields/Fields/Checklist/SugarFieldChecklist.js"}'></script>
{assign var="checklist" value=$fields.checklist.value|json_decode:1}
<div class="col-xs-12">
<button type="button" class="btn btn-danger email-address-add-button" id="widget_add" onclick="window.SugarFieldChecklist.addTask('{$field_name}',{{$displayParams.size|default:30}}{{if !empty($vardef.len)}},{{$vardef.len}}{{/if}}{{if !empty($tabindex)}},{{$tabindex}}{{/if}})" style="width: 38px;height: 32px;padding-right: 5px;padding-top: 10px;padding-left: 5px;padding-bottom: 5px;">
<span class="suitepicon suitepicon-action-plus" style="width: 14px;height: 17px;"></span><span></span>
</button>
</div>
{if empty($checklist) && empty($checklist_qc)}
    <div id="{$field_name}_container_0" class="col-xs-12" style="display: flex;align-items: center;width: 90%;gap: 5px;">
    <input class="checklist" type="text"
            name="{$field_name}[0][task]"
            id="{$field_name}_task_0" 
            size="{{$displayParams.size|default:30}}"
            {{if !empty($vardef.len)}}maxlength='{{$vardef.len}}'{{/if}}
            value='{$task.task|@html_entity_decode}' {{if !empty($tabindex)}} tabindex="{{$tabindex}}" {{/if}}
            onchange="window.SugarFieldChecklist.updateHiddenField('{$field_name}');"
            style="margin-right: 4px;"
        >
        <input type="checkbox" 
            id="{{$field_name}}_complete_0" 
            name="{$field_name}[0][complete]"
            value="1"
            {if !empty($task.complete) && $task.complete == 1}
                checked
            {/if}
            onchange="window.SugarFieldChecklist.updateHiddenField('{$field_name}');"
        >
        <button type="button" id="removeButton_0" class="btn btn-danger email-address-remove-button" onclick="window.SugarFieldChecklist.removeTask('checklist_container_0','{$field_name}')" style="margin-right: 0px;">
        <span class="suitepicon suitepicon-action-minus"></span>
        </button>
    </div>
{/if}
{if empty($checklist) && !empty($checklist_qc)}
    {assign var="checklist" value=$checklist_qc|json_decode:1}
{/if}
{$checklist_qc}
{foreach from=$checklist item=task key=key}
        <div id="{$field_name}_container_{$key}" style="display: flex;align-items: center;width: 90%;gap: 5px;" >
            <input class="checklist" type="text"  
                name="{$field_name}[{$key}][task]"
                id="{$field_name}_task_{$key}" 
                size="{{$displayParams.size|default:30}}"
                {{if !empty($vardef.len)}}maxlength='{{$vardef.len}}'{{/if}}
                value='{$task.task}' {{if !empty($tabindex)}} tabindex="{{$tabindex}}" {{/if}}
                onchange="window.SugarFieldChecklist.updateHiddenField('{$field_name}');"
            >
            <input  type="checkbox" 
                id="{{$field_name}}_complete_{$key}" 
                name="{$field_name}[{$key}][complete]"
                value="1"
                onchange="window.SugarFieldChecklist.updateHiddenField('{$field_name}');"
                {if $task.complete == 1}
                    checked
                {/if}
                
            >
            <button type="button" id="removeButton_{$key}" class="btn btn-danger email-address-remove-button" onclick="window.SugarFieldChecklist.removeTask('checklist_container_{$key}','{$field_name}')" >
            <span class="suitepicon suitepicon-action-minus"></span>
            </button>
        </div>
{/foreach}

</div>
<input type="hidden" name="{$field_name}" id="{$field_name}" value='{$fields.checklist.value}'>