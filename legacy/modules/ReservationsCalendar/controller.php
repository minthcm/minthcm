<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

#[\AllowDynamicProperties]
class ReservationsCalendarController extends SugarController
{

    public function getInitCalendarData()
    {
        $data = array(
            'resourcesList' => [],
            'calendarsList' => [],
        );

        $resources = $this->getResources();
        $data['resourcesList'] = $resources;
        $calendars = $this->getCalendarList($resources);
        $data['calendarsList'] = $calendars;
        return $data;
    }

    public function getResourceReservations($resource, $date = null)
    {
        $data = array(
            'reservationsList' => [],
        );
        $reservations = $this->getReservations([$resource], $date);
        $data['reservationsList'] = $reservations;

        return $data;
    }

    protected function getResources() {
        $resource = BeanFactory::getBean('Resources');
        $resources = $resource->get_full_list("name", "type='for_reservation'");
        return (!empty($resources)) ? $resources : array();
     }

    protected function getCalendarList($resources)
    {
        $calendars = array();
        foreach ($resources as $resource) {
            $calendar = array();
            $calendar['id'] = $resource->id;
            $calendar['name'] = $resource->name;
            $calendar['checked'] = false;
            $calendar['color'] = '#000000';
            $calendar['bgColor'] = '#009976';
            $calendar['borderColor'] = '#3A87AD';
            $calendars[] = $calendar;
        }
        return $calendars;
    }

    protected function getReservations($calendars, $date = null)
    {
        if ($date === null) {
            $date = date('Y-m-d');
        }

        $startDate = date('Y-m-d', strtotime("$date -2 month"));
        $endDate = date('Y-m-d', strtotime("$date +2 month"));

        $reservations = array();
        $reservation = BeanFactory::getBean('Reservations');
        foreach ($calendars as $calendar) {
            $reservation_beans = $reservation->get_full_list("name",
                "resource_id='{$calendar['id']}' AND ending_date > '$startDate' AND starting_date < '$endDate'");
            if (!empty($reservation_beans)) {
                foreach ($reservation_beans as $bean) {
                    $row = array();
                    $row['id'] = $bean->id;
                    $row['calendarId'] = $bean->resource_id;
                    $row['title'] = $bean->name;
                    $row['category'] = "time";
                    $row['start'] = $this->getDate($bean->starting_date);
                    $row['end'] = $this->getDate($bean->ending_date);
                    $row['attendees'] = ['anyone'];
                    $row['raw'] = ['creator' => ['name' => $bean->assigned_user_name]];
                    $row['isReadOnly'] = !($bean->ACLAccess('edit'));
                    $row['isNotDeletable'] = !($bean->ACLAccess('delete'));
                    $row['detailViewAccess'] = $bean->ACLAccess('detail');
                    $reservations[] = $row;
                }
            }
        }
        return $reservations;
    }

    protected function getDate($date)
    {
        global $current_user, $timedate;
        $tz = $current_user->getPreference('timezone');
        $datef = $current_user->getPreference('datef');
        $timef = $current_user->getPreference('timef');
        $format = $datef . " " . $timef;
        if (empty($tz)) {
            $tz = 'UTC';
        }
        $tzo = new DateTimeZone($tz);
        $result_date = $timedate->asUser($timedate->fromDb($date), $current_user);
        $result_date = DateTime::createFromFormat($format, $result_date, $tzo);
        return $result_date->format('c');
    }

}
