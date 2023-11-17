<?php
namespace Api\V8\Service;

use Api\V8\BeanDecorator\BeanManager;
use Api\V8\JsonApi\Helper\AttributeObjectHelper;
use Api\V8\JsonApi\Helper\RelationshipObjectHelper;
use Api\V8\Param\MonthInfoParams;
use Slim\Http\Request;

class MonthInfoService
{
    protected $beanManager;
    protected $attributeHelper;
    protected $relationshipHelper;

    public function __construct(
        BeanManager $beanManager,
        AttributeObjectHelper $attributeHelper,
        RelationshipObjectHelper $relationshipHelper
    ) {
        $this->beanManager = $beanManager;
        $this->attributeHelper = $attributeHelper;
        $this->relationshipHelper = $relationshipHelper;
    }

    public function getMonthInfo(MonthInfoParams $params, Request $request)
    {
        global $db, $timedate;
        $employee_id = $params->getEmployeeId();
        $date = explode('-', $params->getDate());
        $year = $date[0];
        $month = $date[1];
        $modules = [
            'WorkSchedules' => 'workSchedule',
            'Meetings' => 'meeting',
            'Calls' => 'call',
            'Tasks' => 'task',
        ];

        $data = [];

        $start_time = strtotime("01-" . $month . "-" . $year);
        $end_time = strtotime("+1 month", $start_time);
        $date_format = $timedate->get_db_date_format();

        $start_date = date($date_format, $start_time);
        $end_date = date($date_format, $end_time);

        $current_time_zone = date_default_timezone_get();
        date_default_timezone_set('UTC');

        foreach ($modules as $key => $value) {
            $module_data = $db->query($this->getModuleIds($employee_id, $start_date, $end_date, $key));
            while (($row = $db->fetchByAssoc($module_data)) != null) {
                $data[][$value] = [
                    'id' => $row['id'],
                    'type' => ($key === 'WorkSchedules') ? $key : ucfirst($value),
                    'attributes' => array_map(function ($value) {
                        return is_string($value)
                        ? (\DateTime::createFromFormat('Y-m-d H:i:s', $value)
                            ? date(\DateTime::ATOM, strtotime($value))
                            : html_entity_decode(htmlspecialchars_decode($value), ENT_QUOTES))
                        : $value;
                    }, array_slice($row, 1)),
                ];
            }
        }

        date_default_timezone_set($current_time_zone);

        return $data;
    }

    protected function getModuleIds($employee_id, $start_date, $end_date, $module)
    {
        switch ($module) {
            case 'WorkSchedules':
                $sql_query = "SELECT id, name, type, status, date_start, date_end
                    FROM workschedules
                    WHERE assigned_user_id = '{$employee_id}'
                        AND schedule_date >= '{$start_date}'
                        AND schedule_date < '{$end_date}'
                        AND deleted = 0
                    ORDER BY schedule_date ASC";
                break;
            case 'Meetings':
                $sql_query = "SELECT m.id, m.name, m.date_start, m.duration_hours, m.duration_minutes, m.date_end, m.type, m.status
                    FROM meetings m INNER JOIN meetings_users mu ON m.id = mu.meeting_id
                    WHERE m.date_start >= '{$start_date}'
                        AND m.date_start < '{$end_date}'
                        AND m.deleted = 0
                        AND mu.user_id = '{$employee_id}'
                    ORDER BY m.date_start ASC";
                break;
            case 'Calls':
                $sql_query = "SELECT c.id, c.name, c.date_start, c.duration_hours, c.duration_minutes, c.date_end, c.status
                    FROM calls c INNER JOIN calls_users cu ON c.id = cu.call_id
                    WHERE c.date_start >= '{$start_date}'
                        AND c.date_start < '{$end_date}'
                        AND c.deleted = 0
                        AND cu.user_id = '{$employee_id}'
                    ORDER BY c.date_start ASC";
                break;
            case 'Tasks':
                $sql_query = "SELECT id, name, date_start, status
                    FROM tasks
                    WHERE date_start >= '{$start_date}'
                        AND date_start < '{$end_date}'
                        AND deleted = 0
                        AND assigned_user_id = '{$employee_id}'
                    ORDER BY date_start ASC";
                break;
            default:
        }
        return $sql_query;
    }

}
