<?php

require_once 'include/formbase.php';
require_once 'modules/Candidates/Repository/CandidatesRepository.php';

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $current_user;

$module_name = 'Candidates';
$focus = new $module_name();
$focus->retrieve(!empty($_POST['record']) ? $_POST['record'] : $_POST['id']);

if (
    isset($_POST['record'])
    && !ACLAction::userHasAccess($current_user->id, $module_name, 'edit','module',true,true)
) {
    sugar_die("Unauthorized access to administration.");
}

$retrieved_focus = $focus;

populateFromPost(empty($_REQUEST['dup_checked']) ? '' : $module_name, $focus);

// handles duplicate search for updates
if (!empty($focus->fetched_row) && !empty($_POST['id']) && $retrieved_focus->id === $_POST['id']) {
    $focus->new_with_id = false;
}

$focus->email1 = $_REQUEST[$_REQUEST["{$module_name}0emailAddressPrimaryFlag"]];

if (empty($_POST['dup_checked'])) {

    $duplicates = CandidatesRepository::getDuplicatedCandidatesEmployeesRecordsIds($focus);
    if (!empty($duplicates)) {
        $location = "module={$module_name}&action=showduplicates";
        
        $get = '';
        if (isset($_POST['inbound_email_id']) && !empty($_POST['inbound_email_id'])) {
            $get .= '&inbound_email_id=' . $_POST['inbound_email_id'];
        }

        if (isset($_POST['relate_to']) && !empty($_POST['relate_to'])) {
            $get .= "&{$module_name}relate_to=" . $_POST['relate_to'];
        }
        if (isset($_POST['relate_id']) && !empty($_POST['relate_id'])) {
            $get .= "&{$module_name}relate_id='" . $_POST['relate_id'];
        }

        foreach ($focus->column_fields as $field) {
            if (!empty($focus->$field) && !is_object($focus->$field)) {
                $get .= "&{$module_name}$field=" . urlencode($focus->$field);
            }
        }

        foreach ($focus->additional_column_fields as $field) {
            if (!empty($focus->$field)) {
                $get .= "&{$module_name}$field=" . urlencode($focus->$field);
            }
        }

        if ($focus->hasCustomFields()) {
            foreach ($focus->field_defs as $name => $field) {
                if (!empty($field['source']) && 'custom_fields' == $field['source']) {
                    $get .= "&{$module_name}$name=" . urlencode($focus->$name);
                }
            }
        }

        $emailAddress = new SugarEmailAddress();
        $get .= $emailAddress->getFormBaseURL($focus);

        foreach ($duplicates as $index => $duplicated_id) {
            $get .= "&duplicate[$index]=" . $duplicated_id;
        }

        $urlData = ['return_module' => $module_name, 'return_action' => ''];
        foreach (['return_module', 'return_action', 'return_id', 'popup', 'create', 'start'] as $var) {
            if (!empty($_POST[$var])) {
                $urlData[$var] = $_POST[$var];
            }
        }
        $get .= "&" . http_build_query($urlData);
        $_SESSION['SHOW_DUPLICATES'] = $get;

        if (!empty($_POST['is_ajax_call']) && '1' == $_POST['is_ajax_call']) {
            ob_clean();
            $json = getJSONobj();
            echo $json->encode(array('status' => 'dupe', 'get' => $location));
        } else if (!empty($_REQUEST['ajax_load'])) {
            echo "<script>SUGAR.ajaxUI.loadContent('index.php?{$location}');</script>";
        } else {
            if (!empty($_POST['to_pdf'])) {
                $location .= '&to_pdf=' . urlencode($_POST['to_pdf']);
            }

            header("Location: index.php?{$location}");
        }
        return null;
    }
}

$return_id = $focus->save();

if (isset($_POST['return_module']) && "" != $_POST['return_module']) {
    $return_module = $_POST['return_module'];
} else {
    $return_module = $module_name;
}
if (isset($_POST['return_action']) && "" != $_POST['return_action']) {
    $return_action = $_POST['return_action'];
} else {
    $return_action = "DetailView";
}
if (isset($_POST['return_id']) && "" != $_POST['return_id']) {
    $return_id = $_POST['return_id'];
}


header("Location: index.php?action={$return_action}&module={$return_module}&record={$return_id}");
