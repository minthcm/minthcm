<?php

$main = array(
    'columns' => array(
        0 => array(
            'width' => '60%',
            'dashlets' => array(
                2 => 'main_calendar_dashlet',
                4 => 'main_leave_of_absence_dashlet',
                6 => 'main_reservation_dashlet',
            ),
        ),
        1 => array(
            'width' => '40%',
            'dashlets' => array(
                3 => 'main_todays_work_schedule_dashlet',
                6 => 'main_daily_work_schedule_dashlet',
                7 => 'main_my_tasks_dashlet',
            ),
        ),
    ),
    'numColumns' => '3',
    'pageTitle' => 'LBL_HOME_PAGE_1_NAME',
);

$recruitment = array(
    'columns' => array(
        0 => array(
            'dashlets' => array(
                0 => 'recruitment_kreports_dashlet',
            ),
            'width' => '60%',
        ),
        1 => array(
            'dashlets' => array(
                0 => 'recruitment_kreports_second_dashlet',
            ),
            'width' => '40%',
        ),
    ),
    'pageTitle' => 'LBL_RECRUITMENT_DASHBOARD',
    'numColumns' => '2',
);

$hr_manager = array(
    'columns' => array(
        0 => array(
            'dashlets' => array(
                0 => 'hr_manager_trainings_dashlet',
                1 => 'hr_manager_appraisals_dashlet',
                2 => 'hr_manager_onboardings_dashlet',
                3 => 'hr_manager_offboardings_dashlet',
            ),
            'width' => '60%',
        ),
        1 => array(
            'dashlets' => array(
                0 => 'hr_manager_recruitments_dashlet',
                1 => 'hr_manager_candidatures_dashlet',
            ),
            'width' => '40%',
        ),
    ),
    'pageTitle' => 'LBL_HR_MANAGER_DASHBOARD',
    'numColumns' => '2',
);

$my_team = array(
    'columns' => array(
        0 => array(
            'dashlets' => array(
                0 => 'my_team_ideas_dashlet',
                1 => 'my_team_applications_dashlet',
            ),
            'width' => '60%',
        ),
        1 => array(
            'dashlets' => array(
                0 => 'my_team_work_schedules_dashlet',
            ),
            'width' => '40%',
        ),
    ),
    'pageTitle' => 'LBL_MY_TEAM_DASHBOARD',
    'numColumns' => '2',
);

$employee = array(
    'columns' => array(
        0 => array(
            'dashlets' => array(
                0 => 'employee_work_schedules_dashlet',
                1 => 'employee_trainings_dashlet',
                2 => 'employee_goals_dashlet',
            ),
            'width' => '60%',
        ),
        1 => array(
            'dashlets' => array(
                0 => 'employee_ideas_dashlet',
                1 => 'employee_applications_dashlet',
            ),
            'width' => '40%',
        ),
    ),
    'pageTitle' => 'LBL_MY_RECORDS_DASHBOARD',
    'numColumns' => '2',
);

$hr_actions = array(
    'columns' => array(
        0 => array(
            'dashlets' => array(
                0 => 'hr_actions_my_meetings_dashlet',
                1 => 'hr_actions_my_calls_dashlet',
                2 => 'hr_actions_onboardings_dashlet',
            ),
            'width' => '60%',
        ),
        1 => array(
            'dashlets' => array(
                0 => 'hr_actions_recruitments_dashlet',
                1 => 'hr_actions_candidatures_dashlet',
                2 => 'hr_actions_offboardings_dashlet',
            ),
            'width' => '40%',
        ),
    ),
    'pageTitle' => 'LBL_HR_ACTIONS_DASHBOARD' ,
    'numColumns' => '2',
);

$settlements = array(
    'columns' => array(
        0 => array(
            'dashlets' => array(
                0 => 'settlements_work_schedules_dashlet',
                1 => 'settlements_contracts_dashlet',
                2 => 'settlements_delegations_dashlet',
            ),
            'width' => '60%',
        ),
        1 => array(
            'dashlets' => array(
                0 => 'settlements_second_work_schedules_dashlet',
                1 => 'settlements_second_settlements_contracts_dashlet',
                2 => 'settlements_second_delegations_dashlet',
            ),
            'width' => '40%',
        ),
    ),
    'pageTitle' => 'LBL_SPENT_TIME_DASHBOARD',
    'numColumns' => '2',
);
