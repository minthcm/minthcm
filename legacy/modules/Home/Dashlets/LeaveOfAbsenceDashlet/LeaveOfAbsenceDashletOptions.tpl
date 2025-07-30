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
* Copyright (C) 2018-2024 MintHCM
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

*}


<div style='width: 500px; z-index:10;'>
    <form name='configure_{$id}' action="index.php" method="post" onSubmit='return SUGAR.dashlets.postForm( "configure_{$id}", SUGAR.mySugar.uncoverPage );'>
        <input type='hidden' name='id' value='{$id}'>
        <input type='hidden' name='module' id='cal_module' value='Home'>
        <input type='hidden' name='action' value='ConfigureDashlet'>
        <input type='hidden' name='to_pdf' value='true'>
        <input type='hidden' name='configure' value='true'>
        <table width="400" cellpadding="0" cellspacing="0" border="0" class="edit view" align="center">
            <tr>
                <td valign='top' nowrap class='dataLabel'>{$DASHLET_STRINGS.LBL_CONFIGURE_TITLE}</td>
                <td valign='top' class='dataField'>
                    <input type="text" class="text" name="title" size='20' value='{$title}'>
                </td>
            </tr>
            <tr>
                <td valign='top' nowrap class='dataLabel'>{$DASHLET_STRINGS.LBL_SHOW_DAYS_OF_WEEK}</td>
                <td valign='top' class='dataField'>
                    <input type="checkbox" id="show_sunday" name="show_sunday" value="true" {if $show_days_of_week.sunday == true}checked{/if}>
                    <label for="show_sunday">{$DASHLET_STRINGS.LBL_SUNDAY}</label>
                    <br/>
                    <input type="checkbox" id="show_monday" name="show_monday" value="true" {if $show_days_of_week.monday == true}checked{/if}>
                    <label for="show_monday">{$DASHLET_STRINGS.LBL_MONDAY}</label>
                    <br/>
                    <input type="checkbox" id="show_tuesday" name="show_tuesday" value="true" {if $show_days_of_week.tuesday == true}checked{/if}>
                    <label for="show_tuesday">{$DASHLET_STRINGS.LBL_TUESDAY}</label>
                    <br/>
                    <input type="checkbox" id="show_wednesday" name="show_wednesday" value="true" {if $show_days_of_week.wednesday == true}checked{/if}>
                    <label for="show_wednesday">{$DASHLET_STRINGS.LBL_WEDNESDAY}</label>
                    <br/>
                    <input type="checkbox" id="show_thursday" name="show_thursday" value="true" {if $show_days_of_week.thursday == true}checked{/if}>
                    <label for="show_thursday">{$DASHLET_STRINGS.LBL_THURSDAY}</label>
                    <br/>
                    <input type="checkbox" id="show_friday" name="show_friday" value="true" {if $show_days_of_week.friday == true}checked{/if}>
                    <label for="show_friday">{$DASHLET_STRINGS.LBL_FRIDAY}</label>
                    <br/>
                    <input type="checkbox" id="show_saturday" name="show_saturday" value="true" {if $show_days_of_week.saturday == true}checked{/if}>
                    <label for="show_saturday">{$DASHLET_STRINGS.LBL_SATURDAY}</label>
                </td>
            </tr>
            <tr>
                <td valign='top' nowrap class='dataLabel'>{$DASHLET_STRINGS.LBL_SHOW_TYPE_OF_ABSENCE}</td>
                <td valign='top' class='dataField'>
                    <input type="checkbox" id="show_home" name="show_home" value="true" {if $show_type_of_absence.home == true}checked{/if}>
                    <label for="show_home">{$types_of_absence.home}</label>
                    <br/>
                    <input type="checkbox" id="show_sick" name="show_sick" value="true" {if $show_type_of_absence.sick == true}checked{/if}>
                    <label for="show_sick">{$types_of_absence.sick}</label>
                    <br/>
                    <input type="checkbox" id="show_holiday" name="show_holiday" value="true" {if $show_type_of_absence.holiday == true}checked{/if}>
                    <label for="show_holiday">{$types_of_absence.holiday}</label>
                    <br/>
                    <input type="checkbox" id="show_sick_care" name="show_sick_care" value="true" {if $show_type_of_absence.sick_care == true}checked{/if}>
                    <label for="show_holiday">{$types_of_absence.sick_care}</label>
                    <br/>
                    <input type="checkbox" id="show_delegation" name="show_delegation" value="true" {if $show_type_of_absence.delegation == true}checked{/if}>
                    <label for="show_holiday">{$types_of_absence.delegation}</label>
                    <br/>
                    <input type="checkbox" id="show_occasional_leave" name="show_occasional_leave" value="true" {if $show_type_of_absence.occasional_leave == true}checked{/if}>
                    <label for="show_holiday">{$types_of_absence.occasional_leave}</label>
                    <br/>
                    <input type="checkbox" id="show_leave_at_request" name="show_leave_at_request" value="true" {if $show_type_of_absence.leave_at_request == true}checked{/if}>
                    <label for="show_holiday">{$types_of_absence.leave_at_request}</label>
                    <br/>
                    <input type="checkbox" id="show_overtime" name="show_overtime" value="true" {if $show_type_of_absence.overtime == true}checked{/if}>
                    <label for="show_holiday">{$types_of_absence.overtime}</label>
                    <br/>
                    <input type="checkbox" id="show_excused_absence" name="show_excused_absence" value="true" {if $show_type_of_absence.excused_absence == true}checked{/if}>
                    <label for="show_holiday">{$types_of_absence.excused_absence}</label>
                    <br/>
                </td>
            </tr>
            <tr>
                <td align="right" colspan="2">
                    <input type='submit' class='button' value='{$DASHLET_STRINGS.LBL_SAVE_BUTTON_LABEL}'>
                </td>
            </tr>
        </table>
    </form>
</div>
