<?php
class MeetingsController extends SugarController
{
    protected function action_resetPeriodicity()
    {
        if ($this->bean->id) {
            require_once 'modules/Calendar/CalendarUtils.php';
            CalendarUtils::markRepeatDeleted($this->bean);

            $fields = ['repeat_parent_id', 'repeat_type', 'repeat_interval', 'repeat_dow', 'repeat_until', 'repeat_count'];
            $update_fields = [];
            foreach ($fields as $field) {
                if ('repeat_interval' === $field) {
                    $update_fields[] = $field . '=1';
                } else {
                    $update_fields[] = $field . '=NULL';
                }
            }
            $update_fields = implode(',', $update_fields);
            $GLOBALS['db']->query("UPDATE meetings SET {$update_fields} WHERE id='{$this->bean->id}'");

            $url = 'index.php?action=EditView&module=Meetings&record=';
            SugarApplication::redirect($url . $this->bean->id);
        }
    }
}