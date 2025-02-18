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
 * Copyright (C) 2018-2023 MintHCM
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

{if $controls}

    <div class="clear"></div>

    <div style='float:left; width: 70%;'>
        {foreach name=tabs from=$tabs key=k item=tab}
            <input type="button" class="button" {if $view == $k} selected {/if} id="{$tabs_params[$k].id}" title="{$tabs_params[$k].title}" value="{$tabs_params[$k].title}" onclick="showLoadingScreen('{$tabs_params[$k].title}', viewTools.language.get('app_strings', 'LBL_LOADING')); {$tabs_params[$k].link}">
        {/foreach}
    </div>

    <div style="float:left; text-align: right; width: 30%; font-size: 12px;">
        {if $view == "sharedWeek" || $view == "sharedMonth"}
            <input id="userListButtonId" type="button" class="btn btn-info" value="{$MOD.LBL_EDIT_USERLIST}" data-toggle="modal" data-target=".modal-calendar-user-list"">
        {/if}
        {if $view != 'year' && !$print}
            <button id="goto_date_trigger" class="btn btn-danger">
                <span class="dateTime module-calendar">
                    <span class="suitepicon suitepicon-module-calendar" alt="{$APP.LBL_ENTER_DATE}" ></span>
                    <input type="hidden" id="goto_date" name="goto_date" value="{$current_date}">
                    <script type="text/javascript">
                        Calendar.setup({literal} {{/literal}
                           inputField: "goto_date",
                           ifFormat: "%m/%d/%Y",
                           daFormat: "%m/%d/%Y",
                           button: "goto_date_trigger",
                           singleClick: true,
                           dateStr: "{$current_date}",
                           step: 1,
                           onUpdate: goto_date_call,
                           startWeekday: {$start_weekday},
                           weekNumbers: false
                        {literal}}{/literal} );
                        {literal}
                            YAHOO.util.Event.onDOMReady( function () {
                               YAHOO.util.Event.addListener( "goto_date", "change", goto_date_call );
                            } );
                            function goto_date_call() {
                               CAL.goto_date_call();
                            }
                        {/literal}
                    </script>
                </span>
            </button>
        {/if}
        <input type="button" id="" class="btn btn-info" data-toggle="modal" data-target=".modal-calendar-settings" value="{$MOD.LBL_SETTINGS}">
    </div>

    <div style='clear: both;'></div>

{/if}

{*Mint #42390 START*}
{*<div class="row {if $controls}monthHeader{/if}">*}
<div class="row monthHeader" {if !$controls}style="background:#778591"{/if}>
    {*Mint #42390 END*}
    <div class="col-xs-1">{$previous}</div>
    <div class="col-xs-10 text-center">
    {*Mint #75984 START*}
        <p style="font-weight:bold; padding-bottom: 15px;">{$date_info}</p>
        {if $config.display_week_number}
        <p style="font-weight:bold;">{$week_info}</p>
        {/if}
    {*Mint #75984 END*}
    </div>
    <div class="col-xs-1 text-right">{$next}</div>
    <br>
</div>
