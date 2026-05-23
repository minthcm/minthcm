<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/MVC/View/SugarView.php';
require_once 'include/formbase.php';
require_once 'modules/Employees/Employee.php';
require_once 'include/Sugar_Smarty.php';

class EmployeesViewShowduplicates extends SugarView
{
    protected $module_name = "Employees";
    protected $class_name = "Employee";
    protected $object_name = "Employees";
    protected $table_name = "employees";

    public function preDisplay()
    {
        global $app_strings, $current_language, $app_list_strings;

        if (!isset($_SESSION['SHOW_DUPLICATES']) || empty($_REQUEST['module'])) {
            sugar_die("Unauthorized access to this area.");
        }

        parse_str($_SESSION['SHOW_DUPLICATES'], $_POST);
        foreach (array_map("securexss", $_POST) as $k => $v) {
            $_POST[$k] = $v;
        }
        unset($_SESSION['SHOW_DUPLICATES']);

        $error_msg = '';

        $mod_strings = return_module_language($current_language, $this->module_name);
        $tpl = new Sugar_Smarty();
        $tpl->assign("FORM_TITLE", getClassicModuleTitle($this->module_name, array($app_list_strings['moduleList'][$this->module_name], $mod_strings['LBL_SAVE_ACCOUNT']), true));
        $tpl->assign("MOD", $mod_strings);
        $tpl->assign("APP", $app_strings);
        $tpl->assign("PRINT_URL", "index.php?" . $GLOBALS['request_string']);
        $tpl->assign("MODULE", $_REQUEST['module']);
        if ('' != $error_msg) {
            $tpl->assign("ERROR", $error_msg);
        }

        $bean = new $this->class_name();
        $GLOBALS['check_notify'] = false;

        $tpl->assign('FORMBODY', $this->buildTableForm($this->getDuplicatedRecords()));

        $input = '';
        foreach ($bean->column_fields as $field) {
            if (!empty($_POST[$this->module_name . $field])) {
                $value = urldecode($_POST[$this->module_name . $field]);
                $input .= "<input type='hidden' name='$field' value='{$value}'>\n";
            }
        }
        foreach ($bean->additional_column_fields as $field) {
            if (!empty($_POST[$this->module_name . $field])) {
                $value = urldecode($_POST[$this->module_name . $field]);
                $input .= "<input type='hidden' name='$field' value='{$value}'>\n";
            }
        }

        $email_address = new SugarEmailAddress();
        $input .= $email_address->getEmailAddressWidgetDuplicatesView($bean);

        $get = '';
        if (!empty($_POST['return_module'])) {
            $tpl->assign('RETURN_MODULE', $_POST['return_module']);
        } else {
            $get .= $this->module_name;
        }

        $get .= "&return_action=";
        if (!empty($_POST['return_action'])) {
            $tpl->assign('RETURN_ACTION', $_POST['return_action']);
        } else {
            $get .= "DetailView";
        }

        if (!empty($_POST['return_id'])) {
            $tpl->assign('RETURN_ID', $_POST['return_id']);
        }

        if (!empty($_POST['popup'])) {
            $input .= '<input type="hidden" name="popup" value="' . $_POST['popup'] . '">';
        } else {
            $input .= '<input type="hidden" name="popup" value="false">';
        }

        if (!empty($_POST['to_pdf'])) {
            $input .= '<input type="hidden" name="to_pdf" value="' . $_POST['to_pdf'] . '">';
        } else {
            $input .= '<input type="hidden" name="to_pdf" value="false">';
        }

        if (!empty($_POST['create'])) {
            $input .= '<input type="hidden" name="create" value="' . $_POST['create'] . '">';
        } else {
            $input .= '<input type="hidden" name="create" value="false">';
        }

        $tpl->assign('INPUT_FIELDS', $input);
        $tpl->assign('MODULE', $this->module_name);
        $tpl->display("modules/{$this->module_name}/tpls/ShowDuplicates.tpl");
    }

    protected function getDuplicatedRecords()
    {
        global $mod_strings;
        $employee_label = !empty($mod_strings['LBL_MODULE_NAME']) ? $mod_strings['LBL_MODULE_NAME'] : 'Employees';
        $candidate_label = !empty($mod_strings['LBL_CANDIDATES_MODULE_DUP']) ? $mod_strings['LBL_CANDIDATES_MODULE_DUP'] : 'Candidates';
        $query_employee = "select id, first_name, last_name, phone_mobile as 'mobile_phone', '{$employee_label}' as display_module_name_dup, 'Employees' as module_name from users where deleted=0 ";
        $query_candidate = "select id, first_name, last_name, phone_mobile as 'mobile_phone', '{$candidate_label}' as display_module_name_dup, 'Candidates' as module_name from candidates where deleted=0 ";
        $duplicates = $_POST['duplicate'];
        $db = DBManagerFactory::getInstance();
        if (empty($duplicates)) {
            $duplicates = ['NON_EXISTING_ID'];
        }
        $query_employee .= " AND id IN ('" . implode("','", $duplicates) . "') ";
        $query_candidate .= " AND id IN ('" . implode("','", $duplicates) . "') ";

        $query = $query_employee . " UNION " . $query_candidate;

        $duplicate_records = array();

        $result = $db->query($query);
        $i = -1;

        while(($row=$db->fetchByAssoc($result)) != null) {
            $i++;
            $duplicate_records[$i] = $row;
        }
        return $duplicate_records;
    }

    protected function buildTableForm($rows)
    {
        global $action, $mod_strings, $app_strings, $current_language;

        $new_link_label = 'LNK_NEW_' . strtoupper($this->object_name);

        $cols = sizeof($rows[0]) * 2 + 1;

        if ('showduplicates' != $action) {
            $duplicateLabel = string_format($mod_strings['MSG_DUPLICATE'], array(strtolower($this->object_name), $this->module_name));
            $form = '<table width="100%"><tr><td>' . $duplicateLabel . '</td></tr><tr><td height="20"></td></tr></table>';
            $form .= "<form action='index.php' method='post' name='dup{$this->module_name}'><input type='hidden' name='selected{$this->object_name}' value=''>";
            $form .= getPostToForm('/emailAddress(PrimaryFlag|OptOutFlag|InvalidFlag)?[0-9]*?$/', true);
        } else {
            $mod_strings = return_module_language($current_language, $this->module_name);
            $msg_show_duplicates_label = !empty($mod_strings['MSG_SHOW_DUPLICATES']) ? $mod_strings['MSG_SHOW_DUPLICATES'] : $app_strings['MSG_SHOW_DUPLICATES'];
            $duplicateLabel = string_format($msg_show_duplicates_label, array(strtolower($this->object_name), $this->module_name));
            $form = '<table width="100%"><tr><td>' . $duplicateLabel . '</td></tr><tr><td height="20"></td></tr></table>';
        }

        $form .= "<table width='100%' cellpadding='0' cellspacing='0' class='list view' border='0'><tr class='pagination'><td colspan='$cols'><table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td>";
        if ('showduplicates' == $action) {
            $form .= "<input title='" . $app_strings['LBL_SAVE_BUTTON_TITLE'] . "' accessKey='" . $app_strings['LBL_SAVE_BUTTON_KEY'] . "' class='button' onclick=\"this.form.action.value='Save';\" type='submit' name='button' value='" . $app_strings['LBL_SAVE_BUTTON_LABEL'] . "'>\n";
            if (!empty($_REQUEST['return_module']) && !empty($_REQUEST['return_action']) && !empty($_REQUEST['return_id'])) {
                $form .= "<input title='" . $app_strings['LBL_CANCEL_BUTTON_TITLE'] . "' accessKey='" . $app_strings['LBL_CANCEL_BUTTON_KEY'] . "' class='button' onclick=\"this.form.module.value='" . $_REQUEST['return_module'] . "';this.form.action.value='" . $_REQUEST['return_action'] . "';this.form.record.value='" . $_REQUEST['return_id'] . "'\" type='submit' name='button' value='" . $app_strings['LBL_CANCEL_BUTTON_LABEL'] . "'>";
            } else if (!empty($_POST['return_module']) && !empty($_POST['return_action']) && (("DetailView" == $_POST['return_action'] && !empty($_REQUEST['return_id'])) || "DetailView" != $_POST['return_action'])) {
                $form .= "<input title='" . $app_strings['LBL_CANCEL_BUTTON_TITLE'] . "' accessKey='" . $app_strings['LBL_CANCEL_BUTTON_KEY'] . "' class='button' onclick=\"this.form.module.value='" . $_POST['return_module'] . "';this.form.action.value='" . $_POST['return_action'] . "';\" type='submit' name='button' value='" . $app_strings['LBL_CANCEL_BUTTON_LABEL'] . "'>";
            } else {
                $form .= "<a href='index.php?module={$this->module_name}&action=index&return_module={$this->module_name}&return_action=DetailView'><input title='" . $app_strings['LBL_CANCEL_BUTTON_TITLE'] . "' accessKey='" . $app_strings['LBL_CANCEL_BUTTON_KEY'] . "' class='button' type='submit' type='submit' name='button' value='" . $app_strings['LBL_CANCEL_BUTTON_LABEL'] . "'></a>";
            }
        } else {
            $form .= "<input type='submit' class='button' name='Continue{$this->object_name}' value='{$mod_strings[$new_link_label]}'>";
        }
        $form .= "</td></tr></table></td></tr><tr>";

        if ('showduplicates' != $action) {
            $form .= "<td scope='col'>&nbsp;</td>";
        }

        if (isset($_POST['return_action']) && 'SubPanelViewer' == $_POST['return_action']) {
            $_POST['return_action'] = 'DetailView';
        }

        if (isset($_POST['return_action']) && 'DetailView' == $_POST['return_action'] && empty($_REQUEST['return_id'])) {
            unset($_POST['return_action']);
        }

        $form .= getPostToForm();

        if (isset($rows[0])) {
            foreach ($rows[0] as $key => $value) {
                if ('id' != $key && 'module_name' != $key) {
                    $form .= "<th style='font-weight: bold; padding: 0 15px;' scope='col' >" . $mod_strings['LBL_' . strtoupper($key)] . "</th>";
                }
            }
            $form .= "</tr>";
        }

        $rowColor = 'oddListRowS1';

        foreach ($rows as $row) {

            $form .= "<tr class='$rowColor'>";
            if ('showduplicates' != $action) {
                $form .= "<td width='1%' nowrap='nowrap'><a href='#' onClick=\"document.forms['dup{$this->module_name}'].selected{$this->object_name}.value='" . $row['id'] . "';document.forms['dup{$this->module_name}'].submit() \">[{$app_strings['LBL_SELECT_BUTTON_LABEL']}]</a>&nbsp;&nbsp;</td>\n";
            }
            $was_set = false;

            foreach ($row as $key => $value) {
                if ('id' != $key && 'module_name' != $key) {
                    $row_module_name = $this->module_name;
                    if (!empty($row['module_name'])) {
                        $row_module_name = $row['module_name'];
                    }
                    if (isset($_POST['popup']) && true == $_POST['popup']) {
                        $form .= "<td style='padding: 0px; padding: 0 15px;' scope='row'><a href='#' onclick=\"window.opener.location='index.php?module={$row_module_name}&action=DetailView&record=" . $row['id'] . "'\">$value</a></td>\n";
                    } else if (!$was_set) {
                        $form .= "<td style='padding: 0px; padding: 0 15px;' scope='row'><a target='_blank' href='index.php?module={$row_module_name}&action=DetailView&record=" . $row['id'] . "'>$value</a></td>\n";
                        $was_set = true;
                    } else {
                        $form .= "<td style='padding: 0px; padding: 0 15px;'><a target='_blank' href='index.php?module={$row_module_name}&action=DetailView&record=" . $row['id'] . "'>$value</a></td>\n";
                    }
                }
            }

            if ('evenListRowS1' == $rowColor) {
                $rowColor = 'oddListRowS1';
            } else {
                $rowColor = 'evenListRowS1';
            }
            $form .= "</tr>";
        }
        $form .= "<tr class='pagination'><td colspan='$cols'><table width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td>";
        if ('showduplicates' == $action) {
            $form .= "<input title='" . $app_strings['LBL_SAVE_BUTTON_TITLE'] . "' accessKey='" . $app_strings['LBL_SAVE_BUTTON_KEY'] . "' class='button' onclick=\"this.form.action.value='Save';\" type='submit' name='button' value='  " . $app_strings['LBL_SAVE_BUTTON_LABEL'] . "  '>\n";
            if (!empty($_REQUEST['return_module']) && !empty($_REQUEST['return_action']) && !empty($_REQUEST['return_id'])) {
                $form .= "<input title='" . $app_strings['LBL_CANCEL_BUTTON_TITLE'] . "' accessKey='" . $app_strings['LBL_CANCEL_BUTTON_KEY'] . "' class='button' onclick=\"this.form.module.value='" . $_REQUEST['return_module'] . "';this.form.action.value='" . $_REQUEST['return_action'] . "';this.form.record.value='" . $_REQUEST['return_id'] . "';\" type='submit' name='button' value='" . $app_strings['LBL_CANCEL_BUTTON_LABEL'] . "'>";
            } else if (!empty($_POST['return_module']) && !empty($_POST['return_action'])) {
                $form .= "<input title='" . $app_strings['LBL_CANCEL_BUTTON_TITLE'] . "' accessKey='" . $app_strings['LBL_CANCEL_BUTTON_KEY'] . "' class='button' onclick=\"this.form.module.value='" . $_POST['return_module'] . "';this.form.action.value='" . $_POST['return_action'] . "';\" type='submit' name='button' value='" . $app_strings['LBL_CANCEL_BUTTON_LABEL'] . "'>";
            } else {
                $form .= "<a href='index.php?module={$this->module_name}&action=index&return_module={$this->module_name}&return_action=DetailView'><input title='" . $app_strings['LBL_CANCEL_BUTTON_TITLE'] . "' accessKey='" . $app_strings['LBL_CANCEL_BUTTON_KEY'] . "' class='button' onclick=\"this.form.action.value='ListView';\" type='submit' type='submit' name='button' value='" . $app_strings['LBL_CANCEL_BUTTON_LABEL'] . "'></a>";
            }

        } else {
            $form .= "<input type='submit' class='button' name='Continue{$this->object_name}' value='{$mod_strings[$new_link_label]}'></form>";
        }
        $form .= "</td></tr></table></td></tr></table>";
        return $form;
    }
}
