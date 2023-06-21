<?php

require_once 'include/SugarObjects/templates/file/File.php';

class Files extends File
{
    public $new_schema = true;
    public $module_dir = 'Files';
    public $object_name = 'Files';
    public $table_name = 'files';
    public $importable = true;

    public $id;
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
    public $SecurityGroups;
    public $document_name;
    public $filename;
    public $file_ext;
    public $file_mime_type;
    public $uploadfile;
    public $active_date;
    public $exp_date;
    public $category_id;
    public $subcategory_id;
    public $status_id;
    public $status;

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }

}
