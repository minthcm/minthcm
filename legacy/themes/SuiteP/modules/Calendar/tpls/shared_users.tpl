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

<script language="javascript">
{if $edit_shared}
	{literal}
	SUGAR.util.doWhen(function(){
		return typeof cal_loaded != 'undefined' && cal_loaded == true && typeof dom_loaded != 'undefined' && dom_loaded == true;	
	},function(){
		CAL.toggle_shared_edit();
	});
	{/literal}
{/if}

{literal}
			function up(name){
				var td = document.getElementById(name+'_td');
				var obj = td.getElementsByTagName('select')[0];
				obj =(typeof obj == "string") ? document.getElementById(obj) : obj;
				if(obj.tagName.toLowerCase() != "select" && obj.length < 2)
					return false;
				var sel = new Array();
							
				for(i = 0; i < obj.length; i++){
					if(obj[i].selected == true) {
						sel[sel.length] = i;
					}
				}
				for(i in sel){
					if(sel[i] != 0 && !obj[sel[i]-1].selected) {
						var tmp = new Array(obj[sel[i]-1].text, obj[sel[i]-1].value);
						obj[sel[i]-1].text = obj[sel[i]].text;
						obj[sel[i]-1].value = obj[sel[i]].value;
						obj[sel[i]].text = tmp[0];
						obj[sel[i]].value = tmp[1];
						obj[sel[i]-1].selected = true;
						obj[sel[i]].selected = false;
					}
				}
			}			
			function down(name){
				var td = document.getElementById(name+'_td');
				var obj = td.getElementsByTagName('select')[0];
				if(obj.tagName.toLowerCase() != "select" && obj.length < 2)
					return false;
				var sel = new Array();
				for(i=obj.length-1; i>-1; i--){
					if(obj[i].selected == true) {
						sel[sel.length] = i;
					}
				}
				for(i in sel){
					if(sel[i] != obj.length-1 && !obj[sel[i]+1].selected) {
						var tmp = new Array(obj[sel[i]+1].text, obj[sel[i]+1].value);
						obj[sel[i]+1].text = obj[sel[i]].text;
						obj[sel[i]+1].value = obj[sel[i]].value;
						obj[sel[i]].text = tmp[0];
						obj[sel[i]].value = tmp[1];
						obj[sel[i]+1].selected = true;
						obj[sel[i]].selected = false;
					}
				}
			}
{/literal}
</script>

<script language="javascript" type="text/javascript" src="{sugar_getjspath file='themes/SuiteP/modules/Calendar/tpls/shared_users.js'}"></script>

<div class="modal fade modal-calendar-user-list" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" style="width: 1000px;">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title">{$MOD.LBL_EDIT_USERLIST}</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="panel panel-default ">
						<div class="panel-heading  panel-heading-collapse">
							<a id="subpanel_title_activities" class="" role="button" data-toggle="collapse" href="#subpanel_settings_user_list">
								<div class="col-xs-10 col-sm-11 col-md-11">
									<div>
										{$MOD.LBL_EDIT_USERLIST}
									</div>
								</div>
							</a>
						</div>
						<div id="subpanel_settings_user_list" class="panel-body panel-collapse collapse in">
							<form id="shared_cal" name="shared_cal" action="index.php" method="post">
								<input type="hidden" name="module" value="Calendar">
								<input type="hidden" name="action" value="index">
								<input type="hidden" name="edit_shared" value="">
								<input type="hidden" name="view" value="{$view}">


                                <div class="col-12">
                                    <div class="col-12" style="margin-top: 24px">
                                        {$MOD.LBL_SELECT_USERS}
                                    </div>
                                    <div class="col-12">
                                        <div class="col-sm-5 col-12">
                                            <div style="float:left"><b>{$MOD.LBL_USERS}:</b></div>
                                            <div class="shared_ids_td" style="float:left">
                                                <select id="shared_ids" name="shared_ids[]" multiple size="8">{$users_options}</select>
                                            </div>
                                            <div style="float:left">
                                                <a onclick="up('shared_ids');">{$UP}</a><br>
                                                <a onclick="down('shared_ids');">{$DOWN}</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-7 col-12">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-primary" id="create-users-group">+ {$MOD.LBL_CREATE_USERS_GROUP_BUTTON}</button> {* todo LBL *}
                                                {$MOD.LBL_CREATE_GROUP_DESCRIPTION}
                                            </div>
                                            <hr>
                                            <div class="col-12">
                                            <ul class="list-group" style="max-height: 128px; overflow-y: scroll;">
											{foreach from=$shared_ids_groups key=arrayKey  item=group_name}
												{foreach from=$group_name key=name item=ids}
													{if $name eq $shared_ids_last_group}
													<li class="list-group-item d-flex justify-content-between align-items-center users-group-select" value="{$name}" style="color: white; background-color: rgb(0, 153, 118);">
													{$name}
														<a href="javascript:void(0)" class="delete-users-group" style="float: right; margin-left: 10px; color: white;" value="{$name}"><span class="suitepicon suitepicon-action-delete"></span></a>
														<a href="javascript:void(0)" class="update-users-group" style="float: right; margin-left: 10px; color: white;" value="{$name}">AKTUALIZUJ</a>
													{else}
													<li class="list-group-item d-flex justify-content-between align-items-center users-group-select" value="{$name}">
													{$name}
														<a href="javascript:void(0)" class="delete-users-group" style="float: right; margin-left: 10px" value="{$name}"><span class="suitepicon suitepicon-action-delete"></span></a>
														<a href="javascript:void(0)" class="update-users-group" style="float: right; margin-left: 10px" value="{$name}">AKTUALIZUJ</a>
													{/if}
													</li>
												{/foreach}
											{/foreach}
                                            </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>

								{* <table cellpadding="0" cellspacing="3" border="0" align="center" width="100%">
									<tr><th valign="top" align="center" colspan="2">{$MOD.LBL_SELECT_USERS}</th></tr>
									<tr><td valign="top"></td><td valign="top">
											<table cellpadding="1" cellspacing="1" border="0" class="edit view" align="center">
												<tr>
													<td valign="top" nowrap=""><b>{$MOD.LBL_USERS}:</b></td>
													<td valign="top" id="shared_ids_td">
														<select id="shared_ids" name="shared_ids[]" multiple size="8">{$users_options}</select>
													</td>
													<td>
														<a onclick="up('shared_ids');">{$UP}</a><br>
														<a onclick="down('shared_ids');">{$DOWN}</a>
													</td>
												</tr>
											</table>
										</td></tr>
								</table> *}
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button id="sharedCalUsersSelectBtn" data-dismiss="modal" class="btn btn-default" type="button">{$MOD.LBL_CANCEL_BUTTON}</button>
				<button id="sharedCalUsersSelectBtn" onclick="showLoadingScreen('{$MOD.LBL_MODULE_NAME}', viewTools.language.get('app_strings', 'LBL_LOADING')); $('#shared_cal').submit();" class="btn btn-danger" type="button">{$MOD.LBL_APPLY_BUTTON}</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
