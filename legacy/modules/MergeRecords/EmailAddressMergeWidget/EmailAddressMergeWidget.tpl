
{php}
global $emailInstances;
if (empty($emailInstances))
	$emailInstances = array();
if (!isset($emailInstances[$this->_tpl_vars['module']]))
	$emailInstances[$this->_tpl_vars['module']] = 0;
$this->_tpl_vars['index'] = $emailInstances[$this->_tpl_vars['module']];
$emailInstances['module']++;
{/php}
<script type="text/javascript" language="javascript">
var emailAddressWidgetLoaded = false;
</script>
	<script type="text/javascript" src="include/SugarEmailAddress/SugarEmailAddress.js"></script>
<script type="text/javascript">
	var module = '{$module}';
</script>

{literal}
<style>

.email_container_padding {
    padding-left: 0px;
}

.email_address_options_margin {
    margin-top: -5px;
}

</style>
{/literal}

<div class="col-xs-12 email_container_padding">
	<div class="col-xs-12 email_container_padding email-address-lines-container">
		<div class="col-xs-12 email_container_padding template email-address-line-container hidden">
			<div class="col-xs-8 col-sm-8 email_container_padding email-address-input-container {if $module == "Users"} email-address-users-profile{/if}">
				<div class="input-group email-address-input-group">
					<input type="email" id="email-address-input" class="form-control" placeholder="email@example.com" title="{$app_strings.LBL_EMAIL_TITLE}">
					<input type="hidden" id="record-id">
					<input type="hidden" id="verified-flag" class="verified-flag" value="true"/>
					<input type="hidden" id="verified-email-value" class="verified-email-value" value=""/>
					<input type=hidden id="{$module}_email_widget_id" name="{$module}_email_widget_id" value="">
					<input type=hidden id='emailAddressWidget' name='emailAddressWidget' value='1'>
					<span class="input-group-btn">
					<button type="button" id="email-address-remove-button" class="btn btn-danger email-address-remove-button" name="" title="{$app_strings.LBL_ID_FF_REMOVE_EMAIL}">
						<span class="suitepicon suitepicon-action-minus"></span>
					</button>
				</span>
				</div>
			</div>
			<div class="col-xs-4 col-sm-4 email_container_padding">
				<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2 email_address_options_margin email_container_padding text-center email-address-option">
					<label class="text-sm col-xs-12 email_container_padding">{$app_strings.LBL_EMAIL_PRIMARY}</label>
					<div><input type="radio" name="" id="email-address-primary-flag" class="email-address-primary-flag" value="" enabled="true" tabindex="0" checked="true" title="{$app_strings.LBL_EMAIL_PRIM_TITLE}"></div>
				</div>
			</div>
		</div>

	</div>
</div>
<input type="hidden" name="useEmailWidget" value="true">
<script type="text/javascript" language="javascript">
SUGAR_callsInProgress++;
var eaw = SUGAR.EmailAddressWidget.instances.{$module}{$index} = new SUGAR.EmailAddressWidget("{$module}");
eaw.emailView = '{$emailView}';
eaw.emailIsRequired = "{$required}";
eaw.tabIndex = '{$tabindex}';
var addDefaultAddress = '{$addDefaultAddress}';
var prefillEmailAddress = '{$prefillEmailAddresses}';
var prefillData = {$prefillData};
if(prefillEmailAddress == 'true') {ldelim}
	eaw.prefillEmailAddresses('{$module}emailAddressesTable{$index}', prefillData);
{rdelim} else if(addDefaultAddress == 'true') {ldelim}
	eaw.addEmailAddress('{$module}emailAddressesTable{$index}', '',true);
{rdelim}
if('{$module}_email_widget_id') {ldelim}
   document.getElementById('{$module}_email_widget_id').value = eaw.count;
{rdelim}
SUGAR_callsInProgress--;
</script>
