<?php

function install_mint_dashlets($db)
{
	include 'install/config/dashlets/dashlets_definitions.php';
	include 'install/config/dashlets/pages_definitions.php';


	$sql_insert = 'INSERT IGNORE INTO  `dashboardmanager` (`id`, `name`, `date_entered`, `date_modified`, `modified_user_id`, `created_by`, `description`, `deleted`, `assigned_user_id`, `encoded_pages`, `encoded_dashlets`, `is_loaded`, `business_role`) VALUES';

	$dashlets_configuration_payrole =  array($main, $employee, $settlements);
	$sql_payrole = "('payrole', 'Payrole', '2020-02-28 16:00:03', '2020-02-28 15:26:09', '1', '1', NULL, 0, '1', '" . base64_encode(serialize($dashlets_configuration_payrole)) . "', '" . base64_encode(serialize($dashlets_definitions)) . "', 1, NULL)";
	$db->query($sql_insert . $sql_payrole);


	$dashlets_configuration_hr =  array($main, $employee, $hr_actions,$hr_manager);
	$sql_hr = "('hr', 'HR', '2020-02-28 16:00:03', '2020-02-28 15:26:09', '1', '1', NULL, 0, '1', '" . base64_encode(serialize($dashlets_configuration_hr)) . "', '" . base64_encode(serialize($dashlets_definitions)) . "', 1, NULL)";
	$db->query($sql_insert . $sql_hr);
	

	$dashlets_configuration_employee =  array($main, $employee);
	$sql_employee = "('employee', 'Employee', '2020-02-28 16:20:03', '2020-02-28 15:26:09', '1', '1', NULL, 0, '1', '" . base64_encode(serialize($dashlets_configuration_employee)) . "', '" . base64_encode(serialize($dashlets_definitions)) . "', 1, NULL)";
	$db->query($sql_insert . $sql_employee);


	$dashlets_configuration_manager =  array($main,  $employee, $my_team, $hr_actions,$hr_manager);
	$sql_manager = "('manager', 'Manager', '2020-02-28 16:24:03', '2020-02-28 18:26:09', '1', '1', NULL, 0, '1', '" . base64_encode(serialize($dashlets_configuration_manager)) . "', '" . base64_encode(serialize($dashlets_definitions)) . "', 1, NULL)";
	
	$db->query($sql_insert . $sql_manager);
}


function deploy_mint_dashlets()
{
	$role_payroll = array('millera', 'brookse', 'Kate', 'clarkek');
	$role_payroll =  array('d8969478-9591-0c6e-93ca-5ce3c44276c5','3764d490-cd45-5796-dde9-5ce3c7a1e614','ed6cb023-a3ba-b1c5-ad75-5dca74c006ac','f333cc2c-cb86-c2ae-6c21-5ce3c5d477c2');
	deploy_mint_dashlets_for_users('payrole',$role_payroll);
	$role_hr =  array('ellism', 'novakm');
	$role_hr =  array('7451ac0d-c4d1-04f6-4666-5ce3c54ff383','a306a144-af6e-c110-56e5-5dce87229735');
	deploy_mint_dashlets_for_users('hr',$role_hr);
	$role_employee = array('leej', 'hartc', 'stewardb', 'owena', 'whitej', 'rosss', 'lewisa', 'smithc');
	$role_employee = array('14a7c3af-44fc-4e11-ef11-5ce3c5778501', '22da6f70-1581-910f-02b7-5ce3c54fab51', '4ec12c2e-fa27-bec5-3f64-5ce3c8dd0a9b', 'c4676ffc-58c1-5fe5-ea08-5ce3c7b25d74', 'ef6c26ec-8269-6ff1-120b-5ce3c4a1874e', '53d6b86d-60ec-7e08-f5d6-5ce3c7a2c99a', '7fd88261-8d29-be47-2d19-5ce3c69d1048', 'c74cbf0a-50a5-85e6-d4c3-5cf62f1f31c8');
	deploy_mint_dashlets_for_users('employee',$role_employee);
	$role_manager = array('westj', 'howardr', 'woodd', 'blacko');
	$role_manager = array('60e00d24-af21-f93c-272d-5ce3c65ac237', 'b5dcff6f-1a14-56bf-eecd-5ce3c791a112', '59330569-01eb-9b31-0fba-5ce3c5c427d2', 'bf3cb12c-88b6-e637-022e-5ce3c7df4ab7');
	deploy_mint_dashlets_for_users('manager',$role_manager);
}

function deploy_mint_dashlets_for_users($dashboardmanager_id,$users)
{
	$dm_object = BeanFactory::getBean('DashboardManager', $dashboardmanager_id);
	$dd = new DashboardDeployer($dm_object);

    foreach ($users as $user_id) {
		$user = BeanFactory::getBean('Users', $user_id);
        if ($user) {
            $dd->deployForRole($user);
        }
    }	

}