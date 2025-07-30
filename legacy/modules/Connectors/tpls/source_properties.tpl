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

<br/>
{if !empty($connector_language.LBL_LICENSING_INFO)}
{$connector_language.LBL_LICENSING_INFO}
{/if}
<br/>
<table width="100%" border="0" cellspacing="1" cellpadding="0" >
{if !empty($properties)}
{foreach from=$properties key=name item=value}
<tr>
<td class="dataLabel" width="35%">
{$connector_language[$name]}:&nbsp;
{if isset($required_properties[$name])}
<span class="required">*</span>
{/if}
</td>
<td class="dataLabel" width="65%">
{* MintHCM #133185 start *}
{if 'private_key' == $name}
    <textarea id="{$source_id}_{$name}" name="{$source_id}_{$name}" rows="10" cols="75">{$value}</textarea>
{else}
    <input type="text" id="{$source_id}_{$name}" name="{$source_id}_{$name}" size="75" value="{$value}">
{/if}
{* MintHCM #133185 end *}
</td>
</tr>
{/foreach}
{if $hasTestingEnabled}
<tr>
<td class="dataLabel" colspan="2">
<input id="{$source_id}_test_button" type="button" class="button" value="  {$mod.LBL_TEST_SOURCE}  " onclick="run_test('{$source_id}');">
</td>
</tr>
<tr>
<td class="dataLabel" colspan="2">
<span id="{$source_id}_result">&nbsp;</span>
</td>
</tr>
{/if}
{else}
<tr>
<td class="dataLabel" colspan="2">&nbsp;</td>
<td class="dataLabel" colspan="2">{$mod.LBL_NO_PROPERTIES}</td>
</tr>
{/if}
</table>

<script type="text/javascript">
{foreach from=$required_properties key=id item=label}
addToValidate("ModifyProperties", "{$source_id}_{$id}", "alpha", true, "{$label}");
{/foreach}
</script>