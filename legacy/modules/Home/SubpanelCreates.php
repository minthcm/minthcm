<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$mod_strings = return_module_language($current_language, $_REQUEST['target_module']);
if ( file_exists('modules/' . $_REQUEST['target_module'] . '/EditView.php') ) {
   $target_module = $_REQUEST['target_module']; // target class
   if ( is_file('modules/' . $target_module . '/' . $target_module . 'QuickCreate.php') ) { // if there is a quickcreate override
      require_once('modules/' . $target_module . '/' . $target_module . 'QuickCreate.php');
      $editviewClass = $target_module . 'QuickCreate'; // eg. OpportunitiesQuickCreate 
      $editview = new $editviewClass($target_module, 'modules/' . $target_module . '/tpls/' . $_REQUEST['tpl']);
      $editview->viaAJAX = true;
   } else { // else use base class
      require_once('include/EditView/EditViewQuickCreate.php');
      $editview = new EditViewQuickCreate($target_module, 'modules/' . $target_module . '/tpls/' . $_REQUEST['tpl']);
   }
   $editview->process();
   echo $editview->display();
} else {
   if ( file_exists('custom/modules/' . $_REQUEST['target_module'] . '/' . $_REQUEST['target_module'] . 'SubpanelQuickCreate.php') ) {
      require_once('custom/modules/' . $_REQUEST['target_module'] . '/' . $_REQUEST['target_module'] . 'SubpanelQuickCreate.php');
      $view = (!empty($_REQUEST['target_view'])) ? $_REQUEST['target_view'] : 'QuickCreate';
      $subpanelClass = $_REQUEST['target_module'] . "SubpanelQuickCreate";
      $sqc = new $subpanelClass($_REQUEST['target_module'], $view);
   } else if ( file_exists('modules/' . $_REQUEST['target_module'] . '/' . $_REQUEST['target_module'] . 'SubpanelQuickCreate.php') ) {
      require_once('modules/' . $_REQUEST['target_module'] . '/' . $_REQUEST['target_module'] . 'SubpanelQuickCreate.php');
      $view = (!empty($_REQUEST['target_view'])) ? $_REQUEST['target_view'] : 'QuickCreate';
      $subpanelClass = $_REQUEST['target_module'] . "SubpanelQuickCreate";
      $sqc = new $subpanelClass($_REQUEST['target_module'], $view);
   } else {
      require_once('include/EditView/SubpanelQuickCreate.php');
      $view = (!empty($_REQUEST['target_view'])) ? $_REQUEST['target_view'] : 'QuickCreate';
      $sqc = new SubpanelQuickCreate($_REQUEST['target_module'], $view);
   }
}
