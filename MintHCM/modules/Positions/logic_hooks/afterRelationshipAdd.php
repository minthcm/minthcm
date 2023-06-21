<?php

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

class afterRelationshipAdd {
    public function after_relationship_add($bean, $event, $arguments)
    {
        if ($arguments['related_module'] == 'SecurityGroups') {
            $bean->load_relationship('employees');
            $employees_ids = $bean->employees->get();
            foreach ($employees_ids as $emp_id) {
                $employee = BeanFactory::getBean('Users', $emp_id);
                $employee->load_relationship('SecurityGroups');
                $employee->SecurityGroups->add($arguments['related_id']);
                $employee->save();
            }
        }
    }
}
