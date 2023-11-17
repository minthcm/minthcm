<?php

class AcceptWorkScheduleValidator
{
    protected $workschedule;
    protected $employee;
    protected $date_start;
    protected $date_end;
    protected $response;

    private $db;

    const SUCCESS = 1;
    const ERR_SPENT_TIMES_DO_NOT_OVERLAP_WITH_WORK_SCHEDULE = 2;
    const ERR_WORKPLACE_IS_REQUIRED = 3;
    const ERR_WORKPLACE_IS_NOT_ACTIVE = 4;
    const TYPES_THAT_SHOULD_BE_VALIDATED = ['office', 'home', 'delegation'];
    const OFFICE_TYPE = 'office';

    public function __construct($workschedule)
    {
        $this->workschedule = $workschedule;
        $this->db = DBManagerFactory::getInstance();
        $this->response = self::SUCCESS;
        $this->setEmployee();
        $this->setDatesInDbFormat();
    }

    public function validate()
    {
        if (!in_array($this->workschedule->type, self::TYPES_THAT_SHOULD_BE_VALIDATED)) {
            return self::SUCCESS;
        }
        $this->checkIfSpentTimesOverlapWithWorkschedule();
        $this->checkIfWorkPlaceShouldBeRequired();
        $this->checkIfWorkPlaceIsActive();
        return $this->response;
    }

    protected function checkIfSpentTimesOverlapWithWorkschedule()
    {
        $sql = "SELECT date_start, date_end
                FROM spenttime r
                    LEFT JOIN workschedules_spenttime wr ON r.id = wr.spenttime_id
                WHERE r.deleted = 0
                AND wr.deleted = 0
                AND wr.workschedule_id = {$this->db->quoted($this->workschedule->id)}
                ORDER BY r.date_start ASC
            ";
        $result = $this->db->query($sql);
        $current_row = [];
        while ($row = $this->db->fetchByAssoc($result)) {
            $current_row = $row;
            $current_date_start = $row['date_start'];
            if ($this->date_start == $current_date_start) {
                $this->date_start = $row['date_end'];
            } else {
                $this->response = self::ERR_SPENT_TIMES_DO_NOT_OVERLAP_WITH_WORK_SCHEDULE;
                return;
            }
        }
        $current_date_end = $current_row['date_end'];
        if ($this->date_end != $current_date_end) {
            $this->response = self::ERR_SPENT_TIMES_DO_NOT_OVERLAP_WITH_WORK_SCHEDULE;
        }
    }

    protected function checkIfWorkPlaceShouldBeRequired()
    {
        if (self::SUCCESS != $this->response 
            || !empty($this->workschedule->workplace_id) 
            || self::OFFICE_TYPE != $this->workschedule->type 
            || empty($this->employee->id)) 
        {
            return;
        }
        $active_workplaces = $this->employee->getActiveWorkplaces(null, $this->date_start, $this->date_end);
        if (!empty($active_workplaces)) {
            $this->response = self::ERR_WORKPLACE_IS_REQUIRED;
        }
    }

    protected function checkIfWorkPlaceIsActive()
    {
        if (self::SUCCESS != $this->response 
            || empty($this->workschedule->workplace_id) 
            || self::OFFICE_TYPE != $this->workschedule->type 
            || empty($this->employee->id)) 
        {
            return;
        }
        $active_workplaces = $this->employee->getActiveWorkplaces($this->workschedule->workplace_id, $this->date_start, $this->date_end);
        if (empty($active_workplaces)) {
            $this->response = self::ERR_WORKPLACE_IS_NOT_ACTIVE;
        }
    }

    protected function setEmployee()
    {
        $employee = BeanFactory::getBean('Employees', $this->workschedule->assigned_user_id);
        if (empty($employee->id)) {
            return;
        }
        $this->employee = $employee;
    }

    protected function setDatesInDbFormat() {
        global $timedate;
        $this->date_start = $timedate->to_db($this->workschedule->date_start);
        $this->date_end = $timedate->to_db($this->workschedule->date_end);
        if (empty($this->date_start) || empty($this->date_end)) {
            $this->date_start = $this->workschedule->date_start;
            $this->date_end = $this->workschedule->date_end;
        }
    }
}
