<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

global $modules_exempt_from_availability_check;
$modules_exempt_from_availability_check['ACLRoles'] = 'ACLRoles';

$layout_defs['SecurityGroups'] = array(
   // list of what Subpanels to show in the DetailView 
   'subpanel_setup' => array(
      'members' => array(
         'order' => 100,
         'module' => 'SecurityGroups',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_MEMBERS',
         'get_subpanel_data' => 'members',
         'add_subpanel_data' => 'member_id',
         'top_buttons' =>
         array(
            array(
               'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
            array(
               'widget_class' => 'SubPanelTopSelectButton',
               'mode' => 'MultiSelect',
            ),
         ),
      ),
      'positions_membership' => array(
         'order' => 200,
         'module' => 'Positions',
         'subpanel_name' => 'ForOrganizationalUnits',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_POSITIONS_MEMBERSHIP',
         'get_subpanel_data' => 'positions_membership',
         'top_buttons' => array(
            array(
               'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
            array(
               'widget_class' => 'SubPanelTopSelectButton',
               'mode' => 'MultiSelect',
            ),
         ),
      ),
      'onboardingoffboardingelements' => array(
         'order' => 200,
         'module' => 'OnboardingOffboardingElements',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_ONBOARDINGOFFBOARDINGELEMENTS',
         'get_subpanel_data' => 'onboardingoffboardingelements',
         'top_buttons' => array(
            array(
               'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
         ),
      ),
      'employees_in_department' => array(
         'order' => 10,
         'module' => 'Employees',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_EMPLOYEES_IN_DEPARTEMENTS',
         'get_subpanel_data' => 'employees', 
         'top_buttons' => array(
         ),
      ),
      'employees' => array(
         'order' => 10,
         'module' => 'Employees',
         'subpanel_name' => 'default',
         'sort_order' => 'asc',
         'sort_by' => 'id',
         'title_key' => 'LBL_EMPLOYEES',
         'get_subpanel_data' => 'users', 
         'top_buttons' => array(
            array(
               'widget_class' => 'SubPanelTopSelectButton',
               'mode' => 'MultiSelect',
            ),
         ),
      ),
      'users' => array(
         'top_buttons' => array(),
         'order' => 12,
         'module' => 'Users',
         'sort_by' => 'user_name',
         'sort_order' => 'asc',
         'subpanel_name' => 'ForSecurityGroups',
         'override_subpanel_name' => 'ForSecurityGroups',
         'get_subpanel_data' => 'users',
         'add_subpanel_data' => 'user_id',
         'title_key' => 'LBL_USERS_SUBPANEL_TITLE',
      ),
   ),
);
$layout_defs["SecurityGroups"]["subpanel_setup"]['securitygroups_rooms'] = array (
   'order' => 100,
   'module' => 'Rooms',
   'subpanel_name' => 'default',
   'sort_order' => 'asc',
   'sort_by' => 'id',
   'title_key' => 'LBL_RELATIONSHIP_ROOMS_NAME',
   'get_subpanel_data' => 'securitygroups_rooms',             
   'top_buttons' => array (
   ),
);
$layout_defs['SecurityGroupRoles'] = array(
   // sets up which panels to show, in which order, and with what linked_fields
   'subpanel_setup' => array(
      'aclroles' => array(
         'top_buttons' => array(array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'ACLRoles', 'mode' => 'MultiSelect'),),
         'order' => 20,
         'sort_by' => 'name',
         'sort_order' => 'asc',
         'module' => 'ACLRoles',
         'refresh_page' => 1,
         'subpanel_name' => 'default',
         'get_subpanel_data' => 'aclroles',
         'add_subpanel_data' => 'role_id',
         'title_key' => 'LBL_ROLES_SUBPANEL_TITLE',
      ),
   ),
);
global $current_user;
if ( is_admin($current_user) ) {
   $layout_defs['SecurityGroups']['subpanel_setup']['aclroles']['subpanel_name'] = 'admin';
   $layout_defs['SecurityGroupRoles']['subpanel_setup']['aclroles']['subpanel_name'] = 'admin';
} else {

   $layout_defs['SecurityGroups']['subpanel_setup']['aclroles']['top_buttons'] = array();

   $layout_defs['SecurityGroupRoles']['subpanel_setup']['aclroles']['top_buttons'] = array();
}
