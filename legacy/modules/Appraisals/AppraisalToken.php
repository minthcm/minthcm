<?php

class AppraisalToken
{
    public $id;
    public $date_entered;
    public $deleted;
    public $status;
    public $expired_date;
    public $token;
    public $employee_id;
    public $appraisal_id;

    public function save()
    {
        global $db;
        $id = $this->id;
        $employee_id = $this->employee_id;
        $appraisal_id = $this->appraisal_id;
        $deleted = $this->deleted;
        $status = $this->status;
        if (!empty($this->id)) {
            $sql = "UPDATE appraisals_tokens SET deleted = {$deleted}, status = {$status}, appraisal_id = {$appraisal_id} WHERE id = {$id}";
        } else {
            $id = create_guid();
            $token = create_guid();
            $sql = "INSERT INTO appraisals_tokens (id, date_entered, deleted, status, expired_date, token, employee_id, appraisal_id) VALUES ('{$id}', NOW(), 0, 1, DATE_ADD(NOW(), INTERVAL +{$this->sugar_config['days_to_token_expiration']} DAY), '{$token}', '{$employee_id}', '{$appraisal_id}')";
        }
        if ($db->query($sql) === true) {
            return $id;
        }
        return null;
    }

    public function retrieve()
    {
        global $db;
        $appraisal_token_id = $this->id;
        $select_sql = "SELECT * FROM appraisals_tokens WHERE id = '{$appraisal_token_id}'";
        $appraisal_token = $db->fetchOne($select_sql);
        if (!empty($appraisal_token)) {
            $this->id = $appraisal_token['id'];
            $this->date_entered = $appraisal_token['date_entered'];
            $this->deleted = $appraisal_token['deleted'];
            $this->status = $appraisal_token['status'];
            $this->expired_date = $appraisal_token['expired_date'];
            $this->token = $appraisal_token['token'];
            $this->employee_id = $appraisal_token['employee_id'];
            $this->appraisal_id = $appraisal_token['appraisal_id'];
        }
        return $this;

    }

    public function mark_deleted()
    {
        $this->deleted = 1;
        return $this->save();
    }

    public function deactivate()
    {
        $this->status = 0;
        return $this->save();
    }
}
