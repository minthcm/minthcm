<?php

class Comments extends Basic
{
    public $new_schema = true;
    public $module_dir = 'Comments';
    public $object_name = 'Comments';
    public $table_name = 'comments';

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
    public $date_edited;

    public function bean_implements($interface)
    {
        switch ($interface) {
            case 'ACL':
                return true;
        }

        return false;
    }

    public function save($check_notify = false)
    {
        $this->setDateEdited();
        parent::save($check_notify);
    }

    public function getAuthorFullName()
    {
        $author = BeanFactory::getBean('Users', $this->assigned_user_id);
        return empty($author->first_name) ? $author->last_name : $author->first_name . ' ' . $author->last_name;
    }

    public function getAuthorPhoto()
    {
        $author = BeanFactory::getBean('Users', $this->assigned_user_id);
        return $author->photo;
    }

    protected function setDateEdited()
    {
        if (
            !empty($this->fetched_row)
            && $this->description !== $this->fetched_row['description']
        ) {
            global $timedate;
            $this->date_edited = $timedate->getNow()->format(TimeDate::DB_DATETIME_FORMAT);
        }
    }
}
