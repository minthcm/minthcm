<?php

class OnboardingStatus
{
    protected $focus;

    public function closeIfActivitiesAreHeld($focus)
    {
        $this->focus = $focus;
        if ($this->areTrainingsHeld()
            && $this->areExitInterviewsHeld()
            && $this->areTasksHeld()) {
            $this->close();

        }

    }
    protected function areTrainingsHeld()
    {
        global $db;
        $boarding_id = $this->focus->id;
        $sql = "SELECT id, status FROM trainings WHERE parent_id='{$boarding_id}'";
        $result = $db->query($sql);
        while (($row = $db->fetchByAssoc($result)) != null) {
            if ($row['status'] != 'held') {
                return false;
            }
        }
        return true;
    }

    protected function areExitInterviewsHeld()
    {
        global $db;
        $boarding_id = $this->focus->id;
        $sql = "SELECT id, status FROM exitinterviews WHERE offboarding_id='{$boarding_id}'";
        $result = $db->query($sql);
        while (($row = $db->fetchByAssoc($result)) != null) {
            if ($row['status'] != 'held') {
                return false;
            }
        }
        return true;
    }

    protected function areTasksHeld()
    {
        global $db;
        $boarding_id = $this->focus->id;
        $sql = "SELECT id, status FROM tasks WHERE parent_id='{$boarding_id}'";
        $result = $db->query($sql);
        while (($row = $db->fetchByAssoc($result)) != null) {
            if ($row['status'] != 'Completed' && $row['status'] != 'Deferred') {
                return false;
            }
        }
        return true;
    }

    protected function close()
    {
        $this->focus->status = 'held';
        $this->focus->save();
    }
}
