<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Files';
$subpanel_layout = array(
    'top_buttons' => array(
        array(
            'widget_class' => 'SubPanelTopCreateButton'
        ),
        array(
            'widget_class' => 'SubPanelTopSelectButton',
            'popup_module' => $module_name
        ),
    ),
    'where' => '',
    'list_fields' => array(
        'dropzone' => array(
            'name' => 'dropzone',
            'vname' => 'LBL_PHOTOS',
            // 'widget_class' => 'SubPanelDetailViewLink',
        ),
        'object_image' => array(
            'widget_class' => 'SubPanelIcon',
            'width' => '2%',
            'image2' => 'attachment',
            'image2_url_field' => array(
                'id_field' => 'selected_revision_id',
                'filename_field' => 'selected_revision_filename',
            ),
            'attachment_image_only' => true,
        ),
        'document_name' => array(
            'name' => 'document_name',
            'vname' => 'LBL_LIST_DOCUMENT_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '45%',
        ),
        'active_date' => array(
            'name' => 'active_date',
            'vname' => 'LBL_DOC_ACTIVE_DATE',
            'width' => '45%',
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'module' => $module_name,
            'width' => '5%',
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => $module_name,
            'width' => '5%',
        ),
    ),
);
