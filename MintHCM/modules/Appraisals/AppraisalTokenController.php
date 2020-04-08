<?php

require_once 'modules/Appraisals/AppraisalToken.php';

class AppraisalTokenController
{

    public function generateToken($employee_id, $appraisal_id)
    {
        $appraisal_token = null;
        if (!empty($employee_id) && !empty($appraisal_id)) {
            $appraisal_token = new AppraisalToken();
            $appraisal_token->employee_id = $employee_id;
            $appraisal_token->appraisal_id = $appraisal_id;
            $appraisal_token->save();
        }
        return $appraisal_token;
    }

    public function deactivateToken($employee_id, $appraisal_id)
    {
        if (!empty($employee_id) && !empty($appraisal_id)) {
            $appraisal_token = $this->getToken($employee_id, $appraisal_id);
            if (!empty($appraisal_token)) {
                return $appraisal_token->deactivate();
            }
        }
        return true;
    }

    public function getToken($employee_id, $appraisal_id)
    {
        global $db;
        $appraisal_token = null;
        if (!empty($employee_id) && !empty($appraisal_id)) {
            $sql = "SELECT id from appraisals_tokens WHERE employee_id = '{$employee_id}' AND appraisal_id = '{$appraisal_id}' AND deleted = 0";
            $appraisal_token_id = $db->getOne($sql);
            if (!empty($appraisal_token_id)) {
                $appraisal_token = new AppraisalToken();
                $appraisal_token->id = $appraisal_token_id;
                $appraisal_token->retrieve();
            }
        }
        return $appraisal_token;
    }
}
