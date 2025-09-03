<?php

/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM, 
 * Copyright (C) 2018-2023 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM" 
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, the 
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and 
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */
if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

/**
 * Class SpotsController.
 */
class SpotsController extends SugarController {

   protected $nullSqlPlaceholder = '';
   protected $action_remap = array( 'DetailView' => 'editview', 'index' => 'listview' );
   //These are the file paths for the cached results of the spot data sets
   protected $spotFilePath = 'cache/modules/Spots/';
   protected $servicesFileName = 'service.json';
   protected $salesFileName = 'sales.json';
   protected $marketingsFileName = 'marketing.json';
   protected $marketingActivitiesFileName = 'marketingActivity.json';
   protected $activitiesFileName = 'activities.json';
   protected $quotesFileName = 'quotes.json';
   //This is when to consider a data file as stale and replace it (should not be an issue if the scheduler is running)
   //This is the time in seconds, so an hour is 3600
   protected $spotsStaleTime = 3600;

   /**
    * This returns a string of the type of db being used.
    *
    * @return a string of the type of db being used (mysql, mssql or undefined)
    */
   public function getDatabaseType() {
      global $sugar_config;
      $dbType = 'undefined';
      if ( $sugar_config['dbconfig']['db_type'] == 'mysql' ) {
         $dbType = 'mysql';
      } elseif ( $sugar_config['dbconfig']['db_type'] == 'mssql' ) {
         $dbType = 'mssql';
      }

      return $dbType;
   }

   /**
    * This is a duplicate of the build_report_access_query in AOR_Report (here for autonomy).
    *
    * @param SugarBean $module the $module to return the access query for
    * @param string    $alias  the alias for the table
    *
    * @return string $where the where clause to represent access
    */
    public function buildSpotsAccessQuery(SugarBean $module, $alias)
    {
        $module->table_name = $alias;
        $where = '';
        if ($module->bean_implements('ACL') && ACLController::requireOwner($module->module_dir, 'list')) {
            global $current_user;
            $owner_where = $module->getOwnerWhere($current_user->id);
            $where = ' AND '.$owner_where;
        }

        if (file_exists('modules/SecurityGroups/SecurityGroup.php')) {
            /* BEGIN - SECURITY GROUPS */
            if ($module->bean_implements('ACL') && ACLController::requireSecurityGroup($module->module_dir, 'list')) {
                require_once 'modules/SecurityGroups/SecurityGroup.php';
                global $current_user;
                $owner_where = $module->getOwnerWhere($current_user->id);
                $group_where = SecurityGroup::getGroupWhere($alias, $module->module_dir, $current_user->id);
                if (!empty($owner_where)) {
                    $where .= ' AND ('.$owner_where.' or '.$group_where.') ';
                } else {
                    $where .= ' AND '.$group_where;
                }
            }
        }

        return $where;
    }

   /**
    * Returns the cached service file, will create it first if it is out of date / does not exist.
    *
    * @return string returns a string representation of the service file
    */
   public function action_getServiceSpotsData() {
      $userId = $_SESSION['authenticated_user_id'];
      $fileLocation = $this->spotFilePath . $userId . '_' . $this->servicesFileName;
      if ( file_exists($fileLocation) && (time() - filemtime($fileLocation) < $this->spotsStaleTime) ) {
         echo file_get_contents($fileLocation);
      } else {
         $this->action_createServiceSpotsData($fileLocation);
         echo file_get_contents($fileLocation);
      }
   }

   /**
    * This creates the cached file for service.
    *
    * @param string $filepath the filepath to save the cached file
    */
   public function action_createServiceSpotsData($filepath) {
      global $mod_strings;
      $returnArray = array();
      $db = DBManagerFactory::getInstance();

      $mysqlSelect = <<<EOF
        SELECT
            cases.state,
            cases.status,
            cases.priority,
            DAYNAME(cases.date_entered) as day,
            CAST(WEEK(cases.date_entered) as CHAR(5)) as week,
            concat('(',MONTH(cases.date_entered),') ',MONTHNAME(cases.date_entered)) as month,
            COALESCE(QUARTER(cases.date_entered),'$this->nullSqlPlaceholder') as quarter,
            CAST(YEAR(cases.date_entered) as CHAR(10)) as year,
            COALESCE(NULLIF(RTRIM(LTRIM(CONCAT(COALESCE(u2.first_name,''),' ',COALESCE(u2.last_name,'')))),''),'$this->nullSqlPlaceholder') as contactName,
            RTRIM(LTRIM(CONCAT(COALESCE(users.first_name,''),' ',COALESCE(users.last_name,'')))) as assignedUser
EOF;
      $mssqlSelect = <<<EOF
        SELECT
            cases.state,
            cases.status,
            cases.priority,
            DATENAME(weekday,cases.date_entered) as day,
            CAST(DATEPART(wk,cases.date_entered) as CHAR(5)) as week,
            '(' + CAST(DATEPART(mm,cases.date_entered)as CHAR(12)) + ') ' + DATENAME(month,DATEPART(mm,cases.date_entered)) as month,
            COALESCE(DATEPART(qq,cases.date_entered),'$this->nullSqlPlaceholder') as quarter,
            CAST(YEAR(cases.date_entered) as CHAR(10)) as year,
            COALESCE(NULLIF(RTRIM(LTRIM(COALESCE(u2.first_name,'') + ' ' + COALESCE(u2.last_name,''))),''),'$this->nullSqlPlaceholder') as contactName,
            RTRIM(LTRIM(COALESCE(users.first_name,'') + ' ' + COALESCE(users.last_name,''))) as assignedUser
EOF;

      $fromClause = <<<EOF
        FROM cases
        INNER JOIN users
            ON cases.assigned_user_id = users.id
        LEFT JOIN users u2
            ON cases.contact_created_by_id = u2.id
            AND u2.deleted = 0
EOF;
      $whereClause = <<<EOF
        WHERE cases.deleted = 0
        AND users.deleted = 0
EOF;

      $query = '';
      if ( $this->getDatabaseType() === 'mssql' ) {
         $query = $mssqlSelect . ' ' . $fromClause . ' ' . $whereClause;
      } elseif ( $this->getDatabaseType() === 'mysql' ) {
         $query = $mysqlSelect . ' ' . $fromClause . ' ' . $whereClause;
      } else {
         $GLOBALS['log']->error($mod_strings['LBL_AN_UNSUPPORTED_DB']);

         return;
      }

      $cases = BeanFactory::getBean('Cases');
      $users = BeanFactory::getBean('Users');
      $aclWhereCases = $this->buildSpotsAccessQuery($cases, $cases->table_name);
      $aclWhereUsers = $this->buildSpotsAccessQuery($users, $users->table_name);

      $queryString = $query . $aclWhereCases . $aclWhereUsers;
      $result = $db->query($queryString);

      while ( $row = $db->fetchByAssoc($result) ) {
         $x = new stdClass();
         // View Tools start
         //$x->{$mod_strings['LBL_AN_SERVICE_STATE']} = $row['state'];
         //$x->{$mod_strings['LBL_AN_SERVICE_STATUS']} = $row['status'];
         //$x->{$mod_strings['LBL_AN_SERVICE_PRIORITY']} = $row['priority'];
         $x->{$mod_strings['LBL_AN_SERVICE_STATE']} = $this->translateAppString('case_state_dom', $row['state']);
         $x->{$mod_strings['LBL_AN_SERVICE_STATUS']} = $this->translateAppString('case_status_dom', $row['status']);
         $x->{$mod_strings['LBL_AN_SERVICE_PRIORITY']} = $this->translateAppString('case_priority_dom', $row['priority']);
         // View Tools end
         $x->{$mod_strings['LBL_AN_SERVICE_CREATED_DAY']} = $row['day'];
         $x->{$mod_strings['LBL_AN_SERVICE_CREATED_WEEK']} = $row['week'];
         $x->{$mod_strings['LBL_AN_SERVICE_CREATED_MONTH']} = $row['month'];
         $x->{$mod_strings['LBL_AN_SERVICE_CREATED_QUARTER']} = $row['quarter'];
         $x->{$mod_strings['LBL_AN_SERVICE_CREATED_YEAR']} = $row['year'];
         $x->{$mod_strings['LBL_AN_SERVICE_CONTACT_NAME']} = $row['contactName'];
         $x->{$mod_strings['LBL_AN_SERVICE_ASSIGNED_TO']} = $row['assignedUser'];

         $returnArray[] = $x;
      }
      file_put_contents($filepath, json_encode($returnArray));
   }

   /**
    * Returns the cached activities file, will create it first if it is out of date / does not exist.
    *
    * @return string returns a string representation of the activities file
    */
   public function action_getActivitiesSpotsData() {
      $userId = $_SESSION['authenticated_user_id'];
      $fileLocation = $this->spotFilePath . $userId . '_' . $this->activitiesFileName;
      if ( file_exists($fileLocation) && (time() - filemtime($fileLocation) < $this->spotsStaleTime) ) {
         echo file_get_contents($fileLocation);
      } else {
         $this->action_createActivitiesSpotsData($fileLocation);
         echo file_get_contents($fileLocation);
      }
   }

   /**
    * This creates the cached file for activities.
    *
    * @param string $filepath the filepath to save the cached file
    */
   public function action_createActivitiesSpotsData($filepath) {
      global $mod_strings;
      $returnArray = array();
      $db = DBManagerFactory::getInstance();

      $mysqlQueryCalls = <<<EOF
        SELECT
            'call' as type
            , calls.name
            , calls.status
            , RTRIM(LTRIM(CONCAT(COALESCE(users.first_name,''),' ',COALESCE(users.last_name,'')))) as assignedUser
        FROM calls
        LEFT JOIN users
            ON calls.assigned_user_id = users.id
            AND users.deleted = 0
        WHERE calls.deleted = 0
EOF;

      $mysqlQueryMeetings = <<<EOF
        UNION ALL
        SELECT
            'meeting' as type
            , meetings.name
            , meetings.status
            , RTRIM(LTRIM(CONCAT(COALESCE(users.first_name,''),' ',COALESCE(users.last_name,'')))) as assignedUser
        FROM meetings
        LEFT JOIN users
            ON meetings.assigned_user_id = users.id
            AND users.deleted = 0
        WHERE meetings.deleted = 0
EOF;

      $mysqlQueryTasks = <<<EOF
        UNION ALL
        SELECT
            'task' as type
            , tasks.name
            , tasks.status
            , RTRIM(LTRIM(CONCAT(COALESCE(users.first_name,''),' ',COALESCE(users.last_name,'')))) as assignedUser
        FROM tasks
        LEFT JOIN users
            ON tasks.assigned_user_id = users.id
            AND users.deleted = 0
        WHERE tasks.deleted = 0
EOF;

      $mssqlQueryCalls = <<<EOF
        SELECT
            'call' as type
            , calls.name
            , calls.status
            , RTRIM(LTRIM(COALESCE(users.first_name,'') + ' ' + COALESCE(users.last_name,''))) as assignedUser
        FROM calls
        LEFT JOIN users
            ON calls.assigned_user_id = users.id
            AND users.deleted = 0
        WHERE calls.deleted = 0
EOF;
      $mssqlQueryMeetings = <<<EOF
        UNION ALL
        SELECT
            'meeting' as type
            , meetings.name
            , meetings.status
            , RTRIM(LTRIM(COALESCE(users.first_name,'') + ' ' + COALESCE(users.last_name,''))) as assignedUser
        FROM meetings
        LEFT JOIN users
            ON meetings.assigned_user_id = users.id
            AND users.deleted = 0
        WHERE meetings.deleted = 0
EOF;
      $mssqlQueryTasks = <<<EOF
        UNION ALL
        SELECT
            'task' as type
            , tasks.name
            , tasks.status
            , RTRIM(LTRIM(COALESCE(users.first_name,'') + ' ' + COALESCE(users.last_name,''))) as assignedUser
        FROM tasks
        LEFT JOIN users
            ON tasks.assigned_user_id = users.id
            AND users.deleted = 0
        WHERE tasks.deleted = 0
EOF;

      $calls = BeanFactory::getBean('Calls');
      $aclWhereCalls = $this->buildSpotsAccessQuery($calls, $calls->table_name);
      $meetings = BeanFactory::getBean('Meetings');
      $aclWhereMeetings = $this->buildSpotsAccessQuery($meetings, $meetings->table_name);
      $tasks = BeanFactory::getBean('Tasks');
      $aclWhereTasks = $this->buildSpotsAccessQuery($tasks, $tasks->table_name);

      $query = '';
      if ( $this->getDatabaseType() === 'mssql' ) {
         $query = $mssqlQueryCalls . $aclWhereCalls . $mssqlQueryMeetings . $aclWhereMeetings . $mssqlQueryTasks . $aclWhereTasks;
      } elseif ( $this->getDatabaseType() === 'mysql' ) {
         $query = $mysqlQueryCalls . $aclWhereCalls . $mysqlQueryMeetings . $aclWhereMeetings . $mysqlQueryTasks . $aclWhereTasks;
      } else {
         $GLOBALS['log']->error($mod_strings['LBL_AN_UNSUPPORTED_DB']);

         return;
      }

      $result = $db->query($query);

      while ( $row = $db->fetchByAssoc($result) ) {
         $x = new stdClass();
         // View Tools start
         //$x->{$mod_strings['LBL_AN_ACTIVITIES_TYPE']} = $row['type'];
         $x->{$mod_strings['LBL_AN_ACTIVITIES_TYPE']} = $this->translateAppString('spots_activities_type_dom', $row['type']);
         // View Tools end
         $x->{$mod_strings['LBL_AN_ACTIVITIES_NAME']} = $row['name'];
         // View Tools start
         //$x->{$mod_strings['LBL_AN_ACTIVITIES_STATUS']} = $row['status'];
         $x->{$mod_strings['LBL_AN_ACTIVITIES_STATUS']} = $this->translateAppStringByActivitiesType($row['type'], $row['status']);
         // View Tools end
         $x->{$mod_strings['LBL_AN_ACTIVITIES_ASSIGNED_TO']} = $row['assignedUser'];

         $returnArray[] = $x;
      }
      file_put_contents($filepath, json_encode($returnArray));
   }

   /**
    * Returns the cached marketing activity file, will create it first if it is out of date / does not exist.
    *
    * @return string returns a string representation of the marketing activity file
    */
   public function action_getMarketingActivitySpotsData() {
      $userId = $_SESSION['authenticated_user_id'];
      $fileLocation = $this->spotFilePath . $userId . '_' . $this->marketingActivitiesFileName;
      if ( file_exists($fileLocation) && (time() - filemtime($fileLocation) < $this->spotsStaleTime) ) {
         echo file_get_contents($fileLocation);
      } else {
         $this->action_createMarketingActivitySpotsData($fileLocation);
         echo file_get_contents($fileLocation);
      }
   }

   /**
    * This creates the cached file for marketing activity.
    *
    * @param string $filepath the filepath to save the cached file
    */
   public function action_createMarketingActivitySpotsData($filepath) {
      global $mod_strings;
      $returnArray = array();
      $db = DBManagerFactory::getInstance();

      $query = <<<EOF
        SELECT
            campaigns.name,
            campaign_log.activity_date,
            campaign_log.activity_type,
            campaign_log.related_type,
            campaign_log.related_id
        FROM campaigns
        LEFT JOIN campaign_log
            ON campaigns.id = campaign_log.campaign_id
            and campaign_log.deleted = 0
        where campaigns.deleted = 0

EOF;

      $campaigns = BeanFactory::getBean('Campaigns');
      $aclWhereCampaigns = $this->buildSpotsAccessQuery($campaigns, $campaigns->table_name);

      $queryString = $query . $aclWhereCampaigns;
      $result = $db->query($queryString);

      while ( $row = $db->fetchByAssoc($result) ) {
         $x = new stdClass();
         $x->{$mod_strings['LBL_AN_MARKETINGACTIVITY_CAMPAIGN_NAME']} = $row['name'];
         $x->{$mod_strings['LBL_AN_MARKETINGACTIVITY_ACTIVITY_DATE']} = $row['activity_date'];
         // View Tools start
         //$x->{$mod_strings['LBL_AN_MARKETINGACTIVITY_ACTIVITY_TYPE']} = $row['activity_type'];
         //$x->{$mod_strings['LBL_AN_MARKETINGACTIVITY_RELATED_TYPE']} = $row['related_type'];
         $x->{$mod_strings['LBL_AN_MARKETINGACTIVITY_ACTIVITY_TYPE']} = $this->translateAppString('campainglog_activity_type_dom', $row['activity_type']);
         $x->{$mod_strings['LBL_AN_MARKETINGACTIVITY_RELATED_TYPE']} = $this->translateAppString('spots_campainlog_related_type_dom', $row['related_type']);
         // View Tools end
         $x->{$mod_strings['LBL_AN_MARKETINGACTIVITY_RELATED_ID']} = $row['related_id'];

         $returnArray[] = $x;
      }
      file_put_contents($filepath, json_encode($returnArray));
   }

   // View Tools start
   protected function translateAppString($app_string_key, $sub_key) {
      global $app_list_strings;
      $value = '';
      if ( isset($app_list_strings[$app_string_key][$sub_key]) ) {
         $value = $app_list_strings[$app_string_key][$sub_key];
      } else {
         $value = $sub_key;
      }
      return $value;
   }

   protected function translateAppStringByActivitiesType($type, $status) {
      $value = '';
      switch ( $type ) {
         case 'call':
            $value = $this->translateAppString('call_status_dom', $status);
            break;
         case 'meeting':
            $value = $this->translateAppString('meeting_status_dom', $status);
            break;
         case 'task':
            $value = $this->translateAppString('task_status_dom', $status);
            break;
         default:
            $value = $status;
      }
      return $value;
   }

   // View Tools end
}
