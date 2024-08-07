<?php

class jjwg_Areas_sugar extends Basic
{
    public $new_schema = true;
    public $module_dir = 'jjwg_Areas';
    public $object_name = 'jjwg_Areas';
    public $table_name = 'jjwg_areas';
    public $importable = true;
    public $disable_row_level_security = true;
    public $id;
    public $name;
    public $date_entered;
    public $date_modified;
    public $modified_user_id;
    public $modified_by_name;
    public $created_by;
    public $created_by_name;
    public $description;
    public $deleted;
    public $created_by_link;
    public $modified_user_link;
    public $assigned_user_id;
    public $assigned_user_name;
    public $assigned_user_link;
    public $city;
    public $state;
    public $country;
    public $coordinates;

    public function __construct()
    {
        parent::__construct();
    }

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL': return false;
        }
        return false;
    }

    public function ACLAccess($view, $is_owner = 'not_set', $in_group = 'not_set') {
        return false;
    }
}
