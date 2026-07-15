<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $current_user;

if (!empty($_SESSION['masquerade_admin_id'])) {
    header('Location: ' . rtrim($GLOBALS['sugar_config']['site_url'] ?? '', '/') . '/');
    exit;
}

if (is_admin($current_user) && !empty($_REQUEST['record']) && preg_match('/^[0-9a-f-]{36}$/i', $_REQUEST['record'])) {
    require_once 'modules/Users/User.php';

    $mask_user = new User();
    $mask_user->retrieve($_REQUEST['record']);

    if (!empty($mask_user->id) && $mask_user->status !== 'Active') {
        global $app_strings;
        $error_msg = $app_strings['LBL_MASQUERADE_USER_INACTIVE'] ?? 'Cannot login as inactive user.';
        $back_url = 'index.php?module=Users&action=DetailView&record=' . htmlspecialchars($_REQUEST['record'], ENT_QUOTES, 'UTF-8');
        ob_clean();
        echo '<script>alert(' . json_encode($error_msg) . '); top.location.href=' . json_encode($back_url) . ';</script>';
        exit;
    }

    if (
        !empty($mask_user->id)
        && $mask_user->status === 'Active'
        && !$mask_user->deleted
        && !$mask_user->is_group
        && !is_admin($mask_user)
    ) {
        global $sugar_config;

        unset($_SESSION['EAPM']);

        $_SESSION['masquerade_admin_id'] = $GLOBALS['current_user']->id;
        $_SESSION['masquerade_admin_name'] = $GLOBALS['current_user']->full_name;
        $_SESSION['masquerade_user_id'] = $mask_user->id;
        $GLOBALS['current_user'] = $mask_user;
        $current_user = $mask_user;
        $_SESSION['authenticated_user_id'] = $mask_user->id;

        $GLOBALS['log']->debug("User " . $_SESSION['masquerade_admin_name'] . " is Masquerading as " . $mask_user->user_name);

        session_regenerate_id(true);
        session_write_close();
        ob_clean();

        $site_url = rtrim($sugar_config['site_url'] ?? '', '/') . '/';
        echo '<script>top.location.href=' . json_encode($site_url) . ';</script>';
        exit;
    }
}
