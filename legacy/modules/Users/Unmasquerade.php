<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

if (!empty($_SESSION['masquerade_admin_id'])) {
    global $current_user, $sugar_config;

    $admin_user = BeanFactory::newBean('Users');
    $admin_user->retrieve($_SESSION['masquerade_admin_id']);

    if (!empty($admin_user->id) && is_admin($admin_user)) {
        $GLOBALS['log']->debug("User " . $admin_user->user_name . " is Unmasquerading from " . $current_user->user_name);

        unset($_SESSION['EAPM']);

        $GLOBALS['current_user'] = $admin_user;
        $current_user = $admin_user;
        $_SESSION['authenticated_user_id'] = $admin_user->id;

        unset($_SESSION['masquerade_user_id']);
        unset($_SESSION['masquerade_admin_id']);
        unset($_SESSION['masquerade_admin_name']);

        session_regenerate_id(true);
        session_write_close();
        ob_clean();

        $site_url = rtrim($sugar_config['site_url'] ?? '', '/') . '/';
        echo '<script>top.location.href=' . json_encode($site_url) . ';</script>';
        exit;
    }
}
