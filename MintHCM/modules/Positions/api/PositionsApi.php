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

    public function checkSubordinatedPositions($params) {
        if (
            (in_array($params['positions_supervision_id'], $this->getSubordinatedPositions(Array($params['id']))) ||
            $params['id'] == $params['positions_supervision_id']) &&
            !empty($params['positions_supervision_id'])
        ) {
            return false;
        }
        else
        {
            return true;
        }
    }

    protected function getSubordinatedPositions(Array $position_ids)
    {
        global $db;
        $return = array();
        $sql = "SELECT id FROM positions WHERE positions_supervision_id IN('" . implode("','", $position_ids) . "') AND deleted = 0";
        $r = $db->query($sql);
        while ( $a = $db->fetchByAssoc($r) ) {
        $return[] = $a['id'];
        }
        if ( !empty($return) ) {
        $return = array_merge($return, $this->getSubordinatedPositions($return));
        }
        return array_unique($return);
    }

}
