<?php

$module_name = 'Administration';
$viewdefs[$module_name]['dlncsettings'] = array(
    'templateMeta' => array(
        'form' => array(
            'headerTpl' => 'include/EditView/header.tpl',
            'footerTpl' => 'include/EditView/footer.tpl',
            'hidden' => array(
                '<input type="hidden" id="save_config" name="save_config" value="true">',
            ),
            'buttons' => array(
                array(
                    'customCode' => '<input title="{$MOD.LBL_BUTTON_SAVE_CONFIG}" accessKey="N" onclick="this.form.action.value=\'save_dlncsettings_config\'; this.form.module.value=\'Administration\'; if(check_form(\'dlncsettings\'))SUGAR.ajaxUI.submitForm(_form);return false;" type="submit" name="button" value="{$MOD.LBL_BUTTON_SAVE_CONFIG}">',
                ),
                'CANCEL',
            ),
        ),
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '30', 'field' => '70'),
            array('label' => '30', 'field' => '70'),
        ),
    ),
    'panels' => array(
        'default' => array(
            array(
                array(
                    'name' => 'dlnc_flag',
                    'label' => 'LBL_DLNC_CHECKBOX',
                    'customCode' => '<input type="checkbox" id="dlnc_flag" name="dlnc_flag" value="1" {if $DLNC_flag}checked{/if} />',
                ),
            ),
        ),
    ),
);
