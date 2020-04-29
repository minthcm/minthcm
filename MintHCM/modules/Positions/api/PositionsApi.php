<?php

class PositionsApi
{

    public function getOnboardingOffboardingName($args)
    {
        $data = [];
        $employee = BeanFactory::getBean('Users', $args['employee_id']);
        $position_id = $employee->position_id;
        $GLOBALS['log']->fatal($args);
        $focus = BeanFactory::getBean('Positions', $position_id);
        if ($args['boarding'] == 'OffboardingTemplates') {
            $data['parent_id'] = $focus->offboardingtemplate_id;
            $data['parent_name'] = $focus->offboardingtemplate_name;
        } elseif ($args['boarding'] == 'OnboardingTemplates') {
            $data['parent_id'] = $focus->onboardingtemplate_id;
            $data['parent_name'] = $focus->onboardingtemplate_name;
        }

        return $data;
    }

}
