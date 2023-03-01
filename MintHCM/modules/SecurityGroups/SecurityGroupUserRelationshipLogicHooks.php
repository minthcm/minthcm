<?php

class SecurityGroupUserRelationshipLogicHooks
{

    public function afterRelationshipAdd($bean, $event, $arguments)
    {
        if ($arguments['relationship'] == "securitygroups_employees" && $arguments['related_module'] == "SecurityGroups") {
            $target_security_group_id = $arguments['related_id'];
            if ($bean->load_relationship('SecurityGroups')) {
                $bean->SecurityGroups->add($target_security_group_id);
            }
        }
    }
}
