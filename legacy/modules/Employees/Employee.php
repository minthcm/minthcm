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

use SuiteCRM\Search\ElasticSearch\ElasticSearchHooks;

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/SugarObjects/templates/person/Person.php';
require_once __DIR__ . '/../../include/EmailInterface.php';
// MintHCM #77675 start
require_once 'modules/SecurityGroups/PrivateGroup.php';
// MintHCM #77675 end

// Employee is used to store customer information.
class Employee extends Person implements EmailInterface
{
    // Stored fields
    public $name = '';
    public $id;
    public $is_admin;
    public $first_name;
    public $last_name;
    public $full_name;
    public $user_name;
    public $title;
    public $description;
    public $department;
    public $reports_to_id;
    public $reports_to_name;
    public $phone_home;
    public $phone_mobile;
    public $phone_work;
    public $phone_other;
    public $phone_fax;
    public $email1;
    public $email2;
    public $primary_address_street;
    public $primary_address_city;
    public $primary_address_state;
    public $primary_address_postalcode;
    public $primary_address_country;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $created_by;
    public $created_by_name;
    public $modified_by_name;
    public $status;
    public $messenger_id;
    public $messenger_type;
    public $employee_status;
    public $error_string;
    public $position_id;
    public $person_id;
    public $module_dir = "Employees";
    public $table_name = "users";
    public $object_name = "Employee";
    public $user_preferences;
    public $encodeFields = array("first_name", "last_name", "description");
    // This is used to retrieve related fields from form posts.
    public $additional_column_fields = array('reports_to_name');
    public $new_schema = true;
    // MintHCM #123323 START
    public $securitygroup_id;
    public $SecurityGroups;
    // MintHCM #123323 END

    public function __construct()
    {
        parent::__construct();
        $this->setupCustomFields('Users');
        $this->emailAddress = new SugarEmailAddress();
    }

    public function get_summary_text()
    {
        $this->_create_proper_name_field();
        return $this->name;
    }

    public function fill_in_additional_list_fields()
    {
        $this->fill_in_additional_detail_fields();
    }

    public function fill_in_additional_detail_fields()
    {
        global $locale;
        $query = "SELECT u1.first_name, u1.last_name from users u1, users u2 where u1.id = u2.reports_to_id AND u2.id = '$this->id' and u1.deleted=0";
        $result = $this->db->query($query, true, "Error filling in additional detail fields");

        $row = $this->db->fetchByAssoc($result);

        if ($row != null) {
            $this->reports_to_name = stripslashes($locale->getLocaleFormattedName($row['first_name'], $row['last_name']));
        } else {
            $this->reports_to_name = '';
        }
    }

    public function retrieve_employee_id($employee_name)
    {
        $query = "SELECT id from users where user_name='$employee_name' AND deleted=0";
        $result = $this->db->query($query, false, "Error retrieving employee ID: ");
        $row = $this->db->fetchByAssoc($result);
        return $row['id'];
    }

    /**
     * @return -- returns a list of all employees in the system.
     * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
     * All Rights Reserved..
     * Contributor(s): ______________________________________..
     */
    public function verify_data()
    {
        //none of the checks from the users module are valid here since the user_name and
        //is_admin_on fields are not editable.
        return true;
    }

    public function get_list_view_data()
    {

        $user_fields = parent::get_list_view_data();

        // Copy over the reports_to_name
        if (isset($GLOBALS['app_list_strings']['messenger_type_dom'][$this->messenger_type])) {
            $user_fields['MESSENGER_TYPE'] = $GLOBALS['app_list_strings']['messenger_type_dom'][$this->messenger_type];
        }
        if (isset($GLOBALS['app_list_strings']['employee_status_dom'][$this->employee_status])) {
            $user_fields['EMPLOYEE_STATUS'] = $GLOBALS['app_list_strings']['employee_status_dom'][$this->employee_status];
        }
        $user_fields['REPORTS_TO_NAME'] = $this->reports_to_name;

        return $user_fields;
    }

    public function list_view_parse_additional_sections(&$list_form/* , $xTemplateSection */)
    {
        return $list_form;
    }

    public function create_export_query($order_by, $where, $relate_link_join = '')
    {
        global $current_user;
        if (!is_admin($current_user)) {
            throw new RuntimeException('Not authorized');
        }

        include 'modules/Employees/field_arrays.php';

        $cols = '';
        foreach ($fields_array['Employee']['export_fields'] as $field) {
            $cols .= (empty($cols)) ? '' : ', ';
            $cols .= $field;
        }

        $query = "SELECT {$cols} FROM users ";

        $where_auto = " users.deleted = 0";

        if ($where != "") {
            $query .= " WHERE $where AND ".$where_auto;
        } else {
            $query .= " WHERE ".$where_auto;
        }

        if ($order_by != "") {
            $query .= " ORDER BY $order_by";
        } else {
            $query .= " ORDER BY users.user_name";
        }

        return $query;
    }
    //use parent class

    /**
     * Generate the name field from the first_name and last_name fields.
     */
    /*
      function _create_proper_name_field() {
      global $locale;
      $full_name = $locale->getLocaleFormattedName($this->first_name, $this->last_name);
      $this->name = $full_name;
      $this->full_name = $full_name;
      }
     */

    public function preprocess_fields_on_save()
    {
        parent::preprocess_fields_on_save();
    }

    /**
     * create_new_list_query
     *
     * Return the list query used by the list views and export button. Next generation of create_new_list_query function.
     *
     * We overrode this function in the Employees module to add the additional filter check so that we do not retrieve portal users for the Employees list view queries
     *
     * @param string $order_by custom order by clause
     * @param string $where custom where clause
     * @param array $filter Optioanal
     * @param array $params Optional     *
     * @param int $show_deleted Optional, default 0, show deleted records is set to 1.
     * @param string $join_type
     * @param boolean $return_array Optional, default false, response as array
     * @param object $parentbean creating a subquery for this bean.
     * @param boolean $singleSelect Optional, default false.
     * @return string select query string, optionally an array value will be returned if $return_array= true.
     */
    public function create_new_list_query(
        $order_by,
        $where,
        $filter = array(),
        $params = array(),
        $show_deleted = 0,
        $join_type = '',
        $return_array = false,
        $parentbean = null,
        $singleSelect = false,
        $ifListForExport = false
    )
    {
        //create the filter for portal only users, as they should not be showing up in query results
        if (empty($where)) {
            $where = ' users.portal_only = 0 ';
        } else {
            $where .= ' and users.portal_only = 0 ';
        }

        //return parent method, specifying for array to be returned
        return parent::create_new_list_query(
                $order_by,
                $where,
                $filter,
                $params,
                $show_deleted,
                $join_type,
                $return_array,
                $parentbean,
                $singleSelect,
                $ifListForExport
        );
    }
    /*
     * Overwrite Sugar bean which returns the current objects custom fields.  Lets return User custom fields instead
     */

    public function hasCustomFields()
    {

        //Check to see if there are custom user fields that we should report on, first check the custom_fields array
        $userCustomfields = !empty($GLOBALS['dictionary']['Employee']['custom_fields']);
        if (!$userCustomfields) {
            //custom Fields not set, so traverse employee fields to see if any custom fields exist
            foreach ($GLOBALS['dictionary']['Employee']['fields'] as $k => $v) {
                if (!empty($v['source']) && $v['source'] == 'custom_fields') {
                    //custom field has been found, set flag to true and break
                    $userCustomfields = true;
                    break;
                }
            }
        }

        //return result of search for custom fields
        return $userCustomfields;
    }

    /**
     * Override the original save function,
     * for checking first is it same user as employee
     * and disable to save any employee data for others.
     * (admin user is an exception)
     *
     * @param bool $check_notify
     * @return bool|string
     */
    public function save($check_notify = false)
    {
        global $current_user;
        if ($current_user->id && $this->id != $current_user->id) {
            if (
                !is_admin($current_user) &&
                !ACLAction::userHasAccess($GLOBALS['current_user']->id, 'Employees', 'edit', 'module', $current_user->id == $this->id)
                ) {
                $GLOBALS['log']->security("{$current_user->name} tried to update {$this->name} record with out permission.");
                $GLOBALS['log']->fatal("You can change only your own employee data.");

                return false;
            }
        }
        // MintHCM 77675 start
        if ($this->reports_to_id != $this->fetched_row['reports_to_id']) {
            $private_group = new PrivateGroup($this);
            $private_group->update($this->reports_to_id, $this->fetched_row['reports_to_id']);
        }
        // MintHCM 77675 end

        if (!$this->hasSaveAccess()) {
            throw new RuntimeException('Not authorized');
        }

        // If the current user is not an admin, reset the admin flag to the original value.
        $this->setIsAdmin();

        return parent::save($check_notify);
    }

    // MintHCM start
    public function fetchAllResponsibilities()
    {

        $query = "
      SELECT responsibilities.*
      FROM responsibilities
      WHERE responsibilities.deleted=0
      AND responsibilities.id
      IN (
         SELECT responsibilities_positions.responsibility_id
         FROM responsibilities_positions
         WHERE responsibilities_positions.position_id IN
            (SELECT positions.id FROM positions WHERE id='{$this->fetched_row['position_id']}')
      )
      OR responsibilities.id
      IN (
         SELECT responsibilities_roles.responsibility_id
         FROM responsibilities_roles
         WHERE responsibilities_roles.role_id IN
            (SELECT roles_employees.role_id FROM roles_employees WHERE roles_employees.employee_id='{$this->id}')
           )";

        return $query;
    }

    public function fetchAllSubordinates()
    {
        $query = "SELECT users.* FROM users WHERE users.deleted=0 AND users.reports_to_id='{$this->id}'";
        return $query;
    }

    protected function postSave()
    {
        if($this->securitygroup_id != $this->fetched_row['securitygroup_id'] && empty($this->securitygroup_id)){
            $this->load_relationship('SecurityGroups');
            $this->SecurityGroups->delete($this->fetched_row['securitygroup_id']);
        }
        if(!empty($this->user_name)){
            $user = BeanFactory::getBean('Users', $this->id);
            if(!empty($user->id) && $user->id === $this->id){
                (new ElasticSearchHooks())->beanSaved($user, 'after_save', []);
            }
        }
    }
    // MintHCM end

    // MintHCM start

    public function getActiveWorkplaces($workplace_id = null, $date_start = null, $date_end = null) {
        $date_start = empty($date_start) ? 'UTC_TIMESTAMP' : "{$this->db->quoted($date_start)}";
        $date_end = empty($date_end) ? 'UTC_TIMESTAMP' : "{$this->db->quoted($date_end)}";
        $sql = "SELECT w.id, w.name
                FROM (SELECT a.id, a.workplace_id, a.date_from, a.date_to
                    FROM allocations a
                    WHERE a.deleted = 0
                        AND a.assigned_user_id = {$this->db->quoted($this->id)}
                    UNION
                    SELECT ae.allocation_id, a.workplace_id, a.date_from, a.date_to id
                    FROM allocations_employees ae
                            LEFT JOIN allocations a ON a.id = ae.allocation_id AND ae.deleted = 0
                    WHERE ae.employee_id = {$this->db->quoted($this->id)}) all_allocations
                        INNER JOIN workplaces w ON w.id = workplace_id AND w.deleted = 0 AND w.availability = 'active'
                WHERE ( ({$date_start} BETWEEN all_allocations.date_from AND all_allocations.date_to)
                AND ({$date_end} BETWEEN all_allocations.date_from AND all_allocations.date_to) )
                OR  {$date_start} >= all_allocations.date_from AND all_allocations.date_to IS NULL
                ";
        if (!empty($workplace_id)) {
            $sql .= "AND w.id = {$this->db->quoted($workplace_id)}";
        }
        $result = [];
        $query = $this->db->query($sql);
        while ($row = $this->db->fetchByAssoc($query)) {
            array_push($result, $row);
        }
        return $result;
    }
    // MintHCM end
    
    /**
     * Check if current user can save the current employee record
     * @return bool
     */
    protected function hasSaveAccess(): bool
    {
        global $current_user;

        if (empty($this->id)) {
            return true;
        }

        if (empty($current_user->id)) {
            return true;
        }

        $sameUser = $current_user->id === $this->id;

        return $sameUser || is_admin($current_user);
    }

    /**
     * Reset is_admin if current user is not an admin user
     * @return void
     */
    protected function setIsAdmin(): void
    {
        global $current_user;

        if (!isset($this->is_admin)) {
            return;
        }

        $originalIsAdminValue = $this->is_admin ?? false;
        if ($this->isUpdate() && isset($this->fetched_row['is_admin'])) {
            $originalIsAdminValue = isTrue($this->fetched_row['is_admin'] ?? false);
        }

        $currentUserReloaded = BeanFactory::getReloadedBean('Users', $current_user->id);
        if (!is_admin($currentUserReloaded)) {
            $this->is_admin = $originalIsAdminValue;
        }

    }

    /**
     * @return bool
     */
    protected function isUpdate(): bool
    {
        return !empty($this->id) && !$this->new_with_id;
    }

    // MintHCM #123323 Users|Employees ACLAccess START
    public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set')
    {
        global $current_user;
        if ('edit' === $this->ACLNormalizeViewContext($view)) {
            return is_admin($current_user)
            || $this->id == $current_user->id
            || empty($this->id)
            || $this->created_by == $current_user->id;
        }
        if('delete' === $this->ACLNormalizeViewContext($view)){
            return is_admin($current_user)
            || (
                !is_admin($current_user) 
                && $this->id !== $current_user->id 
                && $this->is_admin != '1'
                && $this->created_by == $current_user->id
            );
        }
        return parent::ACLAccess($view, $is_owner, $in_group);
    }
    // MintHCM #123323 Users|Employees ACLAccess END

}